<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CompanyTypeSheet implements FromCollection
{
    protected $types;

    public function __construct($types)
    {
        $this->types = $types;
    }

    public function collection()
    {
        $data = collect([['id', 'name']]);

        foreach ($this->types as $type) {
            $data->push([
                $type['id'],
                $type['name']
            ]);
        }

        return $data;
    }
}
