<?php 
namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Carbon;

class FullCalenderController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $data = Event::whereDate('start', '>=', $request->start)
                         ->whereDate('end', '<=', $request->end)
                         ->get(['id', 'title', 'start', 'end']);
            return response()->json($data);
        }
        return view('calender.fullcalender');
    }

    public function action(Request $request)
    {
        if($request->ajax())
        {
            if($request->type == 'add')
            {
                $event = Event::create([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end
                ]);

                return response()->json($event);
            }

            if($request->type == 'update')
            {
                $event = Event::find($request->id)->update([
                    'title' => $request->title,
                    'start' => $request->start,
                    'end' => $request->end
                ]);

                return response()->json($event);
            }

            if($request->type == 'delete')
            {
                $event = Event::find($request->id)->delete();

                return response()->json($event);
            }
        }
    }
    public function eventForm(){
        $employee = Employee::all();
        return view('setting.addevent', compact('employee'));
    }
    public function AddEvent(Request $request){
        $request->validate([
            'name' => 'required',
            'event' => 'required',
            'start' => 'required',
        ]);
        $title = $request->name . "'s " . $request->event; 

        $event = new Event();
        $event->title = $title;

        $startDate = Carbon::parse($request->start);
        $endDate = $startDate->addDays(1);
        $event->start = $startDate->format('Y-m-d');
        $event->end = $endDate->format('Y-m-d');
        $event->save();
        return redirect('setting.addevent');
    }

}
