<?php

namespace App\Exports\Employee;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EmployeeTemplateExport implements WithMultipleSheets
{
    protected $companies;
    protected $divisions;
    protected $positions;

    public function __construct($companies, $divisions, $positions)
    {
        $this->companies = $companies;
        $this->divisions = $divisions;
        $this->positions = $positions;
    }

    public function sheets(): array
    {
        return [
            'employees' => new EmployeeHeaderSheet(),
            'companies' => new CompaniesSheet($this->companies),
            'divisions' => new DivisionsSheet($this->divisions),
            'positions' => new PositionsSheet($this->positions),
        ];
    }
}
