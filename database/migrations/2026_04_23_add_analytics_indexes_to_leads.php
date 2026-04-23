<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * All indexes we want to add: [name => columns]
     */
    private array $indexes = [
        'leads_created_by_index'                                => ['created_by'],
        'leads_organization_id_index'                           => ['organization_id'],
        'leads_drive_id_index'                                  => ['drive_id'],
        'leads_source_type_index'                               => ['source_type'],
        'leads_created_at_index'                                => ['created_at'],
        'leads_organization_id_created_by_created_at_index'     => ['organization_id', 'created_by', 'created_at'],
        'leads_organization_id_drive_id_created_at_index'       => ['organization_id', 'drive_id', 'created_at'],
        'leads_organization_id_source_type_created_at_index'    => ['organization_id', 'source_type', 'created_at'],
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $existingIndexNames = $this->getExistingIndexNames();

        Schema::table('leads', function (Blueprint $table) use ($existingIndexNames) {
            foreach ($this->indexes as $name => $columns) {
                if (!in_array($name, $existingIndexNames, true)) {
                    $table->index($columns, $name);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $existingIndexNames = $this->getExistingIndexNames();

        Schema::table('leads', function (Blueprint $table) use ($existingIndexNames) {
            foreach (array_keys($this->indexes) as $name) {
                if (in_array($name, $existingIndexNames, true)) {
                    $table->dropIndex($name);
                }
            }
        });
    }

    /**
     * Retrieve the names of all current indexes on the leads table.
     */
    private function getExistingIndexNames(): array
    {
        $dbName = DB::connection()->getDatabaseName();

        return DB::table('information_schema.statistics')
            ->where('table_schema', $dbName)
            ->where('table_name', 'leads')
            ->distinct()
            ->pluck('index_name')
            ->map(fn ($n) => strtolower($n))
            ->toArray();
    }
};
