<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Variant;
use Carbon\Carbon;
use App\Http\Requests\VariantStoreUpdateRequest;

class VariantController extends Controller
{
    public function index(){
        return Inertia::render("Admin/Variants/Index");
    }
    public function getAllVariants(Request $request){
        $length = $request->length ? $request->length : 10;
        $variants = Variant::orderBy("id","desc");
        if($length > 0){
            $output = $variants->paginate($length);
        }else{
            $variants = $variants->get();
            $output = [
                "current_page" => 1,
                "data" => $variants,
                "from" => 1,
                "to" => count($variants),
                "total" => count($variants),
                "last_page" => 1
            ];
        }
        return json_encode($output);
    }
    public function create(){
        return Inertia::render("Admin/Variants/Edit",["variant" => new Variant]);
    }
    public function store(VariantStoreUpdateRequest $request){
        $variant = new Variant($request->all());
        $variant->save();
        return Redirect::route("admin.variants.index")->with("success", "Variant created successfully");
    }
    public function show(string $id){
        //
    }
    public function edit(Variant $variant){
        return Inertia::render("Admin/Variants/Edit",compact("variant"));
    }
    public function update(VariantStoreUpdateRequest $request,$id){
        $variant = Variant::findOrfail($id);
        $variant->update($request->except("_method"));
        return Redirect::route("admin.variants.index")->with("success","Variant updated successfully");
    }
    public function destroy(Variant $variant){
        $variant->delete();
        return Inertia::location(route("admin.variants.index"))->with("success","Variant deleted successfully");
    }
}