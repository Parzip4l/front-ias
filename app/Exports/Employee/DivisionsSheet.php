<?php

namespace App\Exports\Employee;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class DivisionsSheet implements FromCollection
{
    protected $divisions;

    public function __construct($divisions)
    {
        $this->divisions = $divisions;
    }

    public function collection()
    {
        $data = collect([['id', 'name']]);

        foreach ($this->divisions as $d) {
            $data->push([$d['id'], $d['name']]);
        }

        return $data;
    }
}
