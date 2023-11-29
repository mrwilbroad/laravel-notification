# Laravel notification, Event Scheduling and Queue functionality

Added functionality to dispatch Job and send File to aws s3 bucket

Inside Controller 
```php
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
            ProcessDocumentFile::dispatch($fileContent,$filename,$user_id);
            return back()->with("success","File is updloaded succesfull");

        } catch (\Throwable $th) {
           
            return back()->with("UploadError","File upload failed , try again!");
        }
```


Inside Job Class contructor , since to allow serialization to be done successfull, try to encode the Uploaded File format before transimiting it from controller to Job Class

```php
 public function __construct(
        public $fileContent,
        public $filename,
        public $user_id
    ) {
    }
```

Then in Handle Method of the Job Class 
```php
     public function handle(): void
    {
        
        $fullpath = "laravel-notification/".$this->filename;
        Storage::disk("s3")->put($fullpath, $this->fileContent);
        Processfile::create([
            "filename"  => $this->filename,
            "path" => $fullpath,
            "user_id" => $this->user_id
        ]);
    }
```


it's done , Thanks for reading
# Regard mrwilbroad