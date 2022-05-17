<?php

namespace App\Http\Controllers\Guest;

use Carbon\Carbon;
use App\Models\Table;
use App\Rules\DateBetween;
use App\Rules\TimeBetween;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class ReservationController extends Controller
{
    public function stepOne(Request $request)
    {
        // save data from step one reservation
        $reservation = $request->session()->get('reservation');
        $min_date = Carbon::today();
        $max_date = Carbon::now()->addWeek();
        return view('reservations.step-one', compact('reservation', 'min_date', 'max_date'));
    }

    public function stepOneStore(Request $request)
    {
        $formFields = $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|digits:11',
            'res_date' => ['required', 'date', new DateBetween, new TimeBetween],
            'number_of_guests' => 'required|numeric|min:1'
        ]);

        if(empty($request->session()->get('reservation'))){
            $reservation = new Reservation();
            $reservation->fill($formFields);
            $request->session()->put('reservation', $reservation);
        }else {
            $reservation = $request->session()->get('reservation');
            $reservation->fill($formFields);
            $request->session()->put('reservation', $reservation);
        }

        return redirect(route('reservations.step.two'));


    }

    public function stepTwo(Request $request)
    {
        // get data of step one reservation from session
        $reservation = $request->session()->get('reservation');

        $res_table_ids = Reservation::orderBy('res_date')->get()->filter(function($value) use($reservation){
            return  Carbon::parse($value->res_date)->format('Y-m-d') == Carbon::parse($reservation->res_date)->format('Y-m-d');
        });

        $tables = Table::where('status','available')
                        ->where('guest_number', '>=', $reservation->number_of_guests)
                        ->get();

        return view('reservations.step-two', compact('reservation','tables'));
    }

    public function stepTwoStore(Request $request)
    {
        $formFields = $request->validate([
            'table_id' => 'required'
        ]);

        $reservation = $request->session()->get('reservation');
        $reservation->fill($formFields);
        $reservation->save();

        $request->session()->forget('reservation');

        return redirect(route('thank.you'));
    }
}
