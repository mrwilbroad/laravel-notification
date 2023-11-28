# Laravel notification, Event Scheduling and Queue functionality


# Added functionality to dispatch Job and send File to aws s3 bucket 


## inside Controller 
```php
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
```


## inside Job Class contructor , since to allow serialization to be done successfull, try to encode the Uploaded File format before transimiting it from controller to Job Class
```php
 public function __construct(
        public $file,
        public $filename,
        public $user_id
    ) {
        $this->file = base64_decode($file);
        $this->filename = base64_decode($filename);
    }
```

## then in Handle Method of the Job Class 
```php
Storage::disk("s3")->putFileAs(
            "laravel-notification",
            $this->file,
            $this->filename
        );
        Processfile::create([
            "filename"  => $this->filename,
            "path" => "laravel-notification/" . $this->filename,
            "user_id" => $this->user_id
        ]);
```