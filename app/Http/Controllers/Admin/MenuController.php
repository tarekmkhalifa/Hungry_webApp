<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    // show menu index page
    public function index()
    {
        $menus = Menu::all();
        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::all();
        return view('admin.menus.create',compact('categories'));
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
            'image' => ['required', 'image'],
            'price' => 'required',
            'description' => 'required'
        ]);

        $formFields['image'] = $request->file('image')->store('public/menus');

        $menu = Menu::create($formFields);
        if($request->has('categories')){
            $menu->Categories()->attach($request->categories);
        }
        return redirect(route('admin.menus.index'))->with('success','Menu created successfully!');
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
        $menu = Menu::findOrFail($id);
        $categories = Category::all();
        return view('admin.menus.edit',compact('menu','categories'));
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
        $menu = Menu::findOrFail($id);
        $formFields = $request->validate([
            'name' => 'required',
            'image' => ['nullable', 'image'],
            'price' => 'required',
            'description' => 'required'
        ]);

        if($request->hasFile('image')){

            // delete old photo from server
            Storage::delete($menu->image);

            $formFields['image'] = $request->file('image')->store('public/menus');

        }

        $menu->update($formFields);
        if($request->has('categories')){
            $menu->Categories()->detach();
            $menu->Categories()->attach($request->categories);
        }

        return redirect(route('admin.menus.index'))->with('warning','Menu updated scuccessfully!');
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
        $menu = Menu::findOrFail($id);
        Storage::delete($menu->image);
        $menu->categories()->detach();
        $menu->delete();
        return redirect(route('admin.menus.index'))->with('danger', 'Menu deleted successfully');

    }
}
