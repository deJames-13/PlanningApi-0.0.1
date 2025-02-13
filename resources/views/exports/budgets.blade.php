<table>
	<thead>
		<tr>
			<th>Title</th>
			<th>Description</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($data as $example)
			<tr>
				<td>{{ $example['title'] }}</td>
				<td>{{ $example['description'] }}</td>
			</tr>
		@endforeach
	</tbody>
</table>
