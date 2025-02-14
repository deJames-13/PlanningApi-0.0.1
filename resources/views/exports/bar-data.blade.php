@php
	\Log::info($data);
@endphp

<table>
	<tbody>
		@foreach ($data as $row)
			<tr>
				<td colspan="5">
					{{ $row['title'] }}
				</td>
			</tr>
			<tr rowspan="2">
				<td colspan="5">
					{{ $row['description'] }}
				</td>
			</tr>
			{{-- particulars --}}
			@if (isset($row['particulars']))
				<tr>
					<td colspan="5">
						Particulars
					</td>
					@foreach ($row['particulars'] as $particular)
						@if (isset($particular['values']))
							@foreach ($particular['values'] as $value)
								<td>
									Target
								</td>
								<td>
									Accomplishment
								</td>
							@endforeach
						@endif
					@endforeach
				</tr>
				@foreach ($row['particulars'] as $particular)
					<tr rowspan="2">
						<td colspan="5">
							{{ $particular['title'] }}
						</td>
						@if (isset($particular['values']))
							@foreach ($particular['values'] as $value)
								<td colspan="2">
									{{ $value['year'] }}
								</td>
							@endforeach
						@endif
					</tr>
					<tr>
						<td colspan="5">
							&nbsp;
						</td>
						@if (isset($particular['values']))
							@foreach ($particular['values'] as $value)
								<td>
									{{ $value['target'] }}
								</td>
								<td>
									{{ $value['accomplishment'] }}
								</td>
							@endforeach
						@endif
					</tr>
					{{-- <tr rowspan="2">
						<td colspan="5">
							{{ $particular['description'] }}
						</td>
					</tr> --}}
				@endforeach
			@endif
		@endforeach
	</tbody>
</table>
