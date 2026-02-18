@extends('admin.layout')

@section('title', 'AgriConnect - Admin Dashboard')

@section('header')
	<h1>Admin Dashboard</h1>
	<div class="header-actions">
		<button type="button" class="btn-ghost" id="theme-toggle" aria-label="Toggle dark mode">
			<span class="theme-toggle-icon">🌙</span>
		</button>
		<a href="{{ route('sys-admin.logout') }}">Logout</a>
	</div>
@endsection

@section('content')
	<div class="dashboard-welcome">
		<h2>Welcome to the Admin Dashboard</h2>
		<p>You can customize this section later.</p>
	</div>
@endsection

@section('footer')
	<p>&copy; {{ date('Y') }} AgriConnect</p>
@endsection
