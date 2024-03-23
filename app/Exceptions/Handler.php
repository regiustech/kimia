<?php
namespace App\Exceptions;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];
    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void{
        $this->reportable(function(Throwable $e){
            //
        });
    }
    /**
     * Prepare exception for rendering.
     * @param \Throwable $e
     * @return \Throwable
     */
    public function render($request,Throwable $e){
        $response = parent::render($request,$e);
        if($response->status() == 500){
            return Inertia::render('Errors/500');
        }elseif($response->status() == 503){
            return Inertia::render('Errors/503');
        }elseif($response->status() == 500){
            return Inertia::render('Errors/500');
        }elseif($response->status() == 404){
            return Inertia::render('Errors/404');
        }elseif($response->status() == 403){
            return Inertia::render('Errors/403');
        }elseif($response->status() === 419){
            return back()->with(['message' => 'The page expired, please try again.']);
        }
        return $response;
    }
}