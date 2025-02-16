@extends('layout.index')

@section('title', $title ?? 'Sectors')

@section('content')
	<h1>{{ $sector['name'] }}</h1>
	<p>{{ $sector['description'] }}</p>
	<p>Date: {{ $date }}</p>

	<div class="page-break"></div>

@endsection
