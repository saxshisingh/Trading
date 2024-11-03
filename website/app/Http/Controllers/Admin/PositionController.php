<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Position;
use App\Models\Segment;
use App\Models\ExpiryDate;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $segment = Segment::all();
        $user = User::where("role_id",'1')->get();
        $query = Position::with('user','script','segment','expiry');
        if ($request->segment_id!=null) {
            $query->where('segment_id', $request->segment_id);
        }
    
        if ($request->script_id!=null) {
            $query->where('script_id', $request->script_id);
        }
    
        if ($request->position_type!=null) {
            $query->where('position_type', $request->position_type);
        }
    
        if ($request->status !== null) {
            $query->where('status', $request->status);
        }
        // $pos= $position->get();
        // dd($pos);
        $position = $query->paginate($request->get('page_size', 10));
       
        return view('pages.Position.position',compact('user','request','segment','position'))->with('isWelcomePage', false)->with('isvideo', false);
    }

    /**
     * Show the form for creating a new resource.
     */


     public function toggleActive(Request $request, $id)
     {
         try {
             $position = Position::find($id);
             if($position->status=='open'){
                 $position->status='closed';
             }else{
                 $position->status='open';
             }
             if($position->save()){
                 return \Redirect::back()->withInput(['success' => 'Position '.($position->status=='open'?'Opened':'Closed')]);
             }else{
                 return \Redirect::back()->withInput(['error' => 'Something Went Wrong']);
             }
         } catch (\Throwable $th) {
             dd($th);
             return \Redirect::back()->withInput(['error' => 'Something Went Wrong']);
         }
     }

    public function create()
    {
        $user = User::where("role_id",'1')->get();
        $position = Position::get();
        $segment = Segment::get();
        return view('pages.Position.create', compact('user','position','segment'))->with('isWelcomePage', false)->with('isvideo', false);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'segment_id' => 'required',
            'script_id' => 'required',
            'quantity' => 'required|numeric',
            'entry_price' => 'required|numeric',
            'current_price' => 'nullable|numeric',
            'expiry_date_id' => 'required',
            'position_type' => 'required|string',
            'profit_loss' => 'nullable|numeric',
            'stop_loss' => 'nullable|numeric',
            'target_price' => 'nullable|numeric',
            'broker' => 'nullable|string',
            'transaction_fee' => 'nullable|numeric',
        ]);

        try {
            $data = $request->except('_token');
            $position = Position::create($data);
            return redirect()->back()->with('success', 'Position created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'Failed to create position: ' . $e->getMessage()]);
        }
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
        $user = User::where('role_id','1')->get();
        $segment = Segment::all();
        $position = Position::find($id);
        return view('pages.Position.create', compact('position','user','segment'))->with('isWelcomePage', false)->with('isvideo', false);
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
