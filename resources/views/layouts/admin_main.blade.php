<!DOCTYPE html>
<html lang="en" >
	<!-- begin::Head -->
	<head>
		<meta charset="utf-8" />
        @yield('title')
		<meta name="description" content="User profile view and edit">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<!--begin::Web font -->
		<script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
		<script>
          WebFont.load({
            google: {"families":["Poppins:300,400,500,600,700","Roboto:300,400,500,600,700"]},
            active: function() {
                sessionStorage.fonts = true;
            }
          });
		</script>
		<style type="text/css">
			.hide{
				display: none;
			}
		</style>
		<!--end::Web font -->
		<!--begin::Base Styles -->
		
    <link href="{{ asset('assets/vendor/base/vendors.bundle.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendor/base/style.bundle.css') }}" rel="stylesheet" type="text/css">
		<!--end::Base Styles -->
		<!--<link rel="shortcut icon" href="assets/img/user/favicon.ico"/>-->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css">
		<!--<link href="assets/assets/select2/dist/css/select2.min.css">-->
    <link href="{{ asset('assets/summernote/summernote.css') }}" rel="stylesheet" type="text/css">
    @yield('css')
	</head>
	<!-- end::Head -->
	<!-- end::Body -->
	<body class="m-page--fluid m-page--wide  m-header--fixed-mobile m-footer--push m-aside--on canvas-default"  >
		<!-- begin:: Page -->
		<div class="m-grid m-grid--hor m-grid--root m-page">
			<!-- begin::Header -->
			<header class="m-grid__item m-header "  data-minimize-mobile="hide" data-minimize-offset="200" data-minimize-mobile-offset="200" data-minimize="minimize" > 
				<div class="m-header__top">
					<div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
						<div class="m-stack m-stack--ver m-stack--desktop">
							<!-- begin::Brand -->
							<div class="m-stack__item m-brand">
								<div class="m-stack m-stack--ver m-stack--general m-stack--inline">
									<div class="m-stack__item m-stack__item--middle m-brand__logo">
										<a href="#" class="m-brand__logo-wrapper">
											<img width="170" alt="" src="{{ asset('assets/media/logos/logo-2.png') }}"/>
										</a>
									</div>
									
								</div>
							</div>
							<!-- end::Brand -->
							<!-- begin::Topbar -->
							<div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
								<div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
									<div class="m-stack__item m-topbar__nav-wrapper">
										<ul class="m-topbar__nav m-nav m-nav--inline">
											<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
												<a href="#" class="m-nav__link m-dropdown__toggle">
													<span class="m-topbar__userpic m--hide">
														<img src="{{ asset('assets/media/logos/logo-2.png') }}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
													</span>
													
													<span class="m-topbar__username">
														Dashboard
													</span>
												</a>
												
											</li>	
											<?php  	if($data->role!=5){?>
											<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown--skin-light" >
												<a href="admin/userlist" class="m-nav__link ">
													<span class="m-topbar__userpic m--hide">
														<img src="{{ asset('assets/media/logos/logo-2.png') }}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
													</span>
													
													<span class="m-topbar__username">
														Employees
													</span>
												</a>
												
											</li>
											<?php } ?>
											<li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown--skin-light" >
												<a href="{{ route('admin.logout') }}" class="m-nav__link ">
													<span class="m-topbar__userpic m--hide">
														<img src="{{ asset('assets/media/logos/logo-2.png') }}" class="m--img-rounded m--marginless m--img-centered" alt=""/>
													</span>
													
													<span class="m-topbar__username">
														logout
													</span>
												</a>
												
											</li>	
											<li id="m_quick_sidebar_toggle" class="m-nav__item">
												<a href="#" class="m-nav__link m-dropdown__toggle">
													<span class="m-nav__link-icon m-nav__link-icon--aside-toggle">
														<span class="m-nav__link-icon-wrapper">
															<i class="flaticon-chat-1"></i>
														</span>
													</span>
												</a>
											</li>										
										</ul>
									</div>
								</div>
							</div>
							<!-- end::Topbar -->
						</div>
					</div>
				</div>
				
			</header>
			<!-- end::Header -->
			<!-- begin::Body -->
			<div class="m-grid__item m-grid__item--fluid m-grid m-grid--hor-desktop m-grid--desktop m-body">
				<div class="m-grid__item m-grid__item--fluid  m-grid m-grid--ver	m-container m-container--responsive m-container--xxl m-page__container">
					<div class="m-grid__item m-grid__item--fluid m-wrapper">
					@yield('subheader')											
						<div class="m-content">
							<!--begin:: Widgets/Stats-->
                            @yield('main_content')
							<!--end:: Widgets/Stats-->
						</div>
					</div>
				</div>
			</div>
			<!-- end::Body -->
			<!-- begin::Footer -->
			<footer class="m-grid__item m-footer ">
				<div class="m-container m-container--responsive m-container--xxl m-container--full-height m-page__container">
					<div class="m-footer__wrapper">
						<div class="m-stack m-stack--flex-tablet-and-mobile m-stack--ver m-stack--desktop">
							<div class="m-stack__item m-stack__item--left m-stack__item--middle m-stack__item--last">
								<span class="m-footer__copyright">
								<?php echo date('Y'); ?>  &copy; PE System by
									<a href="#" class="m-link">
										HashRoot
									</a>
								</span>
							</div>
							<div class="m-stack__item m-stack__item--right m-stack__item--middle m-stack__item--first">
								<ul class="m-footer__nav m-nav m-nav--inline m--pull-right">
									<li class="m-nav__item">
										<a href="#" class="m-nav__link">
											<span class="m-nav__link-text">
											Autowelkin One
											</span>
										</a>
									</li>
									<li class="m-nav__item m-nav__item--last">
										<a href="#" class="m-nav__link" data-toggle="m-tooltip" title="Support Center" data-placement="left">
											<i class="m-nav__link-icon flaticon-diagram m--icon-font-size-lg3"></i>
										</a>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</footer>
			<!-- end::Footer -->
		</div>
		@yield('modaldiv')

		<!-- begin::Scroll Top -->
		<div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
			<i class="la la-arrow-up"></i>
		</div>
		<script src="{{asset('assets/vendor/base/vendors.bundle.js')}}" type="text/javascript"></script>
		<script src="{{asset('assets/vendor/base/scripts.bundle.js')}}" type="text/javascript"></script>
		
		<script src="{{asset('assets/PeJs/typeahead.js')}}" type="text/javascript"></script>
		<script src="{{asset('assets/PeJs/dropdown.js')}}" type="text/javascript"></script>
		<script src="{{asset('assets/PeJs/bootstrap-datepicker.js')}}" type="text/javascript"></script>
		<script src="{{asset('assets/PeJs/bootstrap-datetimepicker.js')}}" type="text/javascript"></script>
		<script src="{{asset('assets/PeJs/form-controls.js')}}" type="text/javascript"></script>
		<link href="{{asset('assets/select2/dist/js/select2.full.min.js')}}">
		<script src="{{asset('assets/PeJs/chat-widget.js')}}" type="text/javascript"></script>
		<script src="{{asset('assets/summernote/summernote.js')}}"></script>
		<script src="{{asset('assets/PeJs/custom.js')}}" type="text/javascript"></script>
		<!--end::Base Scripts -->

		<script type="text/javascript">
			$('.select2').select2();
			var APP_URL = {!! json_encode(url('/admin')) !!};
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
			    saveToken();
			});
		</script>

		<script>
			  $('#exam_form').ajaxForm({
					dataType:'json', 
						success: function(response, status, xhr, $form){
							console.log(response);
							if(response.status == true){
							
								$("#exam_model").modal('toggle');
								$.notify({
									title: '<strong>Success!</strong>',
									message:response.message
								},{
									type: 'success',
									z_index: 10000,
								});
								
								$('#examiner').val(null);
								$('#examiner').trigger('change');
								$('#exam_form')[0].reset();

							}else{
								$.notify({
									title: '<strong>Failed!</strong>',
									message:response.message
								},{
									type: 'danger',
									z_index: 10000,
								});
							}
						}
				});
		</script>
            @yield('js_after')
	</body>
	<!-- end::Body -->
</html>
