<?php

namespace App\Exports;

use App\Models\Wallet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LedgerExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $wallet = Wallet::where('user_id', auth()->user()->id)->where('action','trade')->get();
        
        $array = [];
        $index = 1;
        $credit = $wallet->where('type','credit')->sum('amount');
        $debit = $wallet->where('type','debit')->sum('amount');
        $balance = auth()->user()->balance;
        foreach ($wallet as $u) {   
            array_push($array, [
                "SR NO" => $index,
                "REMARKS" => $u->remarks,
                "DATE" => $u->created_at,
                "DEBIT" => $u->type == 'debit' ? -$u->amount : "0",
                "CREDIT" => $u->type == 'credit' ? $u->amount : "0",
                "BALANCE" => '',
            ]);
            $index++;
        }
        array_push($array, [
            "SR NO" => '',
            "REMARKS" => 'Balance',
            "DATE" => '',
            "DEBIT" => '',
            "CREDIT" => '',
            "BALANCE" => number_format($balance, 2), 
        ]);
    

        return collect($array);
    }
    public function headings(): array
    {
        return [
            "SR NO",
            "REMARKS",
            "DATE",
            "DEBIT",
            "CREDIT",
            "BALANCE",
        ];
    }
}
