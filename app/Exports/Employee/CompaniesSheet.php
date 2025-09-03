<?php

namespace App\Exports\Employee;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CompaniesSheet implements FromCollection
{
    protected $companies;

    public function __construct($companies)
    {
        $this->companies = $companies;
    }

    public function collection()
    {
        $data = collect([['id', 'name']]);

        foreach ($this->companies as $c) {
            $data->push([hid($c['id']), $c['name']]);
        }

        return $data;
    }
}
