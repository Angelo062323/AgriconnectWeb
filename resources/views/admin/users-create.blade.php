@extends('admin.layout')

@section('title', 'AgriConnect - Create User')

@section('header')
	<h1>Create User</h1>
	<a href="{{ route('sys-admin.logout') }}">Logout</a>
@endsection

@section('content')
	@if ($errors->any())
		<div class="alert-success" style="background:#fef2f2;border-color:#fecaca;color:#b91c1c;animation:none;position:static;">
			<ul style="margin:0;padding-left:1.2rem;">
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

	<div class="card">
		<h2>New User</h2>
		<form method="POST" action="{{ route('sys-admin.users.store') }}" class="form-vertical" style="max-width:420px;">
			@csrf

			<div class="form-group">
				<label for="name">Name</label>
				<input type="text" id="name" name="name" value="{{ old('name') }}" required>
			</div>

			<div class="form-group">
				<label for="email">Email</label>
				<input type="email" id="email" name="email" value="{{ old('email') }}" required>
			</div>

			<div class="form-group">
				<label for="role">Role</label>
				<select id="role" name="role" required>
					@foreach ($roles as $role)
						<option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label for="status">Status</label>
				<select id="status" name="status" required>
					@foreach ($statuses as $status)
						<option value="{{ $status }}" {{ old('status', 'active') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group">
				<label for="password">Password (optional)</label>
				<input type="password" id="password" name="password" placeholder="Leave blank to auto-generate">
			</div>

			<div class="form-actions">
				<button type="submit">Create User</button>
			</div>
		</form>
	</div>
@endsection

@section('footer')
	<p>&copy; {{ date('Y') }} AgriConnect</p>
@endsection
