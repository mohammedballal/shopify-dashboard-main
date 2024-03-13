<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventLabel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{
    public function index()
    {
        $labels = EventLabel::all();
        $users = User::all()->except(Auth::id());
        return view('template.events.list',compact('labels','users'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'label' => ['required'],
            'start_date' => ['required'],
            'end_date' => ['required'],
        ]);
        $data = $request->except('_token');
        $data['creator'] =  Auth::id();
        $data['all_day'] =  @$request->all_day?1:0;
        $event = Event::create($data);
        if ($event){
            $event->guests()->sync($request->event_guests);
            return redirect()->back()->with('success','Event successfully created.');
        }
        else
            return redirect()->back()->with('error','Something went wrong!Please try again later.');
    }


    public function list()
    {
        $events = Event::with('event_label')->get();
        $data = array();
        foreach ($events as $event){
            $data[] = [
                "id"=>$event->id,
                "url"=>$event->url,
                "title"=>$event->title,
                "allDay"=>$event->all_day,
                "start"=>date("Y-m-d",strtotime($event->start_date)),
                "end"=>date("Y-m-d",strtotime($event->end_date)),
                "extendedProps"=>['calendar'=>$event->event_label->name],
            ];

        }
        return Response::json(['events'=>$data],200);
    }


    public function edit(Event $event)
    {
        //
    }


    public function update(Request $request, Event $event)
    {
        //
    }


    public function destroy(Event $event)
    {
        //
    }

    public function labelStore(Request $request)
    {

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'color' => ['required']
        ]);
        if (EventLabel::create($request->except('_token')))
            return redirect()->back()->with('success','Label successfully created.');
        else
            return redirect()->back()->with('error','Something went wrong!Please try again later.');
    }

    public function labelDelete($label)
    {
        if (EventLabel::findorFail($label)->delete())
            return redirect()->back()->with('success','Label successfully deleted.');
        else
            return redirect()->back()->with('error','Something went wrong!Please try again later.');
    }
}
