<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Lib\KiteConnect;
use App\Models\KiteToken;
use GuzzleHttp\Client as GuzzleClient;
class WelcomeController extends Controller
{
    private $kite;

    public function __construct()
    {
        $this->kite = new KiteConnect("tnpl2yjakthm5zcg");
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {

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
        // $body = [
        //     "status"=>[
        //         "NEW",
        //         "CANCELLED",
        //         "ACTIVE",
        //         "SENTTOEXCHANGE",
        //         "FORALL"
        //     ],
        //     "page"=>1,
        //     "count"=>10
        // ];
        
        $body = [
                "mode"=> "FULL",
                "exchangeTokens"=> [
                //     "NSE"=> [
                //         "3045",
                //         "23317",
                //         "11754",
                // ]
                // "MCX"=> [
                //         "426957",
                //         "141625",
                // ]
                // "NFO"=> [
                //         "141625",
                    
                // ]
                "BSE"=> [
                        "99919000",
                    
                ],
                "NSE"=>[
                    "26000",
                ]
            ]
        ];
        
            $url ='https://apiconnect.angelbroking.com/rest/secure/angelbroking/market/v1/quote';
            // $url = "https://apiconnect.angelbroking.com/rest/secure/angelbroking/gtt/v1/ruleList";
            
            $r = $client->request('POST', $url, [
                'body' => json_encode($body)
            ]);
            // dd($r->getBody()->getContents());
            $response = (array)json_decode($r->getBody()->getContents());

            // dd($response);
        
        if(\Request::get('auth_token'))
        {
          try {
            $kite_token = KiteToken::first();
            
            $kite_token->user_id = auth()->user()->id;
            $kite_token->access_token = json_encode(\Request::all());
            $kite_token->save();
          } catch (\Throwable $th) {
            //throw $th;
          }
        }
        return view('pages.welcome.app')->with('isWelcomePage', true)->with('isvideo', false);;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
