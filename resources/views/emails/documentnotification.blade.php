
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @isset($username)
                        <h6>Mail to {{ $username }}</h6>   
                    @endisset
                        <h5 class="text-info display-5">Mail message for testing is here...</h5>
                </div>
            </div>
        </div>
    </div>
