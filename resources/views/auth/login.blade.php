<!DOCTYPE html>
<html lang="en">
	<head>

		<meta charset="UTF-8">
		<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="Description" content="Bootstrap Responsive Admin Web Dashboard HTML5 Template">
		<meta name="Author" content="Bambang VS Dobleh">
		<!-- <meta name="Keywords" content="admin,admin dashboard,admin dashboard template,admin panel template,admin template,admin theme,bootstrap 4 admin template,bootstrap 4 dashboard,bootstrap admin,bootstrap admin dashboard,bootstrap admin panel,bootstrap admin template,bootstrap admin theme,bootstrap dashboard,bootstrap form template,bootstrap panel,bootstrap ui kit,dashboard bootstrap 4,dashboard design,dashboard html,dashboard template,dashboard ui kit,envato templates,flat ui,html,html and css templates,html dashboard template,html5,jquery html,premium,premium quality,sidebar bootstrap 4,template admin bootstrap 4"/> -->

		<!-- Title -->
		<title> la - Attendance </title>

		@include('assets.styles')

	</head>

	<body class="main-body">

		<!-- Loader -->
		<div id="global-loader">
			<img src="{{asset('assets/img/loader.svg')}}" class="loader-img" alt="Loader">
		</div>
		<!-- /Loader -->

        <!-- Page -->
        <div class="page">
                
                <div class="container-fluid">
                    <div class="row no-gutter">
                        <!-- The image half -->
                        <div class="col-md-6 col-lg-6 col-xl-7 d-none d-md-flex bg-primary-transparent">
                            <div class="row wd-100p mx-auto text-center">
                                <div class="col-md-12 col-lg-12 col-xl-12 my-auto mx-auto wd-100p">
                                    <img src="{{ asset('assets/img/attendtr.png') }}" class="my-auto ht-xl-80p wd-md-100p wd-xl-80p mx-auto" alt="logo">
                                </div>
                            </div>
                        </div>
                        <!-- The content half -->
                        <div class="col-md-6 col-lg-6 col-xl-5 bg-white">
                            <div class="login d-flex align-items-center py-2">
                                <!-- Demo content-->
                                <div class="container p-0">
                                    <div class="row">
                                        <div class="col-md-10 col-lg-10 col-xl-9 mx-auto">
                                            <div class="card-sigin">
                                                <div class="mb-5 d-flex"> <a href="index.html"><img src="{{ asset('assets/img/logo-intek.png') }}" class="sign-favicon ht-40" alt="logo"></a><h1 class="main-logo1 mr-1 mr-0 my-auto tx-28">Attendance</h1></div>
                                                <div class="card-sigin">
                                                    <div class="main-signup-header">
                                                        <h2>Welcome back!</h2>
                                                        <h5 class="font-weight-semibold mb-4">Please sign in to continue.</h5>
                                                        <form method="POST" action="{{ route('login') }}">
                                                            @csrf
                                                            <div class="form-group">
                                                                <label>Email</label> <input class="form-control  @error('email') is-invalid @enderror" placeholder="Enter your email" name="email" type="email">
                                                                @error('email')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Password</label> <input class="form-control  @error('password') is-invalid @enderror" placeholder="Enter your password" name="password" type="password">
                                                                @error('password')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div><button type="submit" class="btn btn-main-primary btn-block">Sign In</button>
                                                            <!-- <div class="row row-xs">
                                                                <div class="col-sm-6">
                                                                    <button class="btn btn-block"><i class="fab fa-facebook-f"></i> Signup with Facebook</button>
                                                                </div>
                                                                <div class="col-sm-6 mg-t-10 mg-sm-t-0">
                                                                    <button class="btn btn-info btn-block"><i class="fab fa-twitter"></i> Signup with Twitter</button>
                                                                </div>
                                                            </div> -->
                                                        </form>
                                                        <!-- <div class="main-signin-footer mt-5">
                                                            <p><a href="">Forgot password?</a></p>
                                                            <p>Don't have an account? <a href="page-signup.html">Create an Account</a></p>
                                                        </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- End -->
                            </div>
                        </div><!-- End -->
                    </div>
                </div>
                
            </div>
            <!-- End Page -->

		<!-- Back-to-top -->
		<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>

        @include('assets.scripts')

    </body>
</html> 