@extends('layouts.pdf')

@section('title', $title ?? 'Sectors')

@section('content')

	@php
		\Log::info('Sectors PDF view: \n' . print_r($sector['budgets'], true));
	@endphp

	<div class="container">
		<span class="fw-bold fs-6">{{ $sector['name'] }}</span>
		<p>{{ $sector['description'] }}</p>
		<p>Date: {{ $date }}</p>

		<hr>

		<span class="fw-bold fs-4">Budgets</span>
		@foreach ($sector['budgets'] as $row)
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Title</th>
						<th>Description</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>{{ $row['title'] }}</td>
						<td>{{ $row['description'] }}</td>
					</tr>
				</tbody>
			</table>

			<span class="fw-bold">
				Annual
			</span>
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Year</th>
						<th>Allotment</th>
						<th>Obligated</th>
						<th>Utilization</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($row['annual'] as $annual)
						<tr>
							<td class="fw-bold">{{ $annual['year'] }}</td>
							<td>{{ $annual['allotment'] }}</td>
							<td>{{ $annual['obligated'] }}</td>
							<td>{{ $annual['utilization_rate'] . '%' ?? 'N/A' }}</td>
						</tr>
						<tr>
							<td colspan="4">
								<table class="table table-striped">
									<thead>
										<tr>
											<th>Quarter</th>
											<th>Allotment</th>
											<th>Obligated</th>
											<th>Utilization</th>
										</tr>
									</thead>
									<tbody>
										@foreach ($annual['quarters'] as $quarter)
											<tr>
												<td>{{ $quarter['quarter'] }}</td>
												<td>{{ $quarter['allotment'] }}</td>
												<td>{{ $quarter['obligated'] }}</td>
												<td>{{ $quarter['utilization_rate'] . '%' ?? 'N/A' }}</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		@endforeach
		<hr>
		<span class="fw-bold fs-4">Objectives</span>
		<table class="table table-striped">
			<thead>
				<tr>
					<th>Particular</th>
					<th>Target</th>
					<th>Accomplishment</th>
					<th>Percentage</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($sector['objectives'] as $objective)
					<tr>
						<td>{{ $objective['title'] }}</td>
						<td>{{ $objective['target'] }}</td>
						<td>{{ $objective['accomplishment'] }}</td>
						<td>{{ $objective['percentage'] . '%' ?? 'N/A' }}</td>
					</tr>
					<tr>
						<td colspan="4">
							<table class="table table-bordered">
								<thead>
									<tr>
										<th>Quarter</th>
										<th>Target</th>
										<th>Accomplishment</th>
										<th>Percentage</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($objective['quarters'] as $quarter)
										<tr>
											<td>{{ $quarter['quarter'] }}</td>
											<td>{{ $quarter['target'] }}</td>
											<td>{{ $quarter['accomplishment'] }}</td>
											<td>{{ $quarter['percentage'] . '%' ?? 'N/A' }}</td>
										</tr>
									@endforeach
								</tbody>
							</table>

						</td>
					</tr>
				@endforeach
		</table>
	</div>

@endsection
