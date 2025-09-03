<?php

namespace App\Exports\Employee;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class PositionsSheet implements FromCollection
{
    protected $positions;

    public function __construct($positions)
    {
        $this->positions = $positions;
    }

    public function collection()
    {
        $data = collect([['id', 'name']]);

        foreach ($this->positions as $p) {
            $data->push([hid($p['id']), $p['name']]);
        }

        return $data;
    }
}
