<table>
	<tbody>
		@foreach ($data as $row)
			@php
				$years = [];
				$annualValues = [];
				$currentYearQuarters = [];
				if (isset($row['annual'])) {
				    foreach ($row['annual'] as $annual) {
				        $years[] = $annual['year'];
				        $annualValues[] = $annual['allotment'];
				        $annualValues[] = $annual['obligated'];
				        $annualValues[] = $annual['utilization_rate'] . '%';
				        if ($annual['year'] == end($years)) {
				            $currentYearQuarters = $annual['quarters'];
				        }
				    }
				}
				$currentYear = end($years);
				if (count($years) < 5) {
				    $extraYears = 5 - count($years);
				    $years = array_merge(range($currentYear - 4, $currentYear - count($years)), $years);
				    $annualValues = array_merge(array_fill(0, $extraYears * 3, ''), $annualValues);
				}

				$titleSpan = count($years) * 3;
			@endphp

			<tr>
				<td colspan="{{ $titleSpan }}" style="font-weight: bold; word-wrap: break-word;" align="center">
					{{ $row['title'] }}
				</td>
			</tr>
			@if ($row['description'])
				<tr>
					<td colspan="{{ $titleSpan }}" style="word-wrap: break-word;">
						{{ $row['description'] }}
					</td>
				</tr>
			@endif

			{{-- PAST YEARS --}}
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
						{{ $loop->iteration % 3 == 1 ? 'Allotment' : ($loop->iteration % 3 == 2 ? 'Obligated' : 'Utilization Rate') }}
					</td>
				@endforeach
			</tr>
			<tr>
				@foreach ($annualValues as $value)
					{{-- f loop iteration is the last three values then row span is 4 --}}
					<td @if ($loop->iteration > count($annualValues) - 3) rowspan="4" @endif style="word-wrap: break-word; width: 100px;"
						align="center">
						{{ $value }}
					</td>
				@endforeach
			</tr>
			{{-- CURRENT YEAR QUARTERLY --}}
			<tr>
				<td colspan="12" style="font-weight: bold; word-wrap: break-word;" align="center">
					{{ $currentYear }} Quarterly Budget
				</td>
			</tr>
			<tr>
				<td colspan="3" style="font-weight: bold; word-wrap: break-word;" align="center">Quarter 1</td>
				<td colspan="3" style="font-weight: bold; word-wrap: break-word;" align="center">Quarter 2</td>
				<td colspan="3" style="font-weight: bold; word-wrap: break-word;" align="center">Quarter 3</td>
				<td colspan="3" style="font-weight: bold; word-wrap: break-word;" align="center">Quarter 4</td>
			</tr>

			<tr>
				@foreach ($currentYearQuarters as $quarter)
					<td style="word-wrap: break-word;" align="center">
						{{ $quarter['allotment'] }}
					</td>
					<td style="word-wrap: break-word;" align="center">
						{{ $quarter['obligated'] }}
					</td>
					<td style="word-wrap: break-word;" align="center">
						{{ $quarter['utilization_rate'] }} %
					</td>
				@endforeach
			</tr>

			<tr>
				<td colspan="{{ $titleSpan }}" style="word-wrap: break-word;">&nbsp;</td>
			</tr>
		@endforeach
	</tbody>
</table>
