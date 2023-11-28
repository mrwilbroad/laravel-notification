<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\InvoicePaid;
use Illuminate\Support\Facades\Notification;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        return view("dashboard");
    }


    public function Store(Request $request)
    {
        $request->validate([
            "recipient" => "required|email",
            "message_no" => "required|max:100"
        ]);
        $notifications = $request->all();
        // $request->user()
        //     ->notify(new InvoicePaid($notifications['message_no'], $notifications['recipient']));

        // delay
        $delaytime = now()->addSecond(1);
        $request->user()
        ->notify(new InvoicePaid(
                $notifications['message_no'], 
                $notifications['recipient'])
        );

        // on Demand Notification
        // Notification::route("mail","newuser@gmail.com")
        //             ->notify(new InvoicePaid(
        //                 $notifications['message_no'], 
        //                 $notifications['recipient']));

            //  ->delay($delaytime);
        return back()->with("success","Notification is successfull sent to ".$notifications['recipient']);

    }
}
