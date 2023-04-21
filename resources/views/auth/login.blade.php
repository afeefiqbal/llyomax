

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LLYOMAX</title>
    <link rel="stylesheet" href="{{asset('backend/assets/css/main.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link href="{{asset('backend/assets/vendors/@fortawesome/fontawesome-free/css/all.min.css')}}" rel="stylesheet" />
        <link href="{{asset('backend/assets/vendors/themify-icons/themify-icons.css')}}" rel="stylesheet" />
        <link href="{{asset('backend/assets/vendors/line-awesome/css/line-awesome.min.css')}}" rel="stylesheet" /><!-- PAGE LEVEL VENDORS-->

        <!-- THEME STYLES-->
        <link href="{{asset('backend/assets/css/app.min.css')}}" rel="stylesheet" />
        <link rel="icon" href="{{ asset('backend/assets/img/favicon.png')}}" type="image/x-icon" />
</head>

<body class="cntnt-mb">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 col-xs-12 col-sm-12">
                <div class="container mb-bg-log">
                    <div class="logo">
                        <img src="{{asset('backend/assets/img/new.png')}}" alt="">
                    </div>
                    <div class="title-log">
                    <h2>Login</h2>

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

                        <span class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} </span>
                        @endif
                        @endforeach
                    </div>
                    </div>

                    <div class="login-frm">
                        <form method="POST" id="login-form" action="{{ route('login') }}" >
                            @csrf
                            <div class="txt_field">
                                <input type="text" name="mobile"  required>
                                <label>Username</label>
                            </div>
                            <div class="txt_field">
                                <input  type="password" name="password" required>

                                <label>Password</label>
                            </div>
                            <input type="submit" value="Login">
                        </form>
                    </div>

                    <div class="copyright">
                        <p>Copyright © 2022 LLYOMAX. All rights reserved.Powered by <b>D5N Solution</b></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 bg-sc ">
                <div class="anime m-1600">
                <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_1z0fledt.json"  background="transparent"  speed="1"  style="width: 728px; height: 636px;"  loop  autoplay></lottie-player>
                </div>
                <div class="anime m-768">
                <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_1z0fledt.json"  background="transparent"  speed="1"  style="width: 345px; height: 345px;"  loop  autoplay></lottie-player>
                </div>
                <div class="anime m-1366">
                <lottie-player src="https://assets5.lottiefiles.com/packages/lf20_1z0fledt.json"  background="transparent"  speed="1"  style="width: 645px; height: 471px;"  loop  autoplay></lottie-player>
                </div>

           <p> Llyomax apart from others is the trust that customers place in
them. We are always at the forefront of delivering good quality products to the
customers. Llyomax also specializes in delivering products tailored to customers'
interests and requirements. </p>

<div class="contact-info">
<span class="location"><i class="fa-solid fa-location-dot"></i> Ponneth Building, Thanikkal, Kodur P.O,
Malappuram Dt, Kerala, Pin: 676504</span> <br>
<span class="mobile"><i class="fa-solid fa-phone"></i> 9645 93 14 58</span>
<span class="email"><i class="fa-solid fa-envelope"></i> info@llyomax.com</span>
</div>

        </div>
        </div>
    </div>
    <!--  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
        <script src="{{asset('backend/assets/vendors/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{asset('backend/assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script><!-- PAGE LEVEL PLUGINS-->
        <script src="{{asset('backend/assets/vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script><!-- CORE SCRIPTS-->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="{{asset('backend/assets/vendors/bootstrap/dist/js/bootstrap.bundle.min.js')}}"></script><!-- PAGE LEVEL PLUGINS-->
    <script src="{{asset('backend/assets/js/app.min.js')}}"></script><!-- PAGE LEVEL SCRIPTS-->
    <script>

    </script>
</body>
</html>






