<!DOCTYPE html>
<html lang="en">

<head>
    @include('layouts.admin.inc.head')
</head>

<body class="text-gray-800 font-inter">

    @yield('content')

    @include('layouts.admin.inc.scripts')
    @yield('after_scripts')
</body>

</html>
