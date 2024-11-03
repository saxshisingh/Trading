<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trading;
use App\Models\Position;
use App\Models\WatchlistLog;
use App\Models\Wallet;
use App\Models\ExpiryDate;
use App\Models\User;
use Carbon\Carbon;

class CheckPosition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-position';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now();
        $todayeDate = $now->format('Y-m-d');
        $currentTime = $now->format('H:i:s');
        $targetTime = '15:28:00';

        $positions = Position::whereDate('created_at', $todayDate)->where('status','open')->get();

        if ($positions->isEmpty()) {
            $this->info('No open positions found.');
            return;
        }

        foreach ($positions as $position) {
            $dateText = $position->created_at->format('d-m-Y'); 

            if ($dateText === $todayDate && $currentTime >= $targetTime) 
            {
                $this->info("Position ID {$position->id} meets the criteria.");
                
                if($position)
                {
                    $stock = $position->expiry->stock;
                    $data = WatchlistLog::where('stock',$stock)->first();
                    $position->portfolio_status = 'close';
                    $wallet = new Wallet();
                    $user = User::find($position->user_id);
                    if($position->action=="BUY")
                    {
                        $mtm = $data['BSP'];
                        if ($mtm > 0) {
                            $wallet->category = "SELL ".$position->script->name.$position->expiry->expiry_date;
                            $wallet->remarks = "SELL ".$position->script->name.$position->expiry->expiry_date." Successfully";
                            $wallet->amount = $mtm;
                            $wallet->type = "credit";
                            $user->balance += $mtm;
                            $user->credit += $mtm; 
                        }
                        else{
                            $wallet->category = "BUY ".$position->script->name.$position->expiry->expiry_date;
                            $wallet->remarks = "BUY ".$position->script->name.$position->expiry->expiry_date." Successfully";
                            $wallet->amount = -$mtm;
                            $wallet->type = "debit";
                            $user->balance -= $mtm;
                            $user->debit += $mtm; 
                        }
                    }
                    if($position->action=="SELL")
                    {
                        $mtm = $data['BBP'];
                        if ($mtm > 0) {
                            $wallet->category = "BUY ".$position->script->name.$position->expiry->expiry_date;
                            $wallet->remarks = "BUY ".$position->script->name.$position->expiry->expiry_date." Successfully";
                            $wallet->amount = $mtm;
                            $wallet->type = "credit";
                            $user->balance += $mtm;
                            $user->credit += $mtm; 
                        }
                        else{
                            $wallet->category = "BUY ".$position->script->name.$position->expiry->expiry_date;
                            $wallet->remarks = "BUY ".$position->script->name.$position->expiry->expiry_date." Successfully";
                            $wallet->amount = -$mtm;
                            $wallet->type = "debit";
                            $user->balance -= $mtm;
                            $user->debit += $mtm; 
                        }
                    }
                    $wallet->user_id = $position->user_id;
                    $wallet->trade_id = $position->id;
                    $wallet->save();
                    $user->save();
                    $position->save();
                }
            }
        }
        $this->info('Position check completed.');
    }
}
