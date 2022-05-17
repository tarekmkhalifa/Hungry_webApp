<?php

namespace App\Http\Controllers\Admin;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    // show table index page
    public function index()
    {
        $tables = Table::all();
        return view('admin.tables.index', compact('tables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.tables.create');
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
            'name' => 'required',
            'guest_number' => 'required|numeric|min:1',
            'status' => ['required',Rule::in(['pending','available','unavailable'])],
            'location' => ['required',Rule::in(['front','inside','outside'])]
        ]);

        Table::create($formFields);

        return redirect(route('admin.tables.index'))->with('success','Table created successfully');

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
        //
        $table = Table::findOrFail($id);

        return view('admin.tables.edit',compact('table'));
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
        //
        $table = Table::findOrFail($id);
        $formFields = $request->validate([
            'name' => 'required',
            'guest_number' => 'required|numeric|min:1',
            'status' => ['required',Rule::in(['pending','available','unavailable'])],
            'location' => ['required',Rule::in(['front','inside','outside'])]
        ]);

        $table->update($formFields);
        return redirect(route('admin.tables.index'))->with('warning','Table updated successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $table = Table::findOrFail($id);
        $table->delete();
        $table->reservations()->delete();
        return redirect(route('admin.tables.index'))->with('danger','Table deleted successfully');

    }
}
