<?php

namespace App\Services\Crm;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class LeadImportService
{
    public function isSupportedImportExtension(string $extension): bool
    {
        return in_array(strtolower($extension), ['csv', 'xlsx', 'xls', 'txt'], true);
    }

    public function suggestMappings(array $headers, array $autoMappings): array
    {
        $suggestedMapping = [];

        foreach ($headers as $index => $header) {
            $normalised = strtolower(trim(preg_replace('/[\s_\-]+/', ' ', (string) $header)));
            $normalised = trim((string) preg_replace('/\s*\(.*?\)\s*$/', '', $normalised));

            if (isset($autoMappings[$normalised])) {
                $suggestedMapping[$index] = $autoMappings[$normalised];
                continue;
            }

            $found = '';
            $bestLength = 0;
            foreach ($autoMappings as $alias => $field) {
                if (strlen($alias) >= 3 && str_contains($normalised, $alias) && strlen($alias) > $bestLength) {
                    $found = $field;
                    $bestLength = strlen($alias);
                }
            }

            $suggestedMapping[$index] = $found;
        }

        return $suggestedMapping;
    }

    public function buildFieldToColumns(array $columnMap): array
    {
        $fieldToColumns = [];

        foreach ($columnMap as $colIndex => $fieldName) {
            if ($fieldName && $fieldName !== 'skip') {
                $fieldToColumns[$fieldName][] = (int) $colIndex;
            }
        }

        return $fieldToColumns;
    }

    public function processMappedRows(
        array $dataRows,
        array $fieldToColumns,
        ?int $orgId,
        ?int $driveId,
        string $defaultStatus,
        callable $descriptionRowChecker
    ): array {
        $imported = 0;
        $skipped = 0;
        $errorRows = 0;
        $descriptionRowsSkipped = 0;
        $errors = [];
        $existingLeadCache = [];
        $seenEmails = [];

        foreach ($dataRows as $rowIndex => $row) {
            $rowNumber = $rowIndex + 2;

            $nonEmpty = array_filter($row, fn ($value) => $value !== null && trim((string) $value) !== '');
            if (empty($nonEmpty)) {
                continue;
            }

            if ($descriptionRowChecker($row, $fieldToColumns)) {
                $descriptionRowsSkipped++;
                continue;
            }

            $prepared = $this->buildLeadDataFromMappedRow($row, $fieldToColumns, $orgId, $driveId, $defaultStatus);

            if ($prepared['error']) {
                $errorRows++;
                $errors[] = [
                    'row' => $rowNumber,
                    'type' => 'validation',
                    'message' => $prepared['error']['message'],
                    'affected_field' => $prepared['error']['affected_field'],
                    'raw' => $row,
                    'parsed' => $prepared['data'],
                ];
                continue;
            }

            try {
                $result = $this->importPreparedLead($prepared['data'], $orgId, $existingLeadCache, $seenEmails, true);

                if (in_array($result['status'], ['created', 'restored'], true)) {
                    $imported++;
                    continue;
                }

                if ($result['status'] === 'duplicate') {
                    $skipped++;
                    $errors[] = [
                        'row' => $rowNumber,
                        'type' => 'duplicate',
                        'message' => $result['message'],
                        'affected_field' => 'email',
                        'raw' => $row,
                        'parsed' => $prepared['data'],
                    ];
                    continue;
                }
            } catch (QueryException $exception) {
                $errorRows++;
                $errors[] = [
                    'row' => $rowNumber,
                    'type' => 'database',
                    'message' => $this->friendlyDbError($exception, $prepared['data']),
                    'affected_field' => $this->extractErrorField($exception),
                    'raw' => $row,
                    'parsed' => $prepared['data'],
                ];
            } catch (\Exception $exception) {
                $errorRows++;
                $errors[] = [
                    'row' => $rowNumber,
                    'type' => 'general',
                    'message' => Str::limit($exception->getMessage(), 120),
                    'affected_field' => null,
                    'raw' => $row,
                    'parsed' => $prepared['data'],
                ];
            }
        }

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'error_rows' => $errorRows,
            'description_rows_skipped' => $descriptionRowsSkipped,
            'errors' => $errors,
        ];
    }

    public function buildLeadDataFromMappedRow(
        array $row,
        array $fieldToColumns,
        ?int $orgId,
        ?int $driveId,
        string $defaultStatus
    ): array {
        $data = [
            'organization_id' => $orgId,
            'drive_id' => $driveId,
            'status' => $defaultStatus,
            'source' => 'other',
            'priority' => 'medium',
        ];
        $rawValues = [];

        foreach ($fieldToColumns as $field => $colIndices) {
            $colIndex = $colIndices[0];
            $value = isset($row[$colIndex]) ? trim((string) $row[$colIndex]) : '';

            if ($value === '') {
                continue;
            }

            $rawValues[$field] = $value;

            try {
                $this->applyFieldValue($data, $rawValues, $field, $value, $orgId, $defaultStatus);
            } catch (\Exception $exception) {
                Log::warning("Import: field '{$field}' processing failed", [
                    'value' => $value,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return $this->finalizeLeadData($data, $rawValues, $orgId, $defaultStatus);
    }

    public function buildLeadDataFromManualFields(
        array $fields,
        ?int $orgId,
        ?int $driveId,
        string $defaultStatus
    ): array {
        $data = [
            'organization_id' => $orgId,
            'drive_id' => $driveId,
            'status' => $defaultStatus,
            'source' => 'other',
            'priority' => 'medium',
        ];
        $rawValues = [];

        foreach ($fields as $field => $value) {
            $value = trim((string) $value);
            if ($value === '') {
                continue;
            }

            $rawValues[$field] = $value;

            try {
                $this->applyFieldValue($data, $rawValues, $field, $value, $orgId, $defaultStatus);
            } catch (\Exception $exception) {
                Log::warning("Import review: field '{$field}' processing failed", [
                    'value' => $value,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        return $this->finalizeLeadData($data, $rawValues, $orgId, $defaultStatus);
    }

    public function importPreparedLead(
        array $leadData,
        ?int $orgId,
        array &$existingLeadCache = [],
        array &$seenEmails = [],
        bool $trackBatchDuplicates = false
    ): array {
        $email = isset($leadData['email']) ? Str::lower(trim((string) $leadData['email'])) : null;

        if ($email) {
            if ($trackBatchDuplicates && isset($seenEmails[$email])) {
                return [
                    'status' => 'duplicate',
                    'message' => "Duplicate email \"{$leadData['email']}\" appears more than once in this import file — skipped.",
                ];
            }

            $existingLead = $this->findExistingLeadByEmail($email, $orgId, $existingLeadCache);

            if ($existingLead instanceof Lead) {
                if ($existingLead->trashed()) {
                    $existingLead->restore();
                    $existingLead->update($leadData);
                    $existingLeadCache[$this->cacheKey($email, $orgId)] = $existingLead->fresh();
                    $seenEmails[$email] = true;

                    return [
                        'status' => 'restored',
                        'message' => "Restored existing deleted lead for \"{$leadData['email']}\".",
                        'lead' => $existingLead,
                    ];
                }

                return [
                    'status' => 'duplicate',
                    'message' => "Duplicate email \"{$leadData['email']}\" already exists in your organisation — skipped.",
                    'lead' => $existingLead,
                ];
            }
        }

        $lead = Lead::create($leadData);

        if ($email) {
            $existingLeadCache[$this->cacheKey($email, $orgId)] = $lead;
            $seenEmails[$email] = true;
        }

        return [
            'status' => 'created',
            'message' => 'Lead imported successfully.',
            'lead' => $lead,
        ];
    }

    public function friendlyDbError(\Exception $exception, array $leadData): string
    {
        $message = $exception->getMessage();

        if (str_contains($message, 'Data truncated') || str_contains($message, '1265')) {
            if (preg_match("/column '(\w+)'/", $message, $matches)) {
                $column = $matches[1];
                $value = $leadData[$column] ?? 'unknown';
                $friendly = str_replace('_', ' ', $column);

                return "Invalid value \"{$value}\" for {$friendly}. Please check the format.";
            }

            return 'A value was in the wrong format and could not be saved.';
        }

        if (str_contains($message, 'Incorrect integer') || str_contains($message, '1366')) {
            if (preg_match("/column '(\w+)'/", $message, $matches)) {
                $friendly = str_replace('_', ' ', $matches[1]);

                return "Non-numeric value provided for {$friendly}.";
            }

            return 'A numeric field received a non-numeric value.';
        }

        if (str_contains($message, 'too long') || str_contains($message, '1406')) {
            if (preg_match("/column '(\w+)'/", $message, $matches)) {
                $friendly = str_replace('_', ' ', $matches[1]);

                return "Value too long for {$friendly}. Please shorten it.";
            }

            return 'A value was too long for its field.';
        }

        if (str_contains($message, 'Duplicate entry') || str_contains($message, '1062')) {
            if (str_contains($message, 'leads_org_email_unique') && preg_match("/Duplicate entry '(?:\d+-)?(.*?)' for key/", $message, $matches)) {
                return "A lead with email \"{$matches[1]}\" already exists in your organisation.";
            }

            if (preg_match("/Duplicate entry '(.+?)' for key/", $message, $matches)) {
                return "Duplicate value \"{$matches[1]}\" — this record already exists.";
            }

            return 'This record already exists (duplicate).';
        }

        if (str_contains($message, 'cannot be null') || str_contains($message, '1048')) {
            if (preg_match("/Column '(\w+)'/", $message, $matches)) {
                $friendly = str_replace('_', ' ', $matches[1]);

                return "The {$friendly} field is required and cannot be empty.";
            }

            return 'A required field was left empty.';
        }

        if (str_contains($message, 'Out of range') || str_contains($message, '1264')) {
            if (preg_match("/column '(\w+)'/", $message, $matches)) {
                $friendly = str_replace('_', ' ', $matches[1]);

                return "Value out of range for {$friendly}. Please use a smaller number.";
            }

            return 'A numeric value was out of the allowed range.';
        }

        if (str_contains($message, 'SQLSTATE')) {
            if (preg_match("/column '(\w+)'/i", $message, $matches)) {
                $column = $matches[1];
                $value = $leadData[$column] ?? null;
                $friendly = str_replace('_', ' ', $column);
                $hint = $value !== null ? " (value: \"{$value}\")" : '';

                return "Database error on {$friendly}{$hint}. Please check the value.";
            }

            return 'Database error — please check your data for invalid values. Check the log for details.';
        }

        return Str::limit($message, 120);
    }

    public function extractErrorField(\Exception $exception): ?string
    {
        if (preg_match("/column '(\w+)'/i", $exception->getMessage(), $matches)) {
            return $matches[1];
        }

        return null;
    }

    private function applyFieldValue(
        array &$data,
        array &$rawValues,
        string $field,
        string $value,
        ?int $orgId,
        string $defaultStatus
    ): void {
        switch ($field) {
            case 'full_name':
                $parts = preg_split('/\s+/', $value, 2);
                $data['first_name'] = $parts[0] ?? '';
                $data['last_name'] = $parts[1] ?? '';
                break;

            case 'first_name':
            case 'last_name':
            case 'email':
            case 'phone':
            case 'company':
            case 'job_title':
            case 'employee_count':
            case 'industry':
            case 'website':
            case 'company_linkedin_profile':
            case 'ceo_linkedin_profile':
            case 'country':
            case 'city':
            case 'address':
            case 'notes':
            case 'requirements':
            case 'lost_reason':
                $data[$field] = $value;
                break;

            case 'year_founded':
                if (preg_match('/\b((?:19|20)\d{2})\b/', $value, $matches)) {
                    $data['year_founded'] = (int) $matches[1];
                }
                break;

            case 'currency':
                $code = strtoupper((string) preg_replace('/[^a-zA-Z]/', '', $value));
                if (strlen($code) === 3) {
                    $data['currency'] = $code;
                }
                break;

            case 'status':
                $statuses = array_keys(Lead::getStatuses($orgId));
                $normalised = strtolower(trim(str_replace([' ', '-'], '_', $value)));
                $data['status'] = in_array($normalised, $statuses, true) ? $normalised : $defaultStatus;
                break;

            case 'interested_services':
                $services = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $value))));
                if (!empty($services)) {
                    $data['interested_services'] = $services;
                }
                break;

            case 'last_contacted_at':
            case 'next_follow_up':
                try {
                    $data[$field] = \Carbon\Carbon::parse($value)->toDateTimeString();
                } catch (\Exception $exception) {
                }
                break;

            case 'source':
                $sources = array_keys(Lead::getSources($orgId));
                $normalised = strtolower(str_replace([' ', '-'], '_', $value));
                $data['source'] = in_array($normalised, $sources, true) ? $normalised : 'other';
                break;

            case 'priority':
                $priorities = array_keys(Lead::getPriorities());
                $normalised = strtolower(trim($value));
                $data['priority'] = in_array($normalised, $priorities, true) ? $normalised : 'medium';
                break;

            case 'estimated_value':
                $numeric = preg_replace('/[^0-9.]/', '', $value);
                if ($numeric !== '' && is_numeric($numeric)) {
                    $data['estimated_value'] = (float) $numeric;
                }
                break;
        }
    }

    private function finalizeLeadData(array $data, array $rawValues, ?int $orgId, string $defaultStatus): array
    {
        if (!empty($rawValues['email'])) {
            $email = Str::lower(trim($rawValues['email']));
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return [
                    'data' => $data,
                    'error' => [
                        'message' => "Invalid email address \"{$rawValues['email']}\". Please provide a valid email.",
                        'affected_field' => 'email',
                    ],
                ];
            }
            $data['email'] = $email;
        }

        if (!empty($rawValues['year_founded']) && empty($data['year_founded'])) {
            return [
                'data' => $data,
                'error' => [
                    'message' => "Invalid year founded value \"{$rawValues['year_founded']}\". Enter a 4-digit year.",
                    'affected_field' => 'year_founded',
                ],
            ];
        }

        if (!empty($rawValues['currency']) && empty($data['currency'])) {
            return [
                'data' => $data,
                'error' => [
                    'message' => "Invalid currency code \"{$rawValues['currency']}\". Use a 3-letter code like USD.",
                    'affected_field' => 'currency',
                ],
            ];
        }

        if (!empty($rawValues['estimated_value']) && !array_key_exists('estimated_value', $data)) {
            return [
                'data' => $data,
                'error' => [
                    'message' => "Invalid estimated value \"{$rawValues['estimated_value']}\". Use numbers only.",
                    'affected_field' => 'estimated_value',
                ],
            ];
        }

        if (empty($data['first_name']) && empty($data['last_name']) && empty($data['email']) && empty($data['company'])) {
            return [
                'data' => $data,
                'error' => [
                    'message' => 'Row must include at least a name, company, or email before it can be imported.',
                    'affected_field' => 'first_name',
                ],
            ];
        }

        if (empty($data['first_name'])) {
            if (!empty($data['company'])) {
                $data['first_name'] = Str::limit($data['company'], 255, '');
            } elseif (!empty($data['email'])) {
                $data['first_name'] = Str::limit(Str::before($data['email'], '@'), 255, '');
            } elseif (!empty($data['last_name'])) {
                $data['first_name'] = $data['last_name'];
            } else {
                $data['first_name'] = 'Unknown';
            }
        }

        $fillable = (new Lead())->getFillable();
        $data = array_intersect_key($data, array_flip($fillable));

        $safeStatuses = array_keys(Lead::getStatuses($orgId));
        $safeSources = array_keys(Lead::getSources($orgId));
        $safePriorities = array_keys(Lead::getPriorities());

        if (!empty($data['status']) && !in_array($data['status'], $safeStatuses, true)) {
            $data['status'] = $defaultStatus;
        }
        if (!empty($data['source']) && !in_array($data['source'], $safeSources, true)) {
            $data['source'] = 'other';
        }
        if (!empty($data['priority']) && !in_array($data['priority'], $safePriorities, true)) {
            $data['priority'] = 'medium';
        }

        return [
            'data' => $data,
            'error' => null,
        ];
    }

    private function findExistingLeadByEmail(string $email, ?int $orgId, array &$cache): Lead|Model|false|null
    {
        $key = $this->cacheKey($email, $orgId);

        if (!array_key_exists($key, $cache)) {
            $query = Lead::withTrashed()->whereRaw('LOWER(email) = ?', [$email]);
            if ($orgId) {
                $query->where('organization_id', $orgId);
            } else {
                $query->whereNull('organization_id');
            }

            $cache[$key] = $query->first() ?: false;
        }

        return $cache[$key] ?: null;
    }

    private function cacheKey(string $email, ?int $orgId): string
    {
        return ($orgId ?? 'global') . '|' . Str::lower($email);
    }
}