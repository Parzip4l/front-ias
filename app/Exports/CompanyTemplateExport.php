<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CompanyTemplateExport implements WithMultipleSheets
{
    protected $types;

    public function __construct($types)
    {
        $this->types = $types;
    }

    public function sheets(): array
    {
        return [
            new CompanyImportSheet(),
            new CompanyTypeSheet($this->types),
        ];
    }
}
