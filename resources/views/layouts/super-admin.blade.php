<!DOCTYPE html>
<html lang="en">
@include('include.super-admin.head')

<body data-sidebar="dark" data-layout-mode="light">
    <div id="layout-wrapper">
        @include('include.super-admin.header')
        @include('include.super-admin.sidebar')

       
            @yield('content')
        @include('include.super-admin.footer')

    </div>
    @include('include.super-admin.script')

</body>

</html>
