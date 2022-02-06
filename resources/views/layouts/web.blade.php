<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title')</title>

	{{-- <meta name="robots" content="index,follow" />
	<meta property="og:url" content="{{ url()->current() }}" />
	<meta property="og:type" content="@yield('ogtype', 'website')" />
	<meta property="og:title" content="@yield('title')" />
	<meta property="og:description" content="@yield('ogdescription', 'Texto descriptivo de la página.')" />
	<meta property="og:image" content="@yield('ogimage', asset('/web/img/logo.png'))" />
	<meta name="description" content="@yield('ogdescription', 'Texto descriptivo de la página.')">
	<meta name="twitter:card" content="summary" />
	<meta name="twitter:site" content="" />
	<meta name="twitter:creator" content="" /> --}}

	<link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" />

	<!-- Font Awesome -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link rel="stylesheet" href="{{ asset('/web/css/fontawesome/all.min.css') }}">
	<!-- Bootstrap core CSS -->
	<link href="{{ asset('/web/css/bootstrap.css') }}" rel="stylesheet">
	
	@yield('links')

	<!-- Style CSS -->
	<link href="{{ asset('/web/css/style.css') }}" rel="stylesheet">
	<!-- Style CSS -->
</head>
<body class="goto-here bg-white">

	@include('web.partials.navbar')

	@yield('content')

	@include('web.partials.footer')

	@if(!session()->has('user'))
	@include('web.partials.login')
	{{-- @include('web.partials.register') --}}
	@include('web.partials.recovery')
	@include('web.partials.terms')
	@endif
	
	@include('web.partials.loader')

	<!-- JQuery -->
	<script type="text/javascript" src="{{ asset('/web/js/jquery-3.4.1.min.js') }}"></script>
	<!-- Bootstrap tooltips -->
	<script type="text/javascript" src="{{ asset('/web/js/popper.min.js') }}"></script>
	<!-- Bootstrap core JavaScript -->
	<script type="text/javascript" src="{{ asset('/web/js/bootstrap.min.js') }}"></script>

	@yield('scripts')

	<!-- Scripts -->
	<script src="{{ asset('/admins/vendor/validate/jquery.validate.js') }}"></script>
	<script src="{{ asset('/admins/vendor/validate/additional-methods.js') }}"></script>
	<script src="{{ asset('/admins/vendor/validate/messages_es.js') }}"></script>
	<script src="{{ asset('/admins/js/validate.js') }}"></script>
	<script type="text/javascript" src="{{ asset('/web/js/script.js') }}"></script>
	@include('admin.partials.notifications')
	@if(!session()->has('user'))
	@if(!is_null(old('name')) && !is_null(old('lastname')) && !is_null(old('email')) || session('error.register'))
	<script type="text/javascript">
		$('#modal-register').modal('show');
	</script>
	@elseif(!is_null(old('email')) || session('error.login'))
	<script type="text/javascript">
		$('#modal-login').modal('show');
	</script>
	@elseif(!is_null(old('recovery')) || session('error.recovery') || session('success.recovery'))
	<script type="text/javascript">
		$('#modal-recovery').modal('show');
	</script>
	@endif
	@endif
</body>
</html>