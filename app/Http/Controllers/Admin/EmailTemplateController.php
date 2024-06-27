<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use App\Http\Requests\EmailTemplateStoreUpdateRequest;

class EmailTemplateController extends Controller
{
    public function index(){
        return Inertia::render("Admin/EmailTemplates/Index");
    }
    public function getAllTemplates(Request $request){
        $length = $request->length ? $request->length : 10;
        $emailTemplates = EmailTemplate::orderBy("id","asc");
        if($length > 0){
            $output = $emailTemplates->paginate($length);
        }else{
            $emailTemplates = $emailTemplates->get();
            $output = [
                "current_page" => 1,
                "data" => $emailTemplates,
                "from" => 1,
                "to" => count($emailTemplates),
                "total" => count($emailTemplates),
                "last_page" => 1
            ];
        }
        return json_encode($output);
    }
    public function edit(EmailTemplate $emailTemplate){
        return Inertia::render("Admin/EmailTemplates/Edit",compact("emailTemplate"));
    }
    public function update(EmailTemplateStoreUpdateRequest $request,$id){
        $emailTemplate = EmailTemplate::findOrfail($id);
        $emailTemplate->update($request->except("_method"));
        return Redirect::route("admin.email-templates.index")->with("success","Email Template updated successfully");
    }
}