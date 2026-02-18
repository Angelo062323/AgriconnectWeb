@extends('admin.layout')

@section('title', 'AgriConnect - Settings')

@section('header')
	<h1>Settings</h1>
	<a href="{{ route('sys-admin.logout') }}">Logout</a>
@endsection

@section('content')
	<div class="dashboard-welcome">
		<h2>Welcome to the Admin Settings Section</h2>
		<p>You can customize this section later.</p>
	</div>
@endsection

@section('footer')
	<p>&copy; {{ date('Y') }} AgriConnect</p>
@endsection
