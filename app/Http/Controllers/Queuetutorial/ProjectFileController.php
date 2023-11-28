<?php

namespace App\Http\Controllers\Queuetutorial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\ProcessDocumentFile;

class ProjectFileController extends Controller
{
    

    public function index()
    {
        // dd("dd");
        return view("Queuetutorial/processfile");
    }


    public function store(Request $request)
    {
        $request->validate([
            "processfile" => ['required','file',"mimes:pdf","max:949087"]
        ]);

        $file = $request->file("processfile");
        $exte = $file->getClientOriginalExtension();
        $user_id = $request->user()->id;
        $or = $file->getClientOriginalName();
        $filename = $or."-".date("Y-m-d-h-s").".".$exte;
        $file = base64_encode($file);
        $filename = base64_encode($filename);

        dispatch(new ProcessDocumentFile($file,$filename,$user_id));
        return back()->with("success","File is updloaded succesfull");

    }




}
