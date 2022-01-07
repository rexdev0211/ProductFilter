<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_key = $request->search_key ?: '';
        $category = $request->category ?: '';
        $manufacturer = $request->manufacturer ?: '';
        $page = $request->page ?: 1;
        $limit = $request->limit ?: 10;
        $date_added = $request->date_added ?: '';
        $date_modified = $request->date_added ?: '';

        $products = Product::where(function($query) use ($search_key) {
                        $query->where('id', $search_key)
                            ->orWhere('name', 'like', '%' . $search_key . '%')
                            ->orWhere('model', 'like', '%' . $search_key . '%');
                    })
                    ->where(function($query) use ($category, $manufacturer, $date_added, $date_modified) {
                        if ($category) $query->where('category', $category);
                        if ($manufacturer) $query->where('manufacturer', $manufacturer);
                        if ($date_added) $query->whereBetween('date_added', [$date_added . '00:00:00', $date_added . '23:59:59']);
                        if ($date_modified) $query->whereBetween('date_modified', [$date_modified . '00:00:00', $date_modified . '23:59:59']);
                    })
                    ->offset(($page - 1) * $limit)
                    ->limit($limit)
                    ->get();

        return $products;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $attributes = $request->validated();
        try {
            return Product::create($attributes);
        } catch (\Throwable $t) {
            report($t);
            abort(500, $t->getMessage());
        }
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
    }
}
