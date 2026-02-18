@extends('admin.layout')

@section('title', 'AgriConnect - Reports Management')

@section('header')
	<h1>Reports Management</h1>
	<a href="{{ route('sys-admin.logout') }}">Logout</a>
@endsection

@section('content')
	<div class="card card-table">
		<div class="list-toolbar">
			<div class="list-toolbar-left">
				<h2>Reports List</h2>
			</div>
		</div>

		@if ($reports->isEmpty())
			<p class="muted">No reports have been recorded yet.</p>
		@else
			<table class="table">
				<thead>
					<tr>
						<th>#</th>
						<th>Date</th>
						<th>Farmer</th>
						<th>LGU</th>
						<th>Type</th>
						<th>Severity</th>
						<th>Status</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($reports as $index => $report)
						<tr>
							<td>{{ $reports->firstItem() + $index }}</td>
							<td>{{ $report->created_at?->format('Y-m-d') }}</td>
							<td>
								@if ($report->farmer)
									{{ $report->farmer->last_name }}, {{ $report->farmer->first_name }}
								@else
									—
								@endif
							</td>
							<td>
								@if ($report->lgu)
									{{ $report->lgu->lgu_name }} ({{ $report->lgu->municipality }})
								@else
									—
								@endif
							</td>
							<td>{{ $report->report_type }}</td>
							<td>{{ ucfirst($report->severity) }}</td>
							<td>{{ ucfirst(str_replace('_', ' ', $report->status)) }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>

			<div class="pagination-wrapper">
				{{ $reports->links() }}
			</div>
		@endif
	</div>
@endsection

@section('footer')
	<p>&copy; {{ date('Y') }} AgriConnect</p>
@endsection
