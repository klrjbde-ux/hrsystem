<!DOCTYPE html>
<html lang="en">
{{ $header ?? '' }}

<body>

    {{ $navabr ?? '' }}
    {{ $sidebar ?? '' }}
    @yield('content')
    {{ $footer ?? '' }}
    @yield('js')
</body>

</html>