@php
	$years = [];
	$annualValues = [];
	foreach ($data as $row) {
	    if (isset($row['annual'])) {
	        foreach ($row['annual'] as $annual) {
	            $years[] = $annual['year'];
	            $annualValues[] = $annual['allotment'];
	            $annualValues[] = $annual['obligated'];
	            $annualValues[] = $annual['utilization_rate'];
	        }
	    }
	}
	$titleSpan = count($years) * 3;
	$current_year = date('Y');
@endphp

<table>
	<tbody>
		@foreach ($data as $row)
			<tr>
				<td colspan="{{ $titleSpan }}" style="font-weight: bold; word-wrap: break-word;" align="center">
					{{ $row['title'] }}
				</td>
			</tr>
			<tr>
				<td colspan="{{ $titleSpan }}" style="word-wrap: break-word;">
					{{ $row['description'] }}
				</td>
			</tr>
			<tr>
				@foreach ($years as $year)
					<td colspan="3" style="font-weight: bold; word-wrap: break-word;" align="center">
						{{ $year }}
					</td>
				@endforeach
			</tr>
			<tr>
				@foreach ($annualValues as $value)
					<td style="word-wrap: break-word;" align="center">
						{{ ($loop->iteration % 2 == 0 ? 'Obligated' : $loop->iteration % 3 == 0) ? 'Utilization Rate' : 'Allotment' }}
					</td>
				@endforeach
			</tr>
			<tr>
				@foreach ($annualValues as $value)
					<td style="word-wrap: break-word;" align="center">
						{{ $value }}
					</td>
				@endforeach
			</tr>
		@endforeach
	</tbody>
</table>
