<?php

namespace App\Exports\Employee;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class EmployeeHeaderSheet implements FromCollection
{
    public function collection()
    {
        return collect([[
            'company_id',
            'employee_number',
            'name',
            'division_id',
            'position_id',
            'join_date',
            'end_date',
            'employment_status',
            'grade_level',
            'gender',
            'date_of_birth',
            'place_of_birth',
            'marital_status',
            'national_id',
            'tax_number',
            'phone_number',
            'address',
            'kontak_darurat',
        ]]);
    }
}
