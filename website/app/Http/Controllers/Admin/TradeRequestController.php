<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Segment;
use App\Models\Script;
use App\Models\Trading;
use App\Models\Order;
use Illuminate\Http\Request;

class TradeRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $segment = Segment::all();
        $script = Script::all();
        $order = Order::get();
        $trade = Trading::with('script','segment','order','user')->get();
        return view('pages.Request.index', compact("trade","segment",'request','order'))->with('isWelcomePage', false)->with('isvideo', true);
    }

    /**
     * Show the form for creating a new resource.
     */

     public function toggleActive(Request $request, $id)
     {
         try {
             $trade = Trading::with('script','segment','order','user')->find($id);
          
             if ($request->status == "0") {
                $trade->remark = $request->input('remark');
                $trade->status = '0'; 
                $trade->token = $request->_token;
            } else {
                $trade->remark = "Approved Successfully";
                $trade->status = "1"; 
            }
            
             if($trade->save()){
                // dd($trade);
                 return \Redirect::back()->withInput(['success' => 'Trade Request '.($trade->status==0?'Rejected':'Approved')]);
             }else{
                 return \Redirect::back()->withInput(['error' => 'Something Went Wrong']);
             }
         } catch (\Throwable $th) {
           
             return \Redirect::back()->withInput(['error' => 'Something Went Wrong']);
         }
     }
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
