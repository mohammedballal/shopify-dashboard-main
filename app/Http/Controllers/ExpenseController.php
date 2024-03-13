<?php

namespace App\Http\Controllers;

use App\Models\CostTariff;
use App\Models\Event;
use App\Models\EventLabel;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;

class ExpenseController extends Controller
{

    public function index()
    {
//        if (!(Auth::user()->hasRole("Super Admin")))
//            abort(404);
        if (\request()->ajax()){
            $shops = Expense::with('category')->get();
            $data = array();
            // parsing the shops data to table format of json
            foreach ($shops as $shop){
                $repeat = (@$shop->repeat_date)?date("d M Y",strtotime($shop->repeat_date)):((@$shop->repeat_day)?("Every ".ucwords($shop->repeat_day)):"Every Day");
                $temp = [
                    'responsive_id'=>"",
                    'id'=> $shop->id,
                    'name' => $shop->name,
                    'amount' => $shop->amount,
                    'category' => $shop->category->name ?? NULL,
                    'date' => date("d M Y",strtotime($shop->date)),
                    'repeat_date' => $repeat,
                    'recurring_type' => ucwords(str_ireplace("_"," ",$shop->recurring_type)),
                    'description' => ($shop->description),
                ];
                array_push($data,$temp);
            }
            return response()->json(["data"=>$data]);
        }
        return view('template.expense.list');
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required'],
            'date' => ['required'],
        ]);
        $inputs = $request->except(['_token']);
        $inputs['created_at'] = $inputs['updated_at'] = now();
        $inputs['notify_me'] = $request->notify_me == 'on'?1:0;
        Expense::create($inputs);
        if ($inputs['notify_me']) {
            $event_label = EventLabel::where('name', 'Expenses')->first();
            if (!is_object($event_label)) {
                $event_label = new EventLabel();
                $event_label->name = "Expenses";
                $event_label->color = "#ff0000";
                $event_label->save();
            }
            $event = new Event();
            $event->creator = auth()->user()->id;
            $event->label = $event_label->id;
            $event->title = $inputs['name'];
            $event->start_date = $inputs['notification_date'];
            $event->end_date = $inputs['notification_date'];
            $event->all_day = 1;
            $event->save();
        }

        return redirect()->back()->with('success','Expense Successfully Added.');
    }

    public function show($id)
    {
        $shop = Expense::findorfail($id);
        $repeat = (@$shop->repeat_date)?date("d M Y",strtotime($shop->repeat_date)):"Every ".ucwords($shop->repeat_day);
        return Response::json([
            'id'=> $shop->id,
            'name' => $shop->name,
            'amount' => $shop->amount,
            'repeat_day' => $shop->repeat_day,
            'notification_date' => $shop->notification_date,
            'category_id' => $shop->category->name ?? NULL,
            'date' => date("d M Y",strtotime($shop->date)),
            'repeat_date' => $repeat,
            'recurring_type' => ucwords(str_ireplace("_"," ",$shop->recurring_type)),
            'description' => $shop->description,
        ],200);
    }

    public function edit($id)
    {
        $shop = Expense::findorfail($id);

        return Response::json([
            'id'=> $shop->id,
            'name' => $shop->name,
            'amount' => $shop->amount,
            'notification_date' => $shop->notification_date,
            'category_id' => $shop->category_id ?? NULL,
            'date' => $shop->date,
            'repeat_date' => $shop->repeat_date,
            'repeat_day' => $shop->repeat_day,
            'notify_me' => $shop->notify_me,
            'recurring_type' => $shop->recurring_type,
            'description' => $shop->description,
        ],200);
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'amount' => ['required'],
            'date' => ['required'],
        ]);
        $inputs = $request->except(['_token']);
        $inputs['updated_at'] = now();
        $inputs['notify_me'] = $request->notify_me == 'on'?1:0;
        $inputs['repeat_date'] = $request->repeat_date ?? NULL;
        $inputs['repeat_day'] = $request->repeat_day ?? NULL;
        $inputs['notification_date'] = $request->notification_date ?? NULL;

        Expense::where('id',$id)->update($inputs);
        return redirect()->back()->with('success','Expense '.$request->name.' Successfully updated.');
    }

    public function destroy($id)
    {
//        if (!(Auth::user()->hasRole("Super Admin")))
//            abort(404);
        $cost = Expense::findorfail($id);
        if ($cost->delete()){
            return redirect()->back()->with("success","Expense deleted successfully");
        }
        return redirect()->back()->with("error","Something went wrong");
    }
}
