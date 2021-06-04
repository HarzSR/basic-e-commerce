<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class usersExport implements FromCollection, WithHeadingRow
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $usersData = User::select('name', 'address', 'city', 'state', 'country', 'pincode', 'mobile', 'email', 'admin', 'status', 'created_at')->get();

        return $usersData;
    }

    public function headings(): array {
        return ['name', 'address', 'city', 'state', 'country', 'pincode', 'mobile', 'email', 'admin', 'status', 'created_at'];
    }
}
