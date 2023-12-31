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


sometimes you may wish to run Jobs in Chain , where after completion of one Job , the 2nd Job will start immediately
  - Let say you want to send notification to user who post file to your application
  - This is just miner example , we don't go deep to send it via mail 
  - inside controller
```php
 Bus::chain([
                new ProcessDocumentFile($fileContent,$filename,$user_id),
                new CreateNewUser($request->user())
            ])
              ->onQueue("Fileprocessing") //instead of default Queue(default), let use another queue name
            ->dispatch();
```


I was successfull be able to Upload <strong>JWT Handbook</strong> 
[see here and be able to download ](https://mrwilbroad-bucket.s3.amazonaws.com/laravel-notification/jwt-handbook-v0_14_1-2023-12-01-11-43.pdf)



```php
Bus::chain([
        new ProcessDocumentFile($fileContent,$filename,$user_id),
        new CreateNewUser($request->user()),
        new NotificationDocumentConfirmation($request->user())
    ])
    ->onQueue("low")
    ->dispatch(); 
```

## From terminal this is how Bus Jobs  are processed in chain with Queue named == low 
<img src="https://github.com/mrwilbroad/quality-images/blob/main/Screenshot%20from%202023-12-02%2014-04-52.png" />


it's done , Thanks for reading
# Regard mrwilbroad