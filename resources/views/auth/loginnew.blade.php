<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700%7CRoboto:300,400,500,600,700" media="all">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
    <link href="{{asset('backend/assets/vendors/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet" />
    <link href="{{asset('backend/assets/vendors/themify-icons/themify-icons.css')}}" rel="stylesheet" />
    <link href="{{asset('backend/assets/vendors/line-awesome/css/line-awesome.min.css')}}" rel="stylesheet" /><!-- PAGE LEVEL VENDORS-->

    <!-- THEME STYLES-->
    <link href="{{asset('backend/assets/css/app.min.css')}}" rel="stylesheet" />
    <link rel="icon" href="{{ asset('backend/assets/img/favicon.png')}}" type="image/x-icon" />
    <!-- PAGE LEVEL STYLES-->
    <style>
        body {
            background-color: #eff4ff;
        }

        .auth-wrapper {
            flex: 1 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 50px 15px 30px 15px;
        }

        .auth-content {
            max-width: 400px;
            flex-basis: 400px;
            box-shadow: 0 5px 20px #d6dee4;
        }

        .home-link {
            position: absolute;
            left: 5px;
            top: 10px;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <div class="auth-wrapper">
            <div class="card auth-content mb-0">
                <div class="card-body py-5">
                    <div class="text-center mb-5">
                        <h3 class="mb-3 text-primary">LLYOMAX</h3>
                        <div class="font-18 text-center">Login to Your account</div>
                    </div>
                    <div class="flash-message">
                        @if ($errors->any())
                        <div class="alert alert-danger">

                            @foreach ($errors->all() as $error)
                            {{ $error }}
                            @endforeach

                        </div>
                        @endif
                        @foreach (['danger', 'warning', 'success', 'info','error'] as $msg)
                        @if(Session::has('alert-' . $msg))

                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} </p>
                        @endif
                        @endforeach
                    </div>


                    <form id="login-form" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <div class="md-form mb-0"><input class="md-form-control" type="text" name="mobile"><label>Mobile Number</label></div>
                        </div>
                        <div class="mb-4">
                            <div class="md-form mb-0"><input class="md-form-control" type="password" name="password"><label>Password</label></div>
                        </div>
                        <!-- <div class="flexbox mb-5">
                            <label class="ui-switch switch-solid">
                                <input type="checkbox" checked="">
                                <span class="ml-0"></span> Remember Me
                            </label>
                            <a href="{{ route('password.request') }}">Forgot password?</a>
                        </div> -->
                        <button class="btn btn-primary btn-rounded btn-block" type="submit">LOGIN</button>
                    </form>
                    <div class="text-center mt-5 font-13">
                        <div class="mb-2 text-muted">2021 © All rights reserved</div>
                        <!-- <div>See<a class="hover-link ml-2" href="#" style="border-bottom: 1px solid">Privacy Policy</a></div> -->
                    </div>
                </div>
            </div><a class="btn btn-link home-link" href="/"><span class="btn-icon"><i class="ti-arrow-left font-20"></i>Go Home</span></a>
        </div>
    </div><!-- BEGIN: Page backdrops-->
    <div class="sidenav-backdrop backdrop"></div>
    <div class="preloader-backdrop">
        <div class="page-preloader">Loading</div>
    </div><!-- END: Page backdrops-->
    <!-- CORE PLUGINS-->
    <script src="{{asset('backend/assets/vendors/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{asset('backend/assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script><!-- PAGE LEVEL PLUGINS-->
    <script src="{{asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script><!-- CORE SCRIPTS-->
    <script src="{{asset('backend/assets/js/app.min.js')}}"></script><!-- PAGE LEVEL SCRIPTS-->
    <script>
        $(function() {

        });
    </script>
</body>

</html>



