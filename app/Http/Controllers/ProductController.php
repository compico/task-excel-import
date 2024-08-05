<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function list(int $page = 1)
    {
        $products = Product::query()->limit(10)->offset($page * 10)->get();
        if ($products->isEmpty()) {
            return redirect()->route('product.list', ['page' => 1]);
        }

        return view('product.list', ['products' => $products]);
    }
}
