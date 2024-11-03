<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\AccessToken as Token;
use App\Models\KiteToken;
use GuzzleHttp\Client as GuzzleClient;
class AccessToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refresh:token';

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
        $now = date('H:i');
        // if($now == "23:59")
        // {
            // $loginId = 'DC-MOHD8340'; 
            // $product = 'DIRECTRTLITE';
            // $apikey = '05546177F7514194AC29';
            // $authEndPoint = 'http://s3.vbiz.in/directrt/gettoken?loginid='.$loginId.'&product='.$product.'&apikey='.$apikey;        
            // $get = file_get_contents($authEndPoint);        
            // if($get)
            // {
            //     $response = (array)json_decode($get);
            //     $save = Token::first();
            //     if(!$save)
            //     {
            //         $save = new Token;
            //     }
            //     $save->token = $response['AccessToken'];
            //     $save->valid = $response['ValidUntil'];
            //     $save->response = json_encode($response);
            //     $save->save();
            // }
            // AngleBroker token refresh
            $token = KiteToken::whereNull("deleted_at")->first();
            $ip = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
            $headers = [
                'Authorization' => "Bearer ".json_decode($token->access_token, true)['auth_token'],
                'Content-Type'=> 'application/json',
                'Accept'=> 'application/json',
                'X-PrivateKey'=> 'EmlDRPXu'
            ];
            
            $client = new GuzzleClient([
                'headers' => $headers
            ]);
            
            $body = [
                "refreshToken" => json_decode($token->access_token, true)['refresh_token']
            ];
            
            try {
                $r = $client->request('POST', 'https://apiconnect.angelbroking.com/rest/auth/angelbroking/jwt/v1/generateTokens', [
                    'body' => json_encode($body)
                ]);
                $response = (array)json_decode($r->getBody()->getContents());
                
                $res = (array)$response['data'];
                $kite_token = KiteToken::first();
                $data = json_decode($kite_token->access_token, true);
                $data['auth_token'] = $res['jwtToken'];
                $data['feed_token'] = $res['feedToken'];
                $data['refresh_token'] = $res['refreshToken'];
                if(!$kite_token)
                {
                    $kite_token = new KiteToken();
                }
                $kite_token->user_id = 1;
                $kite_token->access_token = json_encode($data);
                $kite_token->save();
            } catch (\Throwable $th) {
            }
        // }
    }
}
