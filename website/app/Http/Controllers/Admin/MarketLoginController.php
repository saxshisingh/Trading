<?php

namespace App\Http\Controllers\Admin;

use App\Lib\KiteConnect;
use App\Models\KiteToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MarketLoginController extends Controller
{
    private $kite;

    public function __construct()
    {
        $this->kite = new KiteConnect("tnpl2yjakthm5zcg");
    }

    public function login()
    {
        $login_url = 'https://smartapi.angelbroking.com/publisher-login?api_key=EmlDRPXu';

        return view('pages.Login.market', ['login_url' => $login_url]);
    }

    public function handleCallback(Request $request)
    {
        $redirected_url = $request->input('redirected_url');
        parse_str(parse_url($redirected_url, PHP_URL_QUERY), $query);
        
        if (!isset($query['request_token'])) {
            return redirect()->back()->withErrors(['error' => 'Failed to retrieve request token from the URL.']);
        }
        
        $request_token = $query['request_token'];

        try {
            $data = $this->kite->generateSession($request_token, "347sf6e3feevon9a1xgyiyw9wcqk5u0p");

            KiteToken::where('user_id', auth()->user()->id)->delete();
            $kite_token = new KiteToken();
            $kite_token->user_id = auth()->user()->id;
            $kite_token->access_token = $data->access_token;
            $kite_token->save();

            return redirect()->route('dashboard.index')->with('success', 'Authentication successful. Access token stored.');
        } catch (Exception $e) {
       
            return redirect()->back()->withErrors(['error' => 'Authentication failed: ' . $e->getMessage()]);
        }
    }
}
