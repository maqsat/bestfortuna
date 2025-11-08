<?php

namespace App\Exports;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MonthOrdersExport implements FromView
{
    public function view(): View
    {
        $date = new \DateTime();
        $date->modify('-1 month');

        return view('exports.month-orders', [
            'users' => User::whereBetween('created_at', [Carbon::parse($date)->startOfMonth(), Carbon::parse($date)->endOfMonth()])->get()
        ]);
    }
}
