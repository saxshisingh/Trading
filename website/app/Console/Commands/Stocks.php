<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Segment;
use App\Models\Script;
use App\Models\ExpiryDate;
class Stocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:stocks';

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
    $token = \App\Models\AccessToken::first();   

    // Directly use the env function to get the values
    $loginId = "DC-MOHD8340";
    $product = "DIRECTRTLITE";
    
    $apiKey = "05546177F7514194AC29";

    $api = "https://qbase1.vbiz.in/directrt/gettickers/?loginid={$loginId}&product={$product}&accesstoken={$token->token}";
    $get = file_get_contents($api);
    
    $arr = explode(',', $get);
    foreach($arr as $a)
    {
        $single = explode("_", $a);    
        $name = $single[0].''.substr($single[1], 0, 3);
        $postfix = $single[1];
        $segment = Segment::where('name', $name)->first();
        if(!$segment)
        {
            $segment = new Segment();
            $segment->name = $name;
            $segment->postfix = $postfix;
            $segment->save();
        }
        if($segment){

            $script = Script::where('name', $single[2])->where('segment_id', $segment->id)->first();
            if(!$script)
            {
                $script = new Script();
            }
            $script->name = $single[2];
            $script->segment_id = $segment->id;
            if($script->save())
            {
                if(isset($single[3]))
                {
                    $date = ExpiryDate::where('expiry_date',$single[3])->where('script_id', $script->id)->first();
                    if(!$date)
                    {
                        $date = new ExpiryDate();
                    }
                    $date->expiry_date = $single[3];
                    $date->script_id = $script->id;
                    $date->name= $a;
                    $date->save();
                }
            }
        }
    }
}


}
