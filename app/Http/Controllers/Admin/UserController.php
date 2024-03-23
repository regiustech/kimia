<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Requests\UserStoreOrUpdateRequest;

class UserController extends Controller
{
    public function index(){
        return Inertia::render("Admin/Users/Index");
    }
    public function getAllUsers(Request $request){
        $length = $request->length ? $request->length : 10;
        $users = User::select("id","first_name","last_name","email","phone","company","country","street_address","city","state","zipcode","created_at")->customer()->orderBy("id","desc");
        if($length > 0){
            $output = $users->paginate($length);
        }else{
            $users = $users->get();
            $output = [
                "current_page" => 1,
                "data" => $users,
                "from" => 1,
                "to" => count($users),
                "total" => count($users),
                "last_page" => 1
            ];
        }
        return json_encode($output);
    }
    public function create(){
        //
    }
    public function store(Request $request){
        //
    }
    public function show(User $user){
        return Inertia::render("Admin/Users/View",compact("user"));
    }
    public function edit(User $user){
        return Inertia::render("Admin/Users/Edit",compact("user"));
    }
    public function update(UserStoreOrUpdateRequest $request,$id){
        $user = User::findOrfail($id);
        $user->update($request->except("_method","role","user_id"));
        return Redirect::route("admin.users.index")->with("success","User updated successfully.");
    }
    public function destroy(User $user){
        $user->delete();
        return Inertia::location(route("admin.users.index"));
    }
}