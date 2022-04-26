<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Product;
use Hamcrest\Description;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function(){

    $products = Product::orderBy('description', 'asc')->get();
    return view('products.index',compact('products'));

})->name("products.index");

Route::get('products/create', function(){
    return view('products.create');
})->name("products.create");

Route::post('products', function(Request $request){

    $newProduct = new Product;
    $newProduct->description = $request->input('description');
    $newProduct->price = $request->input('price');
    $newProduct->save();

    return redirect()->route('products.index')->with('info', 'Producto Guardado Exitosamente');

})->name("products.save");

Route::delete('products/{id}', function($id){

    $product = Product::findOrFail($id);
    $product->delete();

    return redirect()->route('products.index')->with('info', 'Producto Eliminado Exitosamente');

})->name("products.delete");
