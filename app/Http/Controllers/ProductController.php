<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        $products = Product::all();
        return view('products.products', compact('sections', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = Product::create([
            'product_name' => $request->product_name,
            'section_id' => $request->section_id,
            'description' => $request->description,
        ]);
        session()->flash('Add', 'تم إضافة المنتج بنجاح');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $section_id = Section::where('section_name', $request->section_name)->first()->id;
        $product = Product::findOrFail($request->pro_id);

        $product->update([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'section_id' => $section_id,
        ]);

        session()->flash('Edit', 'تم تعديل المنتج بنجاح');
        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $product = Product::findOrFail($request->pro_id);
        $product->delete();
        session()->flash('Delete', 'تم حذف المنتج بنجاح');
        return redirect('/products');
    }
}
