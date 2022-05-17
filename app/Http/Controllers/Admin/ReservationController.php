<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Table;
use Illuminate\Support;
use App\Rules\DateBetween;
use App\Rules\TimeBetween;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // show resrvation index page

    public function index()
    {
        $reservations = Reservation::all();
        $tables = Table::where('status', 'available')->get();
        return view('admin.reservations.index', compact('reservations', 'tables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $tables = Table::all()->where('status', 'available');
        return view('admin.reservations.create', compact('tables'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $formFields = $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|digits:11',
            'res_date' => ['required', 'date', new DateBetween, new TimeBetween],
            'number_of_guests' => 'required|numeric|min:1',
            'table_id' => 'required|numeric|min:1'
        ]);

        // check table available or not based on number of guests
        $table = Table::findOrFail($request->table_id);
        if ($request->number_of_guests > $table->guest_number) {
            return back()->with('warning', 'Sorry, this table is not available please choose another one');
        }

        // check table available or not based on time and date
        // check table available or not based on time and date
        $request_date = Carbon::parse($request->res_date);
        foreach ($table->reservations as $res){
            if(Carbon::parse($res->res_date)->format('Y-m-d') == $request_date->format('Y-m-d')){
                return back()->with('warning', 'This table is reserved for this date!');
            }
        }

        Reservation::create($formFields);
        return redirect(route('admin.reservations.index'))
            ->with('success', 'Congratulations your reservation created successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $tables = Table::where('status', 'available')->get();
        return view('admin.reservations.edit', compact('reservation', 'tables'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $formFields = $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email',
            'phone' => 'required|digits:11',
            'res_date' => ['required', 'date', new DateBetween, new TimeBetween],
            'number_of_guests' => 'required|numeric|min:1',
            'table_id' => 'required|numeric|min:1'
        ]);

        // check table available or not based on number of guests
        $table = Table::findOrFail($request->table_id);
        if ($request->number_of_guests > $table->guest_number) {
            return back()->with('warning', 'Sorry, this table is not available please choose another one');
        }

        // check table available or not based on time and date
        $request_date = Carbon::parse($request->res_date);
        $reservations = $table->reservations()->where('id', '!=', $reservation->id)->get();
        foreach ($reservations as $res){
            if(Carbon::parse($res->res_date)->format('Y-m-d') == $request_date->format('Y-m-d')){
                return back()->with('warning', 'This table is reserved for this date!');
            }
        }

        $reservation->update($formFields);
        return redirect(route('admin.reservations.index'))->with('warning', 'Reservation updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect(route('admin.reservations.index'))->with('danger', 'Reservation deleted successfully!');

    }

}
