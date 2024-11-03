<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExpiryDate;
use Carbon\Carbon;

class ProessExpiryDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:process-expiry-dates';

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
        $expiryDates = ExpiryDate::all();

        foreach ($expiryDates as $expiryDate) {
            $script = $expiryDate->script;
            $segment = $script->segment;
            $strike = $expiryDate->strike;
            $instrument_type = $expiryDate->instrument_type;

            $scriptname = str_replace(' ', '_', $script->name);
            $exchange = $segment->exchange;

            if ($segment->instrument_type == "EQ") {
                $instr = $segment->name;
            } else {
                if ($exchange == "MCX") {
                    $instr = $segment->instrument_type . 'COM';
                } else {
                    $instr = $segment->instrument_type . 'STK';
                }
            }

            $expireDate = $expiryDate->expiry_date;
            $formattedDate = Carbon::parse($expireDate)->format('dMY');
            $formattedDate = strtoupper($formattedDate);

            if ($instrument_type != "FUT" && $instrument_type != "EQ") {
                $expiryDate->stock = $exchange . '_' . $instr . '_' . $scriptname . '_' . $formattedDate . '_' . $strike . '_' . $instrument_type;
            } else {
                $expiryDate->stock = $exchange . '_' . $instr . '_' . $scriptname;
            }

            $expiryDate->save();
        }

        $this->info('Expiry dates processed successfully.');
    }
    
}
