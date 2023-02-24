<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use SebastianBergmann\Timer\Exception;

class ProductController extends Controller
{
//    public function __construct(){
//        $this->middleware("auth");
//    }
    public function listAll(Request $request){
        $search = $request->get("search");
        $category_id = $request->get("category_id");
        $status = $request->get("status");
        $maxprice = $request->get("maxprice");
        $minprice = $request ->get("minprice");

        $data =  Product::with("Category")
            ->Search($search)
            ->CategoryFilter($category_id)
            ->Status($status)
            ->MaxPrice($maxprice)
            ->MinPrice($minprice)
            ->orderBy("id","desc")
            ->paginate(20);

        $categories = Category::all();
        return view("admin.product.list",[
            "data"=>$data,
            "categories"=>$categories
        ]);
    }
//    public function listAll(){
////          $order = Order::find(1);
////          dd($order->Products);
//
////            $p = Product::find(999);
////            dd($p->Orders);
//
////        $data = Product::where("price",">",500)
////            ->where("qty",20)
////           // ->orWhere("status",true)
////            //->orWhere("name","like","%a%")
//////            ->whereMonth("created_at","=",3)
////            ->orderBy("id","desc")->paginate(20);
////        $data = Product::leftJoin("categories","categories.id","=","products.category_id")
////                ->where("products.qty",20)
////                ->select(["products.*","categories.name as category_name"])
////                ->orderBy("id","desc")->paginate(20);
//        $data =  Product::with("Category")
////            ->where("price",">",500)
////            ->where("qty",20)
//            ->orderBy("id","desc")
//            ->paginate(20);
////        $x = Category::all();
////        $y = $x[0];
////        $y->Products;/// array
//        return view("admin.product.list",[
//            "data"=>$data
//        ]);
//    }
//    public function listAll(){
////        $data = Product::all();// collection Product object
//        // offset = (page - 1) * limit
////        $data = Product::limit(20)->offset(20)->get();
////        $data = Product::limit(20)->orderBy("id","desc")->get();
//        $data = Product::orderBy("id","desc")->paginate(20);
////        $data = Product::onlyTrashed()->orderBy("id","desc")->paginate(20);
////        return view("admin.product.list",compact('data'));
//        return view("admin.product.list",[
//            "data"=>$data
//        ]);
//    }

    public function create(){
        $categories = Category::all();
        return view("admin.product.create",compact("categories"));
    }

    public function store(Request $request){
        $request->validate([
            "name"=>"required|string|min:6",
            "price"=>"required|numeric|min:0",
            "qty"=>"required|numeric|min:0",
            "category_id"=>"required",
            "thumbnail"=>"required|image|mimes:jpg,png,jpeg,gif"
        ],[
            "required"=>"Vui lòng nhập thông tin",
            "string"=> "Phải nhập vào là một chuỗi văn bản",
            "min"=> "Phải nhập :attribute  tối thiểu :min",
            "mimes"=>"Vui lòng nhập đúng định dạng ảnh"
        ]);
        try{
            $thumbnail = null;
            if($request->hasFile("thumbnail")){
                $file = $request->file("thumbnail");
                $fileName = time().$file->getClientOriginalName();
//            $ext = $file->getClientOriginalExtension();
//            $fileName = time().".".$ext;
                $path = public_path("uploads");
                $file->move($path,$fileName);
                $thumbnail = "uploads/".$fileName;
            }

            $product = Product::create([
                "name"=>$request->get("name"),
                "price"=>$request->get("price"),
                "thumbnail"=>$thumbnail,
                "description"=>$request->get("description"),
                "qty"=>$request->get("qty"),
                "category_id"=>$request->get("category_id"),
            ]);
            return redirect()->to("admin/product")->with("success","Them san pham thanh cong");
        }catch (\Exception $e){
            return redirect()->back()->with("error",$e->getMessage());
        }


    }

    public function edit(Product $product){
        // dung id de tim product
//        $product = Product::find($id);
//        if($product==null){
//            return abort(404);
//        }

//        $product = Product::findOrFail($id);

        $categories = Category::all();
        return view("admin.product.edit",compact("categories",'product'));
    }

    public function update(Product $product, Request $request){
        $request->validate([
            "name"=>"required|string|min:6",
            "price"=>"required|numeric|min:0",
            "qty"=>"required|numeric|min:0",
            "category_id"=>"required",
            "thumbnail"=>"nullable|image|mimes:jpg,png,jpeg,gif"
        ],[
            "required"=>"Vui lòng nhập thông tin",
            "string"=> "Phải nhập vào là một chuỗi văn bản",
            "min"=> "Phải nhập :attribute  tối thiểu :min",
            "mimes"=>"Vui lòng nhập đúng định dạng ảnh"
        ]);

        $thumbnail = $product->thumbnail;
        if($request->hasFile("thumbnail")){
            $file = $request->file("thumbnail");
            $fileName = time().$file->getClientOriginalName();
            $path = public_path("uploads");
            $file->move($path,$fileName);
            $thumbnail = "uploads/".$fileName;
        }

        $product->update([
            "name"=>$request->get("name"),
            "price"=>$request->get("price"),
            "thumbnail"=>$thumbnail,
            "description"=>$request->get("description"),
            "qty"=>$request->get("qty"),
            "category_id"=>$request->get("category_id"),
        ]);
        return redirect()->to("admin/product");
    }

    public function delete(Product $product){
        $product->delete();
        return redirect()->to("admin/product");
    }
    public function detail(Product $product){
        $categories = Category::limit(8)->orderBy("id","desc")->get();
        $data=Product::limit(4)->orderBy("price","desc")->get();
        return view("user.product.detail",compact("product","categories","data"));
    }
    public function shopgrid(Request $request){
        $search=$request->get("search");
        $category_id = $request->get("category_id");
        $categories = Category::limit(10)->orderBy("id","desc")->get();
        $cateall =Category::all();
        $productsale=Product::limit(6)->orderBy("id","asc")->get();
        $productlate=Product::limit(6)->orderBy("id","desc")->get();
        $data =  Product::with("Category")
            ->Search($search)
            ->CategoryFilter($category_id)
            ->orderBy("id","desc")
            ->paginate(6);
        return view("user.product.shopgrid",[
            "categories"=>$categories,
            "productsale"=>$productsale,
            "cateall"=>$cateall,
            "data"=>$data,
            "productlate"=>$productlate

        ]);
    }

    public function shopcart(){
        $categories = Category::limit(10)->orderBy("id","desc")->get();

        return view("user.product.shopcart",[
            "categories"=>$categories
        ]);
    }
}
