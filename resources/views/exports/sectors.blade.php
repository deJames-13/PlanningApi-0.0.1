@php
	// \Log::info('Exporting sectors: ' . $data);
@endphp

<table>
	<tbody>
		@foreach ($data as $sector)
			<tr>
				<td colspan="15" style="font-weight: bold; word-wrap: break-word;">
					{{ $sector['name'] }}
				</td>
			</tr>
			@if ($sector['description'])
				<tr>
					<td colspan="15" style="word-wrap: break-word;">
						{{ $sector['description'] }}
					</td>
				</tr>
			@endif
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>
			@include('exports.components.budget-table', ['budgets' => $sector['budgets']])
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>

			@include('exports.components.objectives-table', ['objectives' => $sector['objectives']])
			<tr>
				<td>
					&nbsp;
				</td>
			</tr>
		@endforeach
	</tbody>
</table>
