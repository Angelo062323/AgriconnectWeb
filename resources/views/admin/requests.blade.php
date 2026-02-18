@extends('admin.layout')

@section('title', 'AgriConnect - Requests Management')

@section('header')
	<h1>Requests Management</h1>
	<a href="{{ route('sys-admin.logout') }}">Logout</a>
@endsection

@section('content')
	<div class="card card-table">
		<div class="list-toolbar">
			<div class="list-toolbar-left">
				<h2>Requests List</h2>
			</div>
		</div>

		@if ($requests->isEmpty())
			<p class="muted">No requests have been recorded yet.</p>
		@else
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Date</th>
						<th>Farmer</th>
						<th>LGU</th>
						<th>Type</th>
						<th>Priority</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($requests as $index => $req)
						<tr>
							<td>{{ $requests->firstItem() + $index }}</td>
							<td>{{ $req->created_at?->format('Y-m-d') }}</td>
							<td>
								@if ($req->farmer)
									{{ $req->farmer->last_name }}, {{ $req->farmer->first_name }}
								@else
									—
								@endif
							</td>
							<td>
								@if ($req->lgu)
									{{ $req->lgu->lgu_name }} ({{ $req->lgu->municipality }})
								@else
									—
								@endif
							</td>
							<td>{{ ucfirst(str_replace('_', ' ', $req->request_type)) }}</td>
							<td>{{ ucfirst($req->priority) }}</td>
							<td>{{ ucfirst(str_replace('_', ' ', $req->status)) }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>

			<div class="pagination-wrapper">
				{{ $requests->links() }}
			</div>
		@endif
	</div>
@endsection

@section('footer')
	<p>&copy; {{ date('Y') }} AgriConnect</p>
@endsection
