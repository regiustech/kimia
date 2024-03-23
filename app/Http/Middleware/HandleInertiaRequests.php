<?php
namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Middleware;
use App\Models\Cart;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';
    public function version(Request $request): string|null{
        return parent::version($request);
    }
    public function share(Request $request): array{
        $itemCount = 0;
        $userId = $request->user() ? $request->user()->id : null;
        $sessionId = app("request")->session()->getId();
        $cart = Cart::userSession($userId,$sessionId)->first();
        if($cart){
            $itemCount = $cart->cartItems()->sum('quantity');
        }
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user()
            ],
            'flash' => function(){
                return [
                    'success' => Session::get('success'),
                    'error'   => Session::get('error')
                ];
            },
            'cartCount' => $itemCount
        ];
    }
}