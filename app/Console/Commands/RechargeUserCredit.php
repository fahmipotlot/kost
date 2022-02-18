<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class RechargeUserCredit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'credit:recharge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recharge User Credit every Start of the month';

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
     * @return int
     */
    public function handle()
    {
        $users = User::where('type', '!=', 2)->get();
        foreach ($users as $user) {
            $user_credit = $user->credit;
            if ($user->type == 0) {
                $credit = $user_credit + 20;
            } else {
                $credit = $user_credit + 40;
            }

            $user->update(['credit' => $credit]);
        }
    }
}
