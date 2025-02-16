@php
	// \Log::info('Data: ' . print_r(count($data), true));
@endphp

<table>
	<tbody>
		@foreach ($data as $d)
			<tr>
				<td width="300px"
					style="{{ isset($d['sectors']) && !empty($d['sectors']) ? 'background-color: #000000; color: #ffffff; font-weight: bold;' : 'font-weight: bold;' }}">
					{{ $d['name'] }}
				</td>
			</tr>
			@if (isset($d['sectors']))
				@foreach ($d['sectors'] as $s)
					<tr>
						<td width="300px">{{ $s['name'] }}</td>
					</tr>
				@endforeach
			@endif
		@endforeach

	</tbody>
</table>
