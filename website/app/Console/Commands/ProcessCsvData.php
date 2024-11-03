<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Segment;
use App\Models\Script;
use App\Models\ExpiryDate;

class ProcessCsvData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:csv';


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
        $get = file_get_contents("https://margincalculator.angelbroking.com/OpenAPI_File/files/OpenAPIScripMaster.json");
       $data = json_decode($get, true);
       foreach($data as $d)
       {
            try {
                    $sagment = Segment::where('exchange', $d['exch_seg'])->where('instrument_type', $d['instrumenttype'])->first();
                    // if(!$sagment)
                    // {
                    //     $sagment = new Segment();
                    // }
                    // $sagment->name = $d['exch_seg'];
                    // $sagment->instrument_type = $d['instrumenttype'];
                    // $sagment->exchange_token = $d['token'];
                    // $sagment->exchange = $d['exch_seg'];
                    if($sagment)
                    {
                        $script = Script::where('name', $d['name'])->where('segment_id', $sagment->id)->first();
                        if(!$script)
                        {
                            $script = new Script();
                        }
                        $script->name = $d['name'];
                        $script->segment_id = $sagment->id;
                        $script->is_banned = "0";
                        $script->position_limit = $d['lotsize'];
                        $script->max_order = "10000";
                        if($script->save())
                        {
                            if($d['symbol'])
                            {
                                $expiryDate = ExpiryDate::where('script_id', $script->id)->where('tradingsymbol', $d['symbol'])->first();
                                if(!$expiryDate)
                                {
                                    $expiryDate = new ExpiryDate();
                                }
                                $expiryDate->tradingsymbol = $d['symbol']?$d['symbol']:null;
                                $expiryDate->expiry_date = $d['expiry'];
                                $expiryDate->script_id = $script->id;
                                $expiryDate->tick_size = $d['tick_size'];
                                $expiryDate->strike = $d['strike'];
                                $expiryDate->instrument_type = $d['instrumenttype'];
                                $expiryDate->lot_size = $d['lotsize'];
                                $expiryDate->token = $d['token'];
                                $expiryDate->name = $d['name'];
                                $saved=$expiryDate->save();
                                if(!$saved)
                                {
                                    $script->delete();
                                }
                            }
                        }
                
            }
            } catch (\Throwable $th) {
                dd($d['symbol'], $th);
            }
       }
    }
}
