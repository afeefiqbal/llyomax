<!DOCTYPE html>
<html lang="en">

@include('backend.includes.head')

<body  >
    <div class="page-wrapper">
        <div class="content-wrapper">

            @include('backend.includes.sidebar')
            <!-- BEGIN: Content-->
            <div class="content-area">
                @include('backend.includes.header')
                <div class="page-content fade-in-up">
                    <!-- BEGIN: Page heading-->
                    <div class="page-heading">
                        <div class="page-breadcrumb">
                            <h1 class="page-title">@yield('page-title')</h1>
                        </div>
                    </div><!-- BEGIN: Page content-->
                    <div>
                        @yield('content')
                    </div><!-- END: Page content-->
                </div>
                @include('backend.includes.footer')
            </div><!-- END: Content-->
        </div>
    </div>
    <!-- BEGIN: Page backdrops-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div><!-- END: Page backdrops-->
    @include('backend.includes.scripts')
</body>

</html>
