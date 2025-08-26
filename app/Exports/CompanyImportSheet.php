<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class CompanyImportSheet implements FromCollection
{
    public function collection()
    {
        return new Collection([
            [
                'name',
                'customer_id',
                'email',
                'company_type',
                'phone',
                'address',
                'zipcode',
                'is_pkp',
                'npwp_number',
                'is_active'
            ],
        ]);
    }
}
