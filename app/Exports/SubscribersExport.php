<?php

namespace App\Exports;

use App\NewsletterSubscriber;
use Maatwebsite\Excel\Concerns\FromCollection;

class SubscribersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Export All

        return NewsletterSubscriber::all();

        // Subscribers Users

        $subscribersData = NewsletterSubscriber::select('id', 'email', 'status', 'created_at')->get();

        return $subscribersData;

        // Export Active Users

        $activeSubscribers = NewsletterSubscriber::select('id,', 'email', 'created_at')->where('status', 1)->get();

        return $activeSubscribers;
    }
}
