<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\EmailTemplate;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Newsletter;
use App\Jobs\ContactsEmailJob;
use App\Jobs\CustomOrderEmailJob;

class FrontendController extends Controller
{
    public function index(Request $request): Response{
        $allProducts = Product::orderBy("id","DESC")->limit(6)->get();
        $linkersProducts = Product::where(["category" => "linkers"])->orderBy("id","DESC")->limit(6)->get();
        return Inertia::render("Frontend/Home",compact("allProducts","linkersProducts"));
    }
    public function about(Request $request): Response{
        return Inertia::render("Frontend/About");
    }
    public function services(Request $request): Response{
        return Inertia::render("Frontend/Services");
    }
    public function contacts(Request $request): Response{
        return Inertia::render("Frontend/Contacts");
    }
    public function products(Request $request){
        $category = $request->category ? $request->category : null;
        $search = $request->search ? $request->search : null;
        $products = Product::select("id","name","slug","category","catalog_number","price","image");
        if($category){
            $products = $products->where("category",$category);
        }
        if($search){
            $products = $products->where(function($query) use ($search){
                return $query->where('name','like',"%{$search}%")->orWhere('catalog_number','like',"%{$search}%")->orWhere('cas_number','like',"%{$search}%");
            });
        }
        $products = $products->paginate(12);
        return Inertia::render("Frontend/Products",compact("products","category","search"));
    }
    public function productDetail(Request $request,$slug){
        if(in_array($slug,["linkers","new","pas"])){
            $products = Product::where("category",$slug)->paginate(12);
            $category = $slug;
            if($category == "new"){
                $category = "New From Kimia";
            }else if($category == "pas"){
                $category = "Products for Accelerated Synthesis";
            }
            return Inertia::render("Frontend/ProductByCategory",["category" => $category,"slug" => $slug,"products" => $products]);
        }else{
            $product = Product::where("slug",$slug)->first();
            if(!$product){
                return Redirect::route("home");
            }
            if($product->product_type == "variant"){
                $product->productVariants = ProductVariant::with("variantDetail")->where("product_id",$product->id)->get(["id","variant_detail_id","price"]);
            }
            $relatedProducts = Product::where("category",$product->category)->where("slug","!=",$slug)->orderBy("id","DESC")->limit(4)->get();
            return Inertia::render("Frontend/ProductDetail",compact("product","relatedProducts"));
        }
    }
    public function sendContacts(Request $request){
        $response = $this->verifyRecaptcha($request->token);
        if($response && $response->success){
            $data = $request->except("token");
            $data["to_address"] = env("CONTACTS_EMAIL_ADDRESS");
            $emailTemplate = EmailTemplate::where("type","contacts")->first();
            if($emailTemplate){
                $subject = str_replace("%site_title%","Kimia Corp.",$emailTemplate->subject);
                $subject = str_replace("%site_url%","https://kimiacorp.com",$subject);
                $subject = str_replace("%subject%",$data['subject'],$subject);
                $subject = str_replace("%name%",$data['name'],$subject);
                $subject = str_replace("%company%",$data['company'],$subject);
                $subject = str_replace("%email%",$data['email'],$subject);
                $subject = str_replace("%phone%",$data['phone'],$subject);
                $subject = str_replace("<p",'<p style="margin:0;"',$subject);

                $content = str_replace("%site_title%","Kimia Corp.",$emailTemplate->email_content);
                $content = str_replace("%site_url%","https://kimiacorp.com",$content);
                $content = str_replace("%subject%",$data['subject'],$content);
                $content = str_replace("%name%",$data['name'],$content);
                $content = str_replace("%company%",$data['company'],$content);
                $content = str_replace("%email%",$data['email'],$content);
                $content = str_replace("%phone%",$data['phone'],$content);
                $content = str_replace("%message%",$data['msg'],$content);
                $content = str_replace("<p",'<p style="margin:0;"',$content);

                $data['content'] = $content;
                $data['subject'] = $subject;
                ContactsEmailJob::dispatch($data);
            }
            return json_encode(["status" => "success","message" => "Your enquiry has been submitted."]);
        }else{
            return json_encode(["status" => "error","message" => "Invalid Recaptcha"]);
        }
    }
    public function Newsletter(Request $request){
        $newsletter = Newsletter::where("email",$request->email)->first();
        if($newsletter){
            return json_encode(["status" => 419,"message" => "You have already subscribed."]);
        }
        $newsletter = new Newsletter();
        $newsletter->email = $request->email;
        $newsletter->save();
        return json_encode(["status" => 200,"message" => "Thanks for subscribe."]);
    }
    public function customOrder(Request $request){
        $data = $request->all();
        $data["to_address"] = env("ORDERS_EMAIL_ADDRESS");
        $emailTemplate = EmailTemplate::where("type","custom_order")->first();
        if($emailTemplate){
            $subject = str_replace("%site_title%","Kimia Corp.",$emailTemplate->subject);
            $subject = str_replace("%site_url%","https://kimiacorp.com",$subject);
            $subject = str_replace("%product_name%",$data['product_name'],$subject);
            $subject = str_replace("%name%",$data['name'],$subject);
            $subject = str_replace("%email%",$data['email'],$subject);
            $subject = str_replace("<p",'<p style="margin:0;"',$subject);

            $content = str_replace("%site_title%","Kimia Corp.",$emailTemplate->email_content);
            $content = str_replace("%site_url%","https://kimiacorp.com",$content);
            $content = str_replace("%product_name%",$data['product_name'],$content);
            $content = str_replace("%name%",$data['name'],$content);
            $content = str_replace("%email%",$data['email'],$content);
            $content = str_replace("%message%",$data['msg'],$content);
            $content = str_replace("<p",'<p style="margin:0;"',$content);

            $data['content'] = $content;
            $data['subject'] = $subject;
            CustomOrderEmailJob::dispatch($data);
        }
        return json_encode(["message" => "Your enquiry has been submitted."]);
    }
    private function verifyRecaptcha($token){
        $url = 'https://www.google.com/recaptcha/api/siteverify?secret='.env('RECAPTCHA_SECRET_SITE_KEY').'&response='.$token;
        $curl = curl_init();
        curl_setopt_array($curl,[
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ]);
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response);
    }
}