<!DOCTYPE html>
<html lang="en">
@include('include.User.head')

<body data-page="dashboard" class="admin-layout">
    <div id="layout-wrapper">
        @include('include.User.header')
        @include('include.User.sidebar')

        @yield('content')
        @include('include.User.footer')

    </div>
    @include('include.User.script')

</body>

</html>
