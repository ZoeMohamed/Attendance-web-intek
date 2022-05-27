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
		<style type="text/css">
		body {
			-webkit-print-color-adjust: exact !important;
		}
		/* .digital-clock {
			border-width: 0;
			line-height: 1.538;
			padding: 9px 20px;
			transition: none;
			color: #fff !important;
			background-color: #22c03c;
   			border-color: #22c03c;
		} */
		/* .digital-clock {
			margin: auto;
			position: absolute;
			top: 0;
			left: 0;
			bottom: 0;
			right: 0;
			width: 80px;
			height: 0px;
			text-shadow: black;
			border: 2px solid #999;
			border-radius: 4px;
			text-align: center;
			font: 50px/60px 'DIGITAL', Helvetica;
			background: linear-gradient(90deg, #000, #555);
		} */
		</style>
	</head>

	<body class="main-body">

		<!-- Loader -->
		 {{--<div id="global-loader">
			<img src="{{asset('assets/img/loader.svg')}}" class="loader-img" alt="Loader">
		</div>--}}
		<!-- /Loader -->

		<!-- Page -->
		<div class="page">

			<!-- main-header opened -->
			{{-- @include('assets.main_header') --}}
			<!-- /main-header -->

			<!--Horizontal-main -->
			{{-- @include('assets.main_horizontal') --}}
			<!--Horizontal-main -->

			<!-- main-content opened -->
			<div class="main-content horizontal-content">

				<!-- container opened -->
				<div class="container">

					<!-- breadcrumb -->
					{{-- @yield('header_content') --}}
					<!-- /breadcrumb -->

					@yield('main_content')
					
				</div>
			</div>
			<!-- Container closed -->
			<!-- Footer opened -->
			{{--@include('assets.footer')--}}
			<!-- Footer closed -->

		</div>
		<!-- End Page -->

		<!-- Back-to-top -->
		<!-- <a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a> -->

		{{--@include('assets.scripts')--}}

	</body>
</html>