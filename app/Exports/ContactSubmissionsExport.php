<?php

namespace App\Exports;

use App\Models\ContactSubmission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class ContactSubmissionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Company Name',
            'Email',
            'Phone',
            'Services',
            'Message',
            'IP Address',
            'User Agent',
            'Email Sent At',
            'Submitted At'
        ];
    }

    /**
     * @param ContactSubmission $contact
     */
    public function map($contact): array
    {
        return [
            $contact->id,
            $contact->name,
            $contact->company_name,
            $contact->email,
            $contact->phone,
            is_array($contact->services) ? implode(', ', $contact->services) : $contact->services,
            $contact->message,
            $contact->ip_address,
            $contact->user_agent,
            $contact->email_sent_at?->format('Y-m-d H:i:s'),
            $contact->created_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @param Worksheet $sheet
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
        ];
    }
}
