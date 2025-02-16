@if (!isset($objectives) || count($objectives) == 0)
	<tr>
		<td colspan="15" style="font-weight: bold; word-wrap: break-word;">
			&nbsp;
		</td>
	</tr>
@else
	@php
		\Log::info('Exporting objectives: ' . $objectives);
	@endphp
	<tr>
		<td colspan="15" style="font-weight: bold; word-wrap: break-word;" align="center">
			QUALITY OBJECTIVES
		</td>
	</tr>
	{{-- HEADERS --}}
	<tr>
		<td rowspan="2" align="right">
			#
		</td>
		<td rowspan="2" colspan="3" style="font-weight: bold; word-wrap: break-word;" align="center">
			Particulars
		</td>
		<td rowspan="1" colspan="2" style="font-weight: bold; word-wrap: break-word;" align="center">
			1st Quarter
		</td>
		<td rowspan="1" colspan="2" style="font-weight: bold; word-wrap: break-word;" align="center">
			2nd Quarter
		</td>
		<td rowspan="1" colspan="2" style="font-weight: bold; word-wrap: break-word;" align="center">
			3rd Quarter
		</td>
		<td rowspan="1" colspan="2" style="font-weight: bold; word-wrap: break-word;" align="center">
			4th Quarter
		</td>
		<td rowspan="1" colspan="2" style="font-weight: bold; word-wrap: break-word;" align="center">
			Total
		</td>
		<td rowspan="2" colspan="1" style="width:130px; font-weight: bold; word-wrap: break-word;" align="center">
			% Accomplishment
		</td>
	</tr>
	<tr>
		@for ($i = 1; $i <= 5; $i++)
			<td style="height: 26px; font-weight: bold; word-wrap: break-word;" align="center">
				Target
			</td>
			<td style="height: 26px; width:120px; font-weight: bold; word-wrap: break-word;" align="center">
				Accomplishment
			</td>
		@endfor
	</tr>

	{{-- DATA --}}
	@foreach ($objectives as $objective)
		@php
			$totalTarget = 0;
			$totalAccomplishment = 0;
			$percentage = 0;
		@endphp
		<tr>
			<td align="right">
				{{ $loop->iteration }}
			</td>
			<td colspan="3" style="word-wrap: break-word;">
				{{ $objective['title'] }}
			</td>
			@isset($objective['quarters'])
				@foreach ($objective['quarters'] as $quarter)
					@php
						$totalTarget += $quarter['target'];
						$totalAccomplishment += $quarter['accomplishment'];
					@endphp

					<td style="word-wrap: break-word;" align="center">
						{{ $quarter['target'] }}
					</td>
					<td style="word-wrap: break-word;" align="center">
						{{ $quarter['accomplishment'] }}
					</td>
				@endforeach
				<td style="word-wrap: break-word;" align="center">
					{{ $totalTarget }}
				</td>
				<td style="word-wrap: break-word;" align="center">
					{{ $totalAccomplishment }}
				</td>
				@if ($totalTarget > 0)
					@php
						$percentage = round(($totalAccomplishment / $totalTarget) * 100, 2);
					@endphp
				@endif
				<td style="word-wrap: break-word;" align="center">
					{{ $percentage }}%
				</td>
			@endisset
		</tr>
	@endforeach



@endif
