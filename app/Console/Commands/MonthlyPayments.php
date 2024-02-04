<?php

namespace App\Console\Commands;

use App\Facades\Hierarchy;
use App\Facades\Report;
use Illuminate\Console\Command;

class MonthlyPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'monthly_payments_set {bonus_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if($this->argument('bonus_id') == 1){
            Report::cumulativeCalculation();
        }
        elseif($this->argument('bonus_id') == 2){
            Hierarchy::checkActivationStatus();
        }
        elseif($this->argument('bonus_id') == 3){
            Report::cumulativeWorldBonusForDirectors();
        }
        elseif($this->argument('bonus_id') == 4){
            Report::cumulativeWorldBonusForMasters();
        }
        elseif($this->argument('bonus_id') == 5){
            Report::setMonthlyOrderSum();
        }
        elseif($this->argument('bonus_id') == 6){
            Report::setMonthlyСommandPv();
        }
        else{
            Hierarchy::telegramTestSend();
        }

    }
}
