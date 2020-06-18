<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function index()
    {
        return Category::all();
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        Category::create([
            "name" => $request->name
        ]);
        return "success";
    }

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $category = Category::where("id", $id)->first();
        $category->update([
            "name" => $request->name
        ]);
        return "success";
    }

    public function destroy($id)
    {
        $category = Category::where("id", $id)->first();
        $category->delete();
        return "success";
    }
}
