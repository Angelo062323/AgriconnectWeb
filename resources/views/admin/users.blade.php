@extends('admin.layout')

@section('title', 'AgriConnect - Users Management')

@section('header')
	<h1>Users Management</h1>
	<a href="{{ route('sys-admin.logout') }}">Logout</a>
@endsection

@section('content')
	@if (session('status'))
		<div class="alert-success">{{ session('status') }}</div>
	@endif

	<div class="card card-table">
		<div class="list-toolbar">
			<div class="list-toolbar-left">
				<h2>All Users</h2>
				<form method="GET" class="user-filter-row">
					<div class="user-filter-main">
						<div class="user-search-wrapper">
							<span class="user-search-icon">🔍</span>
							<input type="text" name="search" placeholder="Search name or email" value="{{ request('search') }}" class="user-search-input">
						</div>
					</div>
					<div class="user-filter-actions">
						<select name="role">
							<option value="">All Roles</option>
							@foreach ($roles as $role)
								<option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
							@endforeach
						</select>
						<select name="status">
							<option value="">All Statuses</option>
							@foreach ($statuses as $status)
								<option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
							@endforeach
						</select>
						<button type="submit" class="btn-secondary">Filters</button>
					</div>
				</form>
			</div>
			<div class="list-toolbar-actions">
				<a href="{{ route('sys-admin.users.create') }}" class="btn-primary" style="display:inline-flex;align-items:center;justify-content:center;text-decoration:none;">+ Add New User</a>
			</div>
		</div>

		@if ($users->isEmpty())
			<p class="muted">No users found.</p>
		@else
			<table class="table users-table">
				<thead>
					<tr>
						<th><input type="checkbox"></th>
						<th>Name</th>
						<th>Role</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($users as $user)
						<tr>
							<td><input type="checkbox"></td>
							<td>
								<div class="user-name-cell">
									<div class="user-avatar-placeholder">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
									<div>
										<div class="user-name">{{ $user->name }}</div>
										<div class="user-email">{{ $user->email }}</div>
									</div>
								</div>
							</td>
							<td>
								<span class="badge-role badge-role-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
							</td>
							<td>
								<span class="status-pill status-{{ $user->status }}">
									<span class="status-dot"></span>
									{{ ucfirst($user->status) }}
								</span>
							</td>
							<td>
								<div class="user-actions">
									<form method="POST" action="{{ route('sys-admin.users.reset-password', $user) }}">
										@csrf
										<button type="submit" class="btn-secondary btn-sm">Reset Password</button>
									</form>
									<form method="POST" action="{{ route('sys-admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user?');">
										@csrf
										@method('DELETE')
										<button type="submit" class="btn-danger btn-sm">Delete</button>
									</form>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>

			<div class="pagination-wrapper">
				{{ $users->links() }}
			</div>
		@endif
	</div>
@endsection

@section('footer')
	<p>&copy; {{ date('Y') }} AgriConnect</p>
@endsection
