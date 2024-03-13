<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $counts = array();

        if (Auth::user()->hasRole("Admin") || Auth::user()->hasRole("Super Admin")){
            $tickets = Ticket::all();
            $counts['all'] = count($tickets);
            $counts['open'] = Ticket::where('status','0')->count();
            $counts['inProgress'] = Ticket::where('status','1')->count();
            $counts['closed'] = Ticket::where('status','2')->count();
        }
        else{
            $tickets = Ticket::where('user_id',Auth::user()->id)->get();
            $counts['all'] = count($tickets);
            $counts['open'] = Ticket::where(['status'=>'0','user_id'=>Auth::user()->id])->count();
            $counts['inProgress'] = Ticket::where(['status'=>'1','user_id'=>Auth::user()->id])->count();
            $counts['closed'] = Ticket::where(['status'=>'2','user_id'=>Auth::user()->id])->count();
        }

        if (\request()->ajax()){
            $data = array();
            // parsing the shops data to table format of json
            foreach ($tickets as $ticket){
                $temp = [
                    'responsive_id'=>"",
                    'id'=> $ticket->id,
                    'num'=> sprintf('%06d', $ticket->id),
                    'user'=> $ticket->user->name,
                    'status'=> ($ticket->status == 0?'Open':($ticket->status == 1?'In Progress':'Closed')),
                    'priority'=> ($ticket->priority == 0?'Low':($ticket->priority == 1?'Medium':'High')),
                    'title' => $ticket->title,
                    'description' => $ticket->description,
                ];
                array_push($data,$temp);
            }
            return response()->json(["data"=>$data]);
        }
        return view('template.tickets.list',compact('counts'));
    }

    public function updateStatus($ticket,$status)
    {
        $ticket = Ticket::findOrfail($ticket);
        $ticket->update(['status'=>(string)$status]);
        if ($status == '2'){
            return redirect()->route('ticket.index')->with('success','Ticket Successfully Closed');
        }else{
            return redirect()->back()->with('success','Ticket status Successfully updated');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'priority' => ['required'],
        ]);
        $inputs = $request->except(['_token']);
        $inputs['user_id'] = Auth::user()->id;
        Ticket::create($inputs);
        return redirect()->back()->with('success','Ticket Successfully created.');
    }

    public function show(Ticket $ticket)
    {
        $counts['all'] = Ticket::where('user_id',$ticket->user_id)->count();
        $counts['open'] = Ticket::where(['status'=>'0','user_id'=>$ticket->user_id])->count();
        $counts['inProgress'] = Ticket::where(['status'=>'1','user_id'=>$ticket->user_id])->count();
        $counts['closed'] = Ticket::where(['status'=>'2','user_id'=>$ticket->user_id])->count();
        return view('template.tickets.show',compact('ticket','counts'));
    }

    public function edit(Ticket $ticket)
    {
        //
    }

    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'reply' => ['required'],
            'status' => ['required'],
        ]);
        $inputs = $request->except(['_token','status','_method']);
        $inputs['user_id'] = Auth::user()->id;
        $inputs['ticket_id'] = $ticket->id;
        TicketReply::create($inputs);
        if ($ticket->status == '0' && $ticket->user_id != Auth::user()->id){
            $ticket->update(['status'=>'1']);
        }elseif ($ticket->status == '2'){
            $ticket->update(['status'=>'0']);
        }
        if ($request->status){
            return redirect()->route('ticket.index')->with('success','Replied to ticket Successfully.');
        }else{
            return redirect()->back()->with('success','Replied to ticket successfully.');
        }
    }

    public function destroy(Ticket $ticket)
    {
        //
    }
}
