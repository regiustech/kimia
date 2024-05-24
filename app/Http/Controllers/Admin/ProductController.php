<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\VariantDetail;
use App\Models\ProductVariant;
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
        $search = $request->search ? $request->search : "";
        $products = Product::orderBy("id","desc");
        if(!empty($search)){
            $products = $products->where(function($query) use($search){
                $query->where('name','LIKE',"%{$search}%")
                      ->orWhere('category','LIKE',"%{$search}%")
                      ->orWhere('catalog_number','LIKE',"%{$search}%")
                      ->orWhere('cas_number','LIKE',"%{$search}%");
            });
        }
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
        $variantProductDetails = VariantDetail::where("variant_id",1)->orderBy("order_no","ASC")->get(["id","variant_id","name"]);
        return Inertia::render("Admin/Products/Edit",["product" => new Product,"variantProductDetails" => $variantProductDetails]);
    }
    public function store(ProductStoreRequest $request){
        $request->price = $request->price ? $request->price : null;
        $product = new Product($request->except("image","productVariants","specifications"));
        $product->slug = $this->generateSlug($request->name);
        $product->specifications = json_encode($request->specifications);
        $file = $request->file("image");
        $fileName = $this->randomString(16) . '.' . $file->getClientOriginalExtension();
        $file->move("assets/images/products",$fileName);
        $product->image = "/assets/images/products/" . $fileName;
        $product->save();
        if($request->product_type == "variant"){
            $productVariants = $request->productVariants ? $request->productVariants : [];
            if(count($productVariants)){
                foreach($productVariants as $pVariant){
                    $productVariant = new ProductVariant();
                    $productVariant->product_id = $product->id;
                    $productVariant->variant_id = $pVariant["variant_id"];
                    $productVariant->variant_detail_id = $pVariant["id"];
                    $productVariant->price = $pVariant["price"];
                    $productVariant->save();
                }
            }
        }
        return Redirect::route("admin.products.index")->with("success", "Product created successfully");
    }
    public function show(string $id){
        //
    }
    public function edit(Product $product){
        $variantProductDetails = VariantDetail::where("variant_id",1)->orderBy("order_no","ASC")->get(["id","variant_id","name"]);
        foreach($variantProductDetails as $vpDetail){
            $productVariant = ProductVariant::where(["product_id" => $product->id,"variant_detail_id" => $vpDetail->id])->first();
            if($productVariant){
                $vpDetail["status"] = true;
                $vpDetail["price"] = $productVariant->price;
            }else{
                $vpDetail["status"] = false;
                $vpDetail["price"] = "";
            }
        }
        return Inertia::render("Admin/Products/Edit",compact("product","variantProductDetails"));
    }
    public function update(ProductUpdateRequest $request,$id){
        $product = Product::findOrfail($id);
        $request->price = $request->price ? $request->price : null;
        $product->update($request->except("_method","slug","image","productVariants"));
        $product->slug = $this->generateSlug($request->name,$id);
        $product->save();
        $file = $request->file("image");
        if($file){
            $oldImage = $product->image;
            if($oldImage){
                if(file_exists(public_path($oldImage))){
                    unlink(public_path($oldImage));
                }
            }
            $fileName = $this->randomString(16) . '.' . $file->getClientOriginalExtension();
            $file->move("assets/images/products",$fileName);
            $product->image = "/assets/images/products/" . $fileName;
            $product->save();
        }
        ProductVariant::where("product_id",$product->id)->delete();
        if($request->product_type == "variant"){
            $productVariants = $request->productVariants ? json_decode($request->productVariants) : [];
            if(count($productVariants)){
                foreach($productVariants as $pVariant){
                    $productVariant = new ProductVariant();
                    $productVariant->product_id = $product->id;
                    $productVariant->variant_id = $pVariant->variant_id;
                    $productVariant->variant_detail_id = $pVariant->id;
                    $productVariant->price = $pVariant->price;
                    $productVariant->save();
                }
            }
        }
        return Redirect::route("admin.products.index")->with("success","Product updated successfully");
    }
    public function destroy(Product $product){
        $product->delete();
        return Inertia::location(route("admin.products.index"));
    }
    private function randomString($length = 16){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";	
        $size = strlen($chars);
        $string = '';
        for($i = 0;$i < $length;$i++){
            $string .= $chars[rand(0,$size - 1)];
        }
        return $string;
    }
    private function generateSlug($title,$id = null){
        $slug =  Str::slug($title,"-");
        $product = Product::where("slug",$slug);
        if($id){
            $product = $product->where("id","!=",$id);
        }
        $count = $product->count();
        if($count > 0){
            $slug = $slug."-".((int)$count + 1);
        }
        return $slug;
    }
}