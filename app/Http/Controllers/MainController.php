<?php

namespace App\Http\Controllers;

use App\Category;
use App\Offer;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $popularProducts = DB::table('products')->
        join('offers', 'products.id', '=', 'offers.product_id')->
        select('products.id', 'products.title','products.image','products.description',
            'products.first_invoice','products.url','products.price','products.amount',
            DB::raw('SUM(offers.sales) as total_sales'))->
        groupBy('products.id','products.title','products.image','products.description',
            'products.first_invoice','products.url','products.price','products.amount')->
        orderBy('total_sales', 'desc')->
        limit(20)->get()->toArray();

        $category = new Category();
        $rootCategories = $category->rootCategories();

        return view('products.index', ['products'=>$popularProducts, 'rootCategories' => $rootCategories]);
    }

    public function products_in_category($category_id) {
        $categoryTitle = Category::find($category_id)->title;
        $products = Category::find($category_id)->products;
        return view('products.by_categories', ['products'=>$products, 'categoryTitle'=>$categoryTitle]);
    }

    public function search(Request $request) {
        $searchText = trim($request->search_text);
        $products = null;
        if($searchText) {
            $products = Product::where('title', 'like', "%{$searchText}%")->
            orWhere('description', 'like', "%{$searchText}%")->get();
        }
        return view('products.find_products', ['products'=>$products]);
    }

    public function getProducts()
    {
        $ch = curl_init();
        $url = 'https://markethot.ru/export/bestsp';
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result);
        foreach($response->products as $item) {
            $product = Product::firstOrNew(['id' => $item->id]);
            if($product->exists) {
                $wasChanged = false;
                if($product->title != $item->title) {
                    $product->title = $item->title;
                    $wasChanged = true;
                }
                if($product->image != $item->image) {
                    $product->image = $item->image;
                    $wasChanged = true;
                }
                if($product->description != $item->description) {
                    $product->description = $item->description;
                    $wasChanged = true;
                }
                if($product->first_invoice != $item->first_invoice) {
                    $product->first_invoice = $item->first_invoice;
                    $wasChanged = true;
                }
                if($product->url != $item->url) {
                    $product->url = $item->url;
                    $wasChanged = true;
                }
                if($product->price != $item->price) {
                    $product->price = $item->price;
                    $wasChanged = true;
                }
                if($product->amount != $item->amount) {
                    $product->amount = $item->amount;
                    $wasChanged = true;
                }
                if($wasChanged) {
                    $product->save();
                }
            } else {
                $product->id = $item->id;
                $product->title = $item->title;
                $product->image = $item->image;
                $product->description = $item->description;
                $product->first_invoice = $item->first_invoice;
                $product->url = $item->url;
                $product->price = $item->price;
                $product->amount = $item->amount;
                $product->save();
            }
            foreach($item->offers as $offer) {
                $offerToSave = Offer::firstOrNew(['id' => $offer->id]);
                if($offerToSave->exists) {
                    $wasChanged = false;
                    if($offerToSave->price != $offer->price) {
                        $offerToSave->price = $offer->price;
                        $wasChanged = true;
                    }
                    if($offerToSave->amount != $offer->amount) {
                        $offerToSave->amount = $offer->amount;
                        $wasChanged = true;
                    }
                    if($offerToSave->sales != $offer->sales) {
                        $offerToSave->sales = $offer->sales;
                        $wasChanged = true;
                    }
                    if($offerToSave->article != $offer->article) {
                        $offerToSave->article = $offer->article;
                        $wasChanged = true;
                    }
                    if($offerToSave->product_id != $product->id) {
                        $offerToSave->product_id = $product->id;
                        $wasChanged = true;
                    }
                    if($wasChanged) {
                        $offerToSave->save();
                    }
                } else {
                    $offerToSave->id = $offer->id;
                    $offerToSave->price = $offer->price;
                    $offerToSave->amount = $offer->amount;
                    $offerToSave->sales = $offer->sales;
                    $offerToSave->article = $offer->article;
                    $offerToSave->product_id = $product->id;
                    $offerToSave->save();
                }
            }
            $categoriesToDetach = $product->categories->pluck('id')->toArray();
            $product->categories()->detach($categoriesToDetach);
            $categories = [];
            foreach($item->categories as $category) {
                $categoryToSave = Category::firstOrNew(['id' => $category->id]);
                if($categoryToSave->exists) {
                    $wasChanged = false;
                    if($categoryToSave->title != $category->title) {
                        $categoryToSave->title = $category->title;
                        $wasChanged = true;
                    }
                    if($categoryToSave->alias != $category->alias) {
                        $categoryToSave->alias = $category->alias;
                        $wasChanged = true;
                    }
                    if($categoryToSave->parent_id != $category->parent) {
                        $categoryToSave->parent_id = $category->parent;
                        $wasChanged = true;
                    }
                    if($wasChanged) {
                        $categoryToSave->save();
                    }
                } else {
                    $categoryToSave->id = $category->id;
                    $categoryToSave->title = $category->title;
                    $categoryToSave->alias = $category->alias;
                    $categoryToSave->parent_id = $category->parent;
                    $categoryToSave->save();
                }
                $categories[] = $categoryToSave->id;
            }
            $product->categories()->attach($categories);
        }
    }
}
