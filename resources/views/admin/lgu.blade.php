@extends('admin.layout')

@section('title', 'AgriConnect - LGU Management')

@section('header')
	<h1>Local Government Unit (LGU) Management</h1>
	<a href="{{ route('sys-admin.logout') }}">Logout</a>
@endsection

@section('content')
	@if (session('status'))
		<div class="alert-success">{{ session('status') }}</div>
	@endif

	{{-- LGU modal --}}
	<div class="modal" id="lgu-modal" aria-hidden="true">
		<div class="modal-backdrop" data-modal-close="lgu-modal"></div>
		<div class="modal-dialog">
			<div class="card modal-card">
				<div class="modal-header">
					<h2 id="lgu-modal-title">New LGU</h2>
					<button type="button" class="modal-close" data-modal-close="lgu-modal">&times;</button>
				</div>
				<form id="lgu-form" action="{{ route('sys-admin.lgu.store') }}" method="POST" class="form-vertical" data-store-url="{{ route('sys-admin.lgu.store') }}">
					@csrf
					<input type="hidden" name="_method" id="lgu_form_method" value="POST">
					<input type="hidden" name="id" id="lgu_id_field">

					<div class="form-group">
						<label for="lgu_name">LGU Name</label>
						<input type="text" id="lgu_name" name="lgu_name" value="{{ old('lgu_name') }}" required>
						@error('lgu_name')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="municipality">Municipality</label>
						<select id="municipality" name="municipality" required>
							<option value="" disabled {{ old('municipality') ? '' : 'selected' }}>Select municipality</option>
							@foreach ($municipalities as $municipality)
								<option value="{{ $municipality }}" {{ old('municipality') === $municipality ? 'selected' : '' }}>{{ $municipality }}</option>
							@endforeach
						</select>
						@error('municipality')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="province">Province</label>
						<input type="text" id="province" name="province" value="{{ old('province', 'Oriental Mindoro') }}" required>
						@error('province')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="contact_email">Contact Email (optional)</label>
						<input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email') }}">
						@error('contact_email')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-actions">
						<button type="submit" id="lgu-submit-btn">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="card card-table">
		<div class="list-toolbar">
			<div class="list-toolbar-left">
				<h2>Existing LGUs</h2>
				<form method="GET" class="filter-row">
					<select name="municipality">
						<option value="">All Municipalities</option>
						@foreach ($municipalities as $municipality)
							<option value="{{ $municipality }}" {{ request('municipality') === $municipality ? 'selected' : '' }}>{{ $municipality }}</option>
						@endforeach
					</select>
					<input type="text" name="search" placeholder="Search LGU" value="{{ request('search') }}">
					<button type="submit" class="btn-secondary">Filter</button>
				</form>
			</div>
			<div class="list-toolbar-actions">
				<button type="button" class="btn-primary" id="lgu-new-btn">New</button>
			</div>
		</div>
		@if ($lgus->isEmpty())
			<p class="muted">No LGUs have been added yet.</p>
		@else
			<form id="lgu-table-form" method="POST" action="{{ route('sys-admin.lgu.bulk-delete') }}">
				@csrf
				@method('DELETE')
				<table class="table">
					<thead>
						<tr>
							<th><input type="checkbox" id="lgu-select-all"></th>
							<th>#</th>
							<th>LGU Name</th>
							<th>Municipality</th>
							<th>Province</th>
							<th>Contact Email</th>
							<th>Created At</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($lgus as $index => $lgu)
							<tr
								data-id="{{ $lgu->id }}"
								data-update-url="{{ route('sys-admin.lgu.update', $lgu) }}"
								data-lgu-name="{{ $lgu->lgu_name }}"
								data-municipality="{{ $lgu->municipality }}"
								data-province="{{ $lgu->province }}"
								data-contact-email="{{ $lgu->contact_email }}"
							>
								<td><input type="checkbox" class="lgu-row-checkbox" name="ids[]" value="{{ $lgu->id }}"></td>
								<td>{{ $index + 1 }}</td>
								<td>{{ $lgu->lgu_name }}</td>
								<td>{{ $lgu->municipality }}</td>
								<td>{{ $lgu->province }}</td>
								<td>{{ $lgu->contact_email ?? '—' }}</td>
								<td>{{ $lgu->created_at?->format('Y-m-d') }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				<div class="table-actions">
					<button type="button" class="btn-secondary" id="lgu-edit-selected">Edit Selected</button>
					<button type="submit" class="btn-danger" id="lgu-delete-selected">Delete Selected</button>
				</div>
			</form>

			<div class="pagination-wrapper">
				{{ $lgus->links() }}
			</div>
		@endif
	</div>
@endsection

@section('footer')
	<p>&copy; {{ date('Y') }} AgriConnect</p>
@endsection
