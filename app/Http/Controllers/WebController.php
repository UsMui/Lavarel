<?php

namespace App\Http\Controllers;

use App\Mail\MailOrder;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WebController extends Controller
{
    public function home(){
        $products=Product::limit(8)->orderBy("id","desc")->get();
        $categories = Category::limit(8)->orderBy("id","desc")->get();
        return view("home",compact("products","categories"));
    }

    public function aboutUs(){
        return view("about_us");
    }
    public function detail(Product $product){
        $related_products=Product::CategoryFilter($product->category_id)
            ->where("id","!=",$product->id)
            ->get()->random(4);
        $best_seller_ids=DB::table("order_products")->groupBy("product_id")
            ->orderBy("sum_qty","desc")
            ->limit(4)
            ->select(DB::raw("product_id , sum(qty) as sum_qty"))
            ->get()
        ->pluck("product_id")
        ->toArray();
//        dd($best_seller_ids);
//        $best_sellers = Product::whereIn("id",$best_seller_ids)->get();
        $best_sellers=Product::find($best_seller_ids);
//        dd($best_sellers);

        $categories = Category::limit(8)->orderBy("id","desc")->get();
        $data=Product::limit(4)->orderBy("price","desc")->get();
        return view("user.product.product_detail",[
            "categories"=>$categories,
            "data"=>$data,
            "product"=>$product,
            "best_sellers"=>$best_sellers
        ]);
    }
    public function addToCart(Product $product,Request $request){
        $request->validate([
            "qty"=>"required|numeric|min:1"
        ]);
        $cart=session()->has("cart")&&is_array(session("cart"))?session("cart"):[];
        $flag=true;
        foreach ($cart as $item){
            if($item->id==$product->id){
                $item->buy_qty+=$request->get("qty");

                $flag=false;
                break;
            }
        }
        if($flag){
            $product->buy_qty=$request->get("qty");

            $cart[]=$product;
        }

        session(["cart"=>$cart]);
        return redirect()->back();
    }
    public function shopcart(){

        $cart=session()->has("cart")&&is_array(session("cart"))?session("cart"):[];
        $grand_total=0;
        $can_checkout=true;
        foreach ($cart as $item){
            $grand_total+=$item->price*$item->buy_qty;
            if($can_checkout&&$item->qty==0){
                $can_checkout=false;
            }
        }
        $categories = Category::limit(10)->orderBy("id","desc")->get();
        return view("user.product.shopcart",[
            "categories"=>$categories,
            "grand_total"=>$grand_total,
            "cart"=>$cart,
            "can_checkout"=>$can_checkout
        ]);
    }
    public function checkout(){
        $cart=session()->has("cart")&&is_array(session("cart"))?session("cart"):[];
        if(count($cart)==0){
            return redirect()->to("user/product/shopcart");
        }
        $grand_total=0;
        foreach ($cart as $item){
            $grand_total+=$item->price*$item->buy_qty;
        }
        $categories = Category::limit(10)->orderBy("id","desc")->get();
        return view("user.product.checkout",[
            "categories"=>$categories,
            "grand_total"=>$grand_total,
            "cart"=>$cart
        ]);
    }
    public function remove(Product $product){
        $cart = session()->has("cart") && is_array(session("cart"))?session("cart"):[];
        foreach ($cart as $key=>$item){
            if($item->id == $product->id){
                unset($cart[$key]);
                break;
            }
        }
        session(["cart"=>$cart]);
        return redirect()->back();
    }
    public function placeOrder(Request $request){
        $request->validate([
            "firstname"=>"required",
            "lastname"=>"required",
            "country"=>"required",
            "shipping_address"=>"required",
            "city"=>"required",
            "zip"=>"required",
            "customer_tel"=>"required",
            "email"=>"required",
        ]);
        $cart = session()->has("cart") && is_array(session("cart"))?session("cart"):[];
        if(count($cart)==0) return abort(404);
        $grand_total=0;
        $can_checkout=true;
        foreach ($cart as $item){
            $grand_total+=$item->price*$item->buy_qty;
            if($can_checkout&&$item->qty==0){
                $can_checkout=false;
            }
        }
        if(!$can_checkout) return abort(404);

            $order=Order::create([
                "order_date"=>now(),
                "grand_total"=>$grand_total,
                "shipping_address"=>$request->get("shipping_address"),
                "customer_tel"=>$request->get("customer_tel"),
                "status"=>"0",
                "fullname"=>$request->get("firstname")." ".$request->get("lastname"),
                "country"=>$request->get("country"),
                "city"=>$request->get("city"),
                "zip"=>$request->get("zip"),
                "email"=>$request->get("email")

            ])->createItems();
        //  $order->createItems();

            return redirect()->to("/");

    }
    public function sendNotification(){
        // send notification


        $data['message'] = 'Có một đơn hàng mới';
        $data["order_id"] = 55;
        notification("my_channel","my_event",$data);
    }
}
