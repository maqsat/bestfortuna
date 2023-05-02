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
        return view('exports.new-contracts', [
            'users' => User::whereBetween('created_at', [Carbon::parse('04/04/2023'), Carbon::parse('03/05/2023')])->get()
        ]);
    }
}
