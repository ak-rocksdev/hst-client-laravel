<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
	
	<meta property="og:url" content="{{ Request::url() }}" />
	@hasSection('title')
		<meta property="og:title" content="@yield('title')" />
	@endif

	@hasSection('image-preview')
		<meta property="og:image" content="@yield('image-preview')" />
	@endif

	@hasSection('description')
		<meta property="og:description" content="@yield('description')" />
	@endif

	@hasSection('keywords')
		<meta name="keywords" content="@yield('keywords')" />
	@else
		<meta name="keywords" content="Hyper Score Technology, Hyperscore, Scoring System, Scoring, Skateboards, Skateboard, Indonesia, BMX, Scooter, Aggresive, Aggresive Inline, Action Sport">
	@endif
	
	@hasSection('page-type')
		<meta property="og:type" content="@yield('page-type')" />
	@endif

	@hasSection('site-name')
		<meta property="og:site_name" content="@yield('site-name')" />
	@endif

	@hasSection('article-author')
		<meta name="author" content="@yield('article-author')" />
	@endif
	
	<title>@yield('title')</title>

	@hasSection('description')
		<meta name="description" content="@yield('description')" />
	@endif

	<meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="@yield('title')">
    <meta name="twitter:description" content="@yield('description')">
    <meta name="twitter:image" content="@yield('image-preview')">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="googlebot" content="index, follow">
    <link rel="canonical" href="{{ Request::url() }}">
	<meta name="robots" content="max-image-preview:large">

	@hasSection('meta')
		@yield('meta')
	@endif

	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/img/favicon/apple-touch-icon.png') }}">
	<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/img/favicon/favicon-32x32.png') }}">
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon/favicon-16x16.png') }}">
	<link rel="manifest" href="{{ asset('assets/img/favicon/site.webmanifest') }}">
	<link href="{{ asset('css/app.css?v=1.0') }}" rel="stylesheet">

	@yield('extracss')
</head>
<body>
	@include('layout.navbar')
