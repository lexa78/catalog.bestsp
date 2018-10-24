<?php

namespace App\Console\Commands;

use App\Category;
use App\Offer;
use App\Product;
use Illuminate\Console\Command;

class GetProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get products from markethot.ru';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
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
