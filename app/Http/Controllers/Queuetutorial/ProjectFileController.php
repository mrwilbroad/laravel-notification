<?php

namespace App\Http\Controllers\Queuetutorial;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Jobs\ProcessDocumentFile;
use Illuminate\Support\Str;
use App\Jobs\CreateNewUser;
use Illuminate\Support\Facades\Bus;

class ProjectFileController extends Controller
{
    

    public function index()
    {
        // dd("dd");
       
        return view("Queuetutorial/processfile");
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                "processfile" => ['required','file',"mimes:pdf","max:949087"]
            ]);
    
            $file = $request->file("processfile");
            $filepath = $file->getRealPath();
            $exte = $file->getClientOriginalExtension();
            $user_id = $request->user()->id;
            $filename = str_replace(" ","-",$file->getClientOriginalName());
            $filename = Str::replaceLast(".".$exte,"",$filename);
            $filename = $filename."-".date("Y-m-d-h-s").".".$exte;
            $fileContent = file_get_contents($filepath);
            // ProcessDocumentFile::dispatch($fileContent,$filename,$user_id);

            // // in Chaining if you have many Job List
            Bus::chain([
                new ProcessDocumentFile($fileContent,$filename,$user_id),
                new CreateNewUser($request->user())
            ])
            ->onQueue("Fileprocessing")
            ->dispatch();
            
            return back()->with("success","We're processing ...");

        } catch (\Throwable $th) {
            report($th);
            return back()->with("UploadError","File upload failed , try again!");
        }

    }




}
