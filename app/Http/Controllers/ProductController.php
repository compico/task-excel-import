<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Repository\ExtraFieldRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function list(int $page = 0)
    {
        $data = [];

        $products = Product::query()->limit(10)->offset($page * 10)->get();
        if ($products->isEmpty() && $page !== 0) {
            return redirect()->route('product.list', ['page' => 0]);
        }
        $data['products'] = $products;

        if ($page > 0) {
            $data['previous_page'] = url('product/list', ['page' => $page - 1]);
        }

        $c = Product::query()->limit(10)->offset(($page + 1) * 10)->get('id')->count();
        if ($c > 0) {
            $data['next_page'] = url('product/list', ['page' => $page + 1]);
        }

        return view('product.list', $data);
    }

    public function index(int $id = 0)
    {
        if ($id == 0) {
            return redirect()->route('product.list');
        }

        $data = [];

        $product = Product::query()->where('id', $id)->with('photos')->first();
        if ($product === null) {
            return redirect()->route('product.list');
        }

        $data['product'] = $product;

        $data['extra_fields'] = ExtraFieldRepository::GetExtraFieldsByProductId($product->id);

        return view('product.index', $data);
    }
}
