<?php

namespace App\Exports;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class NewContractsExport implements FromView
{
    public function view(): View
    {
        $date = new \DateTime();

        return view('exports.new-contracts', [
            'users' => User::whereBetween('created_at', [Carbon::parse($date)->startOfMonth(), Carbon::parse($date)->endOfMonth()])->get()
        ]);
    }
}
