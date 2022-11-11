<!doctype html>
<html>

<head>
    @include('includes.head')
</head>

<body>

    <header>
        @include('includes.header')
    </header>

    <div id="wrapper">
        <!-- sidebar content -->

        @include('includes.sidebar')

        <!-- main content -->
        <div id="page-content-wrapper">
            @yield('content')
        </div>
        @include('includes.footer')
    </div>
    </div>
</body>

</html>