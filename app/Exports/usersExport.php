<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class usersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $usersData = User::select('name', 'address', 'city', 'state', 'country', 'pincode', 'mobile', 'email', 'admin', 'status', 'created_at', 'updated_at')->get();

        return $usersData;
    }

    // Add Header to Excel

    public function headings(): array
    {
        return ['Full Name', 'Address', 'City', 'State', 'Country', 'Pincode', 'Mobile', 'Email', 'Admin', 'Status', 'Created At', 'Last Updated At'];
    }
}
