@if (session()->has('danger'))
    <div class="danger flash-message fixed top-0 left-1/2 transform -translate-x-1/2 px-48 py-3">
        <p>
            {{ session('danger') }}
        </p>
    </div>
@endif

@if (session()->has('warning'))
    <div class="warning flash-message fixed top-0 left-1/2 transform -translate-x-1/2 bg-laravel text-white px-48 py-3">
        <p>
            {{ session('warning') }}
        </p>
    </div>
@endif

@if (session()->has('success'))
    <div class="success flash-message fixed top-0 left-1/2 transform -translate-x-1/2 bg-laravel text-white px-48 py-3">
        <p>
            {{ session('success') }}
        </p>
    </div>
@endif
