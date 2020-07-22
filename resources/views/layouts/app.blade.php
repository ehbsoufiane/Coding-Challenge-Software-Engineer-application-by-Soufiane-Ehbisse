<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} | Challenge</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{asset('css/app.css') }}">
    @yield('stylesheets')
</head>
<body>

    <div class="container" id="app">
        
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="{{ route('categories.index') }}">{{ env('APP_NAME') }}</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
          
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="{{ route('categories.index') }}">Categories <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Products</a>
                    </li>
                </ul>
            </div>
        </nav>

        @yield('content')
    </div>

    <script src="{{asset('js/jquery.min.js') }}"></script>
    <script src="{{asset('js/bootstrap.min.js') }}"></script>
    <script src="{{asset('js/app.js') }}"></script>

    @yield('scripts')
</body>
</html>