<?php

namespace App\Exports;

use App\NewsletterSubscriber;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscribersExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Export All

        return NewsletterSubscriber::all();

        // Subscribers Users

        $subscribersData = NewsletterSubscriber::select('id', 'email', 'status', 'created_at', 'updated_at')->get();

        return $subscribersData;

        // Export Active Users

        $activeSubscribers = NewsletterSubscriber::select('id,', 'email', 'created_at', 'updated_at')->where('status', 1)->get();

        return $activeSubscribers;
    }

    // Add Header to Excel

    public function headings(): array
    {
        return ['ID', 'Email ID', 'Status', 'Created At', 'Last Updated At'];
    }
}
