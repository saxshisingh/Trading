<?php

namespace App\Exports;

use App\Models\Trading;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TradeHistoryExport implements FromCollection, WithHeadings
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
        $trades = Trading::with('user','segment','script','expiry')->where('portfolio_status','close')->whereNotNull('buy_at')->whereNotNull('sell_at');
        if (!empty($this->filters['after'])) {
            $startDate = \Carbon\Carbon::createFromFormat('Y-m-d', $this->filters['after']);
            $trades = $trades->where('created_at', '>=', $startDate);            
        }

        if (!empty($this->filters['before'])) {
            $endDate = \Carbon\Carbon::createFromFormat('Y-m-d', $this->filters['before']);
            $trades = $trades->whereDate('created_at', '<', $endDate);
        }
        
        if (!empty($this->filters['user_id'])) {
            $trades->where('user_id', $this->filters['user_id']);
        }
        $trades = $trades->get();
        $array = [];
        $index = 1;
        foreach ($trades as $t) {   
            $expiryDate = \Carbon\Carbon::parse($t->expiry->expiry_date);
            $formattedExpiryDate = strtoupper($expiryDate->format('dMY'));
            array_push($array, [
                "D" => $index,
                "ID" => $t->id,
                "USERNAME" => $t->user->client,
                "NAME" => $t->user->first_name . ' ' . $t->user->last_name,
                "TIME" => $t->created_at,
                "MARKET" => $t->segment ? $t->segment->name . $t->segment->instrument_type : '',
                "SCRIPT" => $t->script ? $t->script->name . ' ' . $formattedExpiryDate : '',
                "B/S" => $t->action == 'BUY' ? 'SELL' : 'BUY',
                "LOT" => $t->lot,
                "QTY" => $t->qty,
                "ORDER PRICE" => $t->action == 'BUY' ? $t->sell_rate : $t->buy_rate,
                "STATUS" => ($t->status == '1' || $t->status == '0') ? 'EXECUTED' : 'PENDING',
                "O. TIME" => $t->action == 'BUY' ? $t->sell_at : $t->buy_at,
                "MODIFY" => 'NA', 
                "CANCEL" => 'NA', 
            ]);
            $index++;
            array_push($array, [
                "D" => $index,
                "ID" => $t->id,
                "USERNAME" => $t->user->client,
                "NAME" => $t->user->first_name . ' ' . $t->user->last_name,
                "TIME" => $t->created_at,
                "MARKET" => $t->segment ? $t->segment->name . $t->segment->instrument_type : '',
                "SCRIPT" => $t->script ? $t->script->name . ' ' . $formattedExpiryDate : '',
                "B/S" => $t->action == 'BUY' ? 'BUY' : 'SELL',
                "LOT" => $t->lot,
                "QTY" => $t->qty,
                "ORDER PRICE" => $t->action == 'BUY' ? $t->buy_rate : $t->sell_rate,
                "STATUS" => ($t->status == '1' || $t->status == '0') ? 'EXECUTED' : 'PENDING',
                "O. TIME" => $t->action == 'BUY' ? $t->buy_at : $t->sell_at,
                "MODIFY" => 'NA', 
                "CANCEL" => 'NA', 
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
                "TIME",
                "MARKET",
                "SCRIPT",
                "B/S",
                "LOT",
                "QTY",
                "ORDER PRICE",
                "STATUS",
                "O. TIME",
                "MODIFY", 
                "CANCEL",
        ];
    }
}
