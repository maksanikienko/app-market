<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class MainController extends Controller
{
    public function index() {
        $products = Product::get();
        return view('index', compact('products'));
    }
    public function categories() {
        $categories = Category::get();
        return view('categories',compact('categories'));
    }
    public function category($code) {
        $category = Category::where('code', $code)->first();
        return view('category', compact('category'));
    }
    public function product($category, $product_code = null) {
        $product = Product::where('code', $product_code)->first();
        return view('product',compact('product'));
    }

}
