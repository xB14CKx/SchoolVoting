<?php
namespace App\Mail\Auth;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class TestEmail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct()
    {
        //
    }
    public function build()
    {
        return $this->subject('Test Email from Laravel Breeze')
                    ->view('auth.test-email');
    }
}
