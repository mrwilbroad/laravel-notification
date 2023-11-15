<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-2">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>


                @if(Session::has("success"))
                 <section class="alert alert-success dismissible fade show col-lg-6">
                      <strong>{{ Session::get("success") }}</strong>
                 </section>
                 @endif
                  
                   <form action="/dashboard" method="POST" class="vstack gap-2 col-4 mx-5 mb-2">
                    <h5 class="">Send Notification</h5>
                    @csrf
                    
                          <section>
                              <input 
                              type="text"
                              value="{{ old("recipient") }}" 
                              name="recipient" 
                              placeholder="Recipient Email" 
                              class="form-control"/>

                               @error("recipient")
                               <small class="text-danger">{{ $message }}</small>
                               @enderror
                          </section>

                          <section>
                            <textarea 
                            type="text"
                            value="{{ old("message_no") }}"  
                            name="message_no"
                            placeholder="Recipient message"
                            class="form-control">
                            </textarea>
                            @error("message_no")
                               <small class="text-danger">{{ $message }}</small>
                               @enderror
                            
                        </section>

                        <section>
                               <button type="submit" class='btn btn-success'>Send notification</button>
                        </section>


                   </form>
            </div>
        </div>
    </div>
</x-app-layout>
