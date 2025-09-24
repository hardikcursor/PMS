<!DOCTYPE html>
<html lang="en">
@include('include.admin.head')

<body data-sidebar="dark" data-layout-mode="light">
    <div id="layout-wrapper">
        @include('include.admin.header')
        @include('include.admin.sidebar')

       
            @yield('content')
        @include('include.admin.footer')

    </div>
    @include('include.admin.script')

</body>

</html>
