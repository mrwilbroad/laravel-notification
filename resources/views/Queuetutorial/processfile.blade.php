<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container mt-4">
        
        @if(Session::has("success"))
          <section class="alert alert-success alert-dismissible fade show">
            {{ Session::get("success") }}
          </section>
        @endif
        @if(Session::has("UploadError"))
          <section class="alert alert-danger alert-dismissible fade show">
            {{ Session::get("UploadError") }}
          </section>
        @endif
           <h4>Processfile</h4>
           <form enctype="multipart/form-data" action={{ url("process-file") }} class="col-5 vstack gap-2" method="POST" >
            @csrf
                <section>
                       <label class="form-label">Choose file</label>
                       <input type="file" value="{{ old('processfile') }}" name="processfile" class="form-control" />

                       @error("processfile")
                       <section>
                           <small class="text-danger">{{ $message }}</small>
                       </section>
                       @enderror
                </section>

                <section>
                    <button type="submit" class="btn btn-success">Send file</button>
                </section>
           </form>
    </div>
</x-app-layout>
