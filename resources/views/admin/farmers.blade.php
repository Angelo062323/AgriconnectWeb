@extends('admin.layout')

@section('title', 'AgriConnect - Farmers Management')

@section('header')
	<h1>Farmers Management</h1>
	<a href="{{ route('sys-admin.logout') }}">Logout</a>
@endsection

@section('content')
	@if (session('status'))
		<div class="alert-success">{{ session('status') }}</div>
	@endif

	{{-- Farmer modal --}}
	<div class="modal" id="farmer-modal" aria-hidden="true">
		<div class="modal-backdrop" data-modal-close="farmer-modal"></div>
		<div class="modal-dialog">
			<div class="card modal-card">
				<div class="modal-header">
					<h2 id="farmer-modal-title">Register Farmer</h2>
					<button type="button" class="modal-close" data-modal-close="farmer-modal">&times;</button>
				</div>
				<form id="farmer-form" action="{{ route('sys-admin.farmers.store') }}" method="POST" class="form-vertical" data-store-url="{{ route('sys-admin.farmers.store') }}">
					@csrf
					<input type="hidden" name="_method" id="farmer_form_method" value="POST">
					<input type="hidden" name="id" id="farmer_id_field">

					<div class="form-group">
						<label for="lgu_id">LGU</label>
						<select id="lgu_id" name="lgu_id" required>
							<option value="" disabled {{ old('lgu_id') ? '' : 'selected' }}>Select LGU</option>
							@foreach ($lgus as $lgu)
								<option value="{{ $lgu->id }}" data-municipality="{{ $lgu->municipality }}" {{ (int) old('lgu_id') === $lgu->id ? 'selected' : '' }}>
									{{ $lgu->lgu_name }} ({{ $lgu->municipality }})
								</option>
							@endforeach
						</select>
						@error('lgu_id')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="rsbsa_number">RSBSA Number</label>
						<input
							type="text"
							id="rsbsa_number"
							name="rsbsa_number"
							value="{{ old('rsbsa_number') }}"
							placeholder="00-00-00-000-000000"
							pattern="\d{2}-\d{2}-\d{2}-\d{3}-\d{6}"
							title="Format: 00-00-00-000-000000"
							required
						>
						@error('rsbsa_number')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="first_name">First Name</label>
						<input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
						@error('first_name')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="last_name">Last Name</label>
						<input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
						@error('last_name')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="contact_number">Contact Number</label>
						<input type="text" id="contact_number" name="contact_number" value="{{ old('contact_number') }}">
						@error('contact_number')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="email">Email (optional)</label>
						<input type="email" id="email" name="email" value="{{ old('email') }}">
						@error('email')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="crop_type">Crop Type</label>
						<select id="crop_type" name="crop_type">
							<option value="">Select crop</option>
							@php
								$crops = ['Rice', 'Corn', 'Coconut', 'Banana', 'Vegetables', 'Fruit Trees', 'Others'];
							@endphp
							@foreach ($crops as $crop)
								<option value="{{ $crop }}" {{ old('crop_type') === $crop ? 'selected' : '' }}>{{ $crop }}</option>
							@endforeach
						</select>
						@error('crop_type')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="farm_location">Farm Location (description)</label>
						<input type="text" id="farm_location" name="farm_location" value="{{ old('farm_location') }}">
						@error('farm_location')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="barangay">Barangay</label>
						<input type="text" id="barangay" name="barangay" value="{{ old('barangay') }}" required>
						@error('barangay')
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
						<label for="latitude">Latitude (optional)</label>
						<input type="text" id="latitude" name="latitude" value="{{ old('latitude') }}">
						@error('latitude')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-group">
						<label for="longitude">Longitude (optional)</label>
						<input type="text" id="longitude" name="longitude" value="{{ old('longitude') }}">
						@error('longitude')
							<div class="form-error">{{ $message }}</div>
						@enderror
					</div>

					<div class="form-actions">
						<button type="submit" id="farmer-submit-btn">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="card card-table">
		<div class="list-toolbar">
			<div class="list-toolbar-left">
				<h2>Registered Farmers</h2>
				<form method="GET" class="filter-row">
					<select name="municipality">
						<option value="">All Municipalities</option>
						@foreach ($municipalities as $municipality)
							<option value="{{ $municipality }}" {{ request('municipality') === $municipality ? 'selected' : '' }}>{{ $municipality }}</option>
						@endforeach
					</select>
					<select name="lgu_id">
						<option value="">All LGUs</option>
						@foreach ($lgus as $lgu)
							<option value="{{ $lgu->id }}" {{ (string) request('lgu_id') === (string) $lgu->id ? 'selected' : '' }}>
								{{ $lgu->lgu_name }} ({{ $lgu->municipality }})
							</option>
						@endforeach
					</select>
					<input type="text" name="search" placeholder="Search name/RSBSA" value="{{ request('search') }}">
					<button type="submit" class="btn-secondary">Filter</button>
				</form>
			</div>
			<div class="list-toolbar-actions">
				<button type="button" class="btn-primary" id="farmer-new-btn">New</button>
			</div>
		</div>
		@if ($farmers->isEmpty())
			<p class="muted">No farmers have been registered yet.</p>
		@else
			<form id="farmer-table-form" method="POST" action="{{ route('sys-admin.farmers.bulk-delete') }}">
				@csrf
				@method('DELETE')
				<table class="table">
					<thead>
						<tr>
							<th><input type="checkbox" id="farmer-select-all"></th>
							<th>#</th>
							<th>RSBSA</th>
							<th>Name</th>
							<th>LGU</th>
							<th>Barangay</th>
							<th>Municipality</th>
							<th>Province</th>
							<th>Crop</th>
							<th>Contact</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($farmers as $index => $farmer)
							<tr
								data-id="{{ $farmer->id }}"
								data-update-url="{{ route('sys-admin.farmers.update', $farmer) }}"
								data-lgu-id="{{ $farmer->lgu_id }}"
								data-rsbsa="{{ $farmer->rsbsa_number }}"
								data-first-name="{{ $farmer->first_name }}"
								data-last-name="{{ $farmer->last_name }}"
								data-contact-number="{{ $farmer->contact_number }}"
								data-email="{{ $farmer->email }}"
								data-crop-type="{{ $farmer->crop_type }}"
								data-farm-location="{{ $farmer->farm_location }}"
								data-barangay="{{ $farmer->barangay }}"
								data-municipality="{{ $farmer->municipality }}"
								data-province="{{ $farmer->province }}"
								data-latitude="{{ $farmer->latitude }}"
								data-longitude="{{ $farmer->longitude }}"
							>
								<td><input type="checkbox" class="farmer-row-checkbox" name="ids[]" value="{{ $farmer->id }}"></td>
								<td>{{ $index + 1 }}</td>
								<td>{{ $farmer->rsbsa_number }}</td>
								<td>{{ $farmer->last_name }}, {{ $farmer->first_name }}</td>
								<td>{{ $farmer->lgu?->lgu_name }} ({{ $farmer->lgu?->municipality }})</td>
								<td>{{ $farmer->barangay }}</td>
								<td>{{ $farmer->municipality }}</td>
								<td>{{ $farmer->province }}</td>
								<td>{{ $farmer->crop_type ?? '—' }}</td>
								<td>{{ $farmer->contact_number ?? '—' }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				<div class="table-actions">
					<button type="button" class="btn-secondary" id="farmer-edit-selected">Edit Selected</button>
					<button type="submit" class="btn-danger" id="farmer-delete-selected">Delete Selected</button>
				</div>
			</form>

			<div class="pagination-wrapper">
				{{ $farmers->links() }}
			</div>
		@endif
	</div>
@endsection

@section('footer')
	<p>&copy; {{ date('Y') }} AgriConnect</p>
@endsection
