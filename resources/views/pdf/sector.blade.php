@extends('layouts.pdf')

@section('title', $title)

@section('content')
    <h1>{{ $title }}</h1>
    <p>{{ $content }}</p>
    <p>Date: {{ $date }}</p>

    <div class="page-break"></div>

    <h2>Additional Content</h2>
    <p>This is some additional content that will appear on a new page.</p>
@endsection