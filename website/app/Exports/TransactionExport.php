<?php

namespace App\Exports;

use App\Models\Wallet;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $users = User::where('role_id', '1')->get();
        $wallets = Wallet::with('user');

        if (!empty($this->filters['after'])) {
            $afterDate = $this->filters['after'];
            $wallets->whereDate('created_at', '>=', $afterDate);
        }

        if (!empty($this->filters['before'])) {
            $beforeDate = $this->filters['before'];
            $wallets->whereDate('created_at', '<=', $beforeDate);
        }

        if (!empty($this->filters['user_id'])) {
            $wallets->where('user_id', $this->filters['user_id']);
        }
        $wallet = $wallets->get();

        
        $array = [];
        $index = 1;
        foreach ($wallet as $t) {   
            array_push($array, [
                "D" => $index,
                "ID" => $t->id,
                "USERNAME" => $t->user->client,
                "NAME" => $t->user->first_name . ' ' . $t->user->last_name,
                "AMOUNT" => $t->amount,
                "TXN TYPE" => $t->type,
                "NOTES" => $t->remarks,
                "CREATED AT" => $t->created_at,
            ]);
            $index++;
        }
    

        return collect($array);
    }
    public function headings(): array
    {
        return [
                "D",
                "ID",
                "USERNAME",
                "NAME",
                "AMOUNT",
                "TXN TYPE",
                "NOTES",
                "CREATED AT",
        ];
    }
}
