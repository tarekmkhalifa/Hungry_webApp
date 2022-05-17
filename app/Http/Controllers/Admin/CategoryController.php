<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    // show category index page
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // show create category page
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // save caregory in db
        $formFields = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image'
        ]);

        // save photo in public folder
        $formFields['image'] = $request->file('image')->store('public/categories');

        // save in db
        Category::create($formFields);

        return redirect(route('admin.categories.index'))->with('success', 'Category inserted successfully!');
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
        // show edit category page

        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
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
        // update category
        $category = Category::findOrFail($id);
        $formFields = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|image'
        ]);

        if ($request->hasFile(('image'))) {
            // delete old photo from server
            Storage::delete($category->image);
            // save photo in public folder
            $formFields['image'] = $request->file('image')->store('public/categories');
        }

        $category->update($formFields);

        return redirect(route('admin.categories.index'))->with('warning', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // delete category from db
        $category = Category::findOrFail($id);
        // delete image from server
        Storage::delete($category->image);
        $category->menus()->detach();
        $category->delete();

        return redirect(route('admin.categories.index'))->with('danger', 'Category deleted successfully');
    }
}
