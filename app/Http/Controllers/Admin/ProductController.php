<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Product;
use Carbon\Carbon;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    public function index(){
        return Inertia::render("Admin/Products/Index");
    }
    public function getAllProducts(Request $request){
        $length = $request->length ? $request->length : 10;
        $products = Product::select("id","name","slug","category","catalog_number","cas_number","price","image")->orderBy("id","desc");
        if($length > 0){
            $output = $products->paginate($length);
        }else{
            $products = $products->get();
            $output = [
                "current_page" => 1,
                "data" => $products,
                "from" => 1,
                "to" => count($products),
                "total" => count($products),
                "last_page" => 1
            ];
        }
        return json_encode($output);
    }
    public function create(){
        return Inertia::render("Admin/Products/Edit",["product" => new Product]);
    }
    public function store(ProductStoreRequest $request){
        $slug = "PRD" . Carbon::now()->format("mdY") . rand(000000,999999);
        $product = new Product($request->except("image"));
        $product->slug = $slug;
        $file = $request->file("image");
        $fileName = str_replace([" ","'",'"'],"-",$file->getClientOriginalName());
        $file->move("assets/images/products",$fileName);
        $product->image = "/assets/images/products/" . $fileName;
        $product->save();
        return Redirect::route("admin.products.index")->with("success", "Product created successfully");
    }
    public function show(string $id){
        //
    }
    public function edit(Product $product){
        return Inertia::render("Admin/Products/Edit",compact("product"));
    }
    public function update(ProductUpdateRequest $request,$id){
        $product = Product::findOrfail($id);
        $product->update($request->except("_method","slug","image"));
        $file = $request->file("image");
        if($file){
            $oldImage = $product->image;
            if($oldImage){
                if(file_exists(public_path($oldImage))){
                    unlink(public_path($oldImage));
                }
            }
            $fileName = str_replace([" ","'",'"'],"-",$file->getClientOriginalName());
            $file->move("assets/images/products",$fileName);
            $product->image = "/assets/images/products/" . $fileName;
            $product->save();
        }
        return Redirect::route("admin.products.index")->with("success","Product updated successfully");
    }
    public function destroy(Product $product){
        $product->delete();
        return Inertia::location(route("admin.products.index"));
    }
}