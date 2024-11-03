<?php

namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
// use Spatie\LaravelPdf\Facades\Pdf;
use Illuminate\Http\Request;
use App\Models\Segment;
use App\Models\Wallet;
use App\Models\User;
use App\Models\Trade;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LedgerExport;
class LedgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)
                    ->where(function ($query) {
                        $query->where('action', 'trade')
                            ->orWhere('action', 'brokerage')
                            ->orWhere('action', 'profit_loss_share');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();

        $user = User::find(auth()->user()->id);
        $balance = $user->balance;
        $initial_balance = $user->initial_balance;
        return view('pages.Accounts.ledger', compact('user','wallet','balance','initial_balance'))->with('isWelcomePage', false)->with('isvideo', false);
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
    public function export()
    {
        return Excel::download(new LedgerExport , 'ledger.xlsx');
    }
    public function pdf()
    {
        dd("in pdf");
        $wallet = Wallet::where('user_id', auth()->user()->id)
            ->where('action', 'trade')
            ->get();
        
        $array = [];
        $index = 1;
        $credit = $wallet->where('type', 'credit')->sum('amount');
        $debit = $wallet->where('type', 'debit')->sum('amount');
        $balance = auth()->user()->balance;

        foreach ($wallet as $u) {
            $array[] = [
                "SR NO" => $index,
                "REMARKS" => $u->remarks,
                "DATE" => $u->created_at->format('Y-m-d'),
                "DEBIT" => $u->type == 'debit' ? number_format(-$u->amount, 2) : "0.00",
                "CREDIT" => $u->type == 'credit' ? number_format($u->amount, 2) : "0.00",
                "BALANCE" => ''
            ];
            $index++;
        }
        $array[] = [
            "SR NO" => '',
            "REMARKS" => 'Balance',
            "DATE" => '',
            "DEBIT" => '',
            "CREDIT" => '',
            "BALANCE" => number_format($balance, 2),
        ];

        return Pdf::view('pdf.wallet', ['wallet' => $array])
            ->format('a4')
            ->download('ledger.pdf');
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
