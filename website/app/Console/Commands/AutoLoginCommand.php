<?php

namespace App\Console\Commands;
use App\Lib\KiteConnect;
use App\Models\KiteToken;
use Illuminate\Console\Command;

class AutoLoginCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:kite-login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    private $kite;
    
    public function __construct()
    {
        parent::__construct();
        $this->kite = new KiteConnect("tnpl2yjakthm5zcg");
    }
    public function handle()
    {
        $login_url = $this->kite->getLoginURL();
        $response = file_get_contents($login_url);
        $data = json_decode($response);

        if (isset($data->request_token)) {
            
            try {
                $data = $this->kite->generateSession($data->request_token, "347sf6e3feevon9a1xgyiyw9wcqk5u0p");
                KiteToken::where('user_id', auth()->user()->id)->delete();
                $kite_token = new KiteToken();
                $kite_token->user_id = auth()->user()->id;
                $kite_token->access_token = $data->access_token;
                $kite_token->save();

                $this->info('Authentication successful.');
            } catch (Exception $e) {
                $this->error('Authentication failed: ' . $e->getMessage());
            }
        } else {
            $this->error('Failed to retrieve request token from login response.');
        }
    }
}
