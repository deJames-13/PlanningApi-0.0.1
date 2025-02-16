<table>
	<tbody>
		@if (isset($data[0]))
			<tr>
				<td colspan="5">
					Particulars
				</td>
				@if (isset($data[0]['particulars'][0]))
					@if (isset($data[0]['particulars'][0]['values']))
						@foreach ($data[0]['particulars'][0]['values'] as $value)
							<td style="width: 110px;">
								Target
							</td>
							<td style="width: 110px;">
								Accomplishment
							</td>
						@endforeach
					@endif
				@endif
			</tr>
			<tr>
				<td colspan="5">
					&nbsp;
				</td>
				@if (isset($data[0]['particulars'][0]))
					@if (isset($data[0]['particulars'][0]['values']))
						@foreach ($data[0]['particulars'][0]['values'] as $value)
							<td colspan="2">
								{{ $value['year'] }}
							</td>
						@endforeach
					@endif
				@endif
			</tr>
		@endif

		@foreach ($data as $row)
			<tr>
				<td colspan="5" style="font-weight: bold; word-wrap: break-word;">
					{{ $row['title'] }}
				</td>
			</tr>
			@if (isset($row['description']))
				<tr rowspan="2">
					<td colspan="5">
						{{ $row['description'] }}
					</td>
				</tr>
			@endif
			<tr>
				<td colspan="5">
					&nbsp;
				</td>
			</tr>

			{{-- particulars --}}
			@if (isset($row['particulars']))
				@foreach ($row['particulars'] as $particular)
					<tr rowspan="2">
						<td colspan="5" style="word-wrap: break-word;">
							{{ $particular['title'] }}
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
				@endforeach
			@endif
		@endforeach
	</tbody>
</table>
