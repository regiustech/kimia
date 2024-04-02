<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\Variant;
use App\Models\VariantDetail;
use Carbon\Carbon;
use App\Http\Requests\VariantDetailStoreUpdateRequest;

class VariantDetailController extends Controller
{
    public function index(){
        return Inertia::render("Admin/VariantDetails/Index");
    }
    public function getAllVariantDetails(Request $request){
        $length = $request->length ? $request->length : 10;
        $variantDetails = VariantDetail::with('variant')->orderBy("id","desc");
        if($length > 0){
            $output = $variantDetails->paginate($length);
        }else{
            $variantDetails = $variantDetails->get();
            $output = [
                "current_page" => 1,
                "data" => $variantDetails,
                "from" => 1,
                "to" => count($variantDetails),
                "total" => count($variantDetails),
                "last_page" => 1
            ];
        }
        return json_encode($output);
    }
    public function create(){
        $variants = Variant::get(["id","name"]);
        return Inertia::render("Admin/VariantDetails/Edit",["variants" => $variants,"variantDetail" => new VariantDetail]);
    }
    public function store(VariantDetailStoreUpdateRequest $request){
        $variantDetail = new VariantDetail($request->all());
        $variantDetail->save();
        return Redirect::route("admin.variant-details.index")->with("success", "Variant Detail created successfully");
    }
    public function show(string $id){
        //
    }
    public function edit(VariantDetail $variantDetail){
        $variants = Variant::get(["id","name"]);
        return Inertia::render("Admin/VariantDetails/Edit",compact("variants","variantDetail"));
    }
    public function update(VariantDetailStoreUpdateRequest $request,$id){
        $variantDetail = VariantDetail::findOrfail($id);
        $variantDetail->update($request->except("_method"));
        return Redirect::route("admin.variant-details.index")->with("success","Variant Detail updated successfully");
    }
    public function destroy(VariantDetail $variantDetail){
        $variantDetail->delete();
        return Inertia::location(route("admin.variant-details.index"))->with("success","Variant Detail deleted successfully");
    }
}