<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Mail\Auth\TestEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
class TestEmailController extends Controller
{
    public function send(Request $request)
    {
        Mail::to($request->user()->email)->send(new TestEmail());
        return redirect()->route('dashboard')->with('status', 'Test email sent!');
    }
}
