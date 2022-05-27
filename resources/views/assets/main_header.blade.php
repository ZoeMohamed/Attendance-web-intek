<div class="main-header nav nav-item hor-header top-header">
				<div class="container">
					<div class="main-header-left">
						<a class="animated-arrow hor-toggle horizontal-navtoggle"><span></span></a><!-- sidebar-toggle-->
						<a class="header-brand" href="#">
							<img src="{{asset('assets/img/brand/logo-white.png')}}" width="100" height="800" class="desktop-dark">
							<img src="{{asset('assets/img/brand/logo.png')}}" class="desktop-logo">
							<img src="{{asset('assets/img/brand/favicon.png')}}" class="desktop-logo-1">
							<img src="{{asset('assets/img/brand/favicon-white.png')}}" class="desktop-logo-dark">
						</a>
						<div class="main-header-center ml-8">
							<span class="badge badge-success" id="clock">00:00:00</span>
							
						</div>
						
					</div><!-- search -->
					<a class="header-brand header-brand2 d-none d-lg-block" href="#">
						<img src="{{asset('assets/img/logo-intek.png')}}" width="100" height="800px" class="desktop-dark">
						<img src="{{asset('assets/img/logo-intek.png')}}" width="100" height="800px" class="desktop-logo">
						<img src="{{asset('assets/img/brand/favicon.png')}}" class="desktop-logo-1">
						<img src="{{asset('assets/img/brand/favicon-white.png')}}" class="desktop-logo-dark">
					</a>
					<div class="main-header-right">
						<ul class="nav">
							
						</ul>
						<div class="nav nav-item  navbar-nav-right ml-auto">
							<div class="nav-link" id="bs-example-navbar-collapse-1">
								
							</div>
							<div class="dropdown nav-item main-header-notification">
								<a class="new nav-link" href="#">
								<svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg><span class=" pulse"></span></a>
								<div class="dropdown-menu">
									<div class="menu-header-content bg-primary text-left">
										<div class="d-flex">
											<h6 class="dropdown-title mb-1 tx-15 text-white font-weight-semibold">Notifications</h6>
											<span class="badge badge-pill badge-warning ml-auto my-auto float-right">Mark All Read</span>
										</div>
										<p class="dropdown-title-text subtext mb-0 text-white op-6 pb-0 tx-12 ">You have 4 unread Notifications</p>
									</div>
									<div class="main-notification-list Notification-scroll">
										<a class="d-flex p-3 border-bottom" href="#">
											<div class="notifyimg bg-pink">
												<i class="la la-file-alt text-white"></i>
											</div>
											<div class="ml-3">
												<h5 class="notification-label mb-1">New files available</h5>
												<div class="notification-subtext">10 hour ago</div>
											</div>
											<div class="ml-auto" >
												<i class="las la-angle-right text-right text-muted"></i>
											</div>
										</a>
										
									</div>
									<div class="dropdown-footer">
										<a href="">VIEW ALL</a>
									</div>
								</div>
							</div>
							<div class="nav-item full-screen fullscreen-button">
								<a class="new nav-link full-screen-link" href="#"><svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-maximize"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path></svg></a>
							</div>
							<div class="dropdown main-profile-menu nav nav-item nav-link">
								<a class="profile-user d-flex" href=""><img alt="" src="{{asset('assets/img/faces/6.jpg')}}"></a>
								<div class="dropdown-menu">
									<div class="main-header-profile bg-primary p-3">
										<div class="d-flex wd-100p">
											<div class="main-img-user"><img alt="" src="{{asset('assets/img/faces/6.jpg')}}" class=""></div>
											<div class="ml-3 my-auto">
												<h6>{{ Auth::user()->name }}</h6><span>~The Master</span>
											</div>
										</div>
									</div>
									<!-- <a class="dropdown-item" href=""><i class="bx bx-user-circle"></i>Profile</a>
									<a class="dropdown-item" href=""><i class="bx bx-cog"></i> Edit Profile</a>
									<a class="dropdown-item" href=""><i class="bx bxs-inbox"></i>Inbox</a>
									<a class="dropdown-item" href=""><i class="bx bx-envelope"></i>Messages</a>
									<a class="dropdown-item" href=""><i class="bx bx-slider-alt"></i> Account Settings</a> -->
									<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('frm-logout').submit();"><i class="bx bx-log-out"></i> Sign Out</a>  
									<form id="frm-logout" action="{{ route('logout') }}" method="POST" style="display: none;">
										{{ csrf_field() }}
									</form>
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>