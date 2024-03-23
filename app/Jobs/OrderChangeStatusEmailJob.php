<?php
namespace App\Jobs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class OrderChangeStatusEmailJob implements ShouldQueue
{
    use Dispatchable,InteractsWithQueue,Queueable,SerializesModels;
    protected $details;
    public function __construct($details){
        $this->details = $details;
    }
    public function handle(){
        $data = $this->details;
        Mail::send(['html' => 'emails.change-status'],$data,function($mail) use ($data){
            $mail->to($data["to_address"])->subject($data["subject"]);
        });
    }
}