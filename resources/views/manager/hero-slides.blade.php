@extends('layouts.manager')

@section('title', 'Hero Slider')
@section('page-title', 'Hero Slider')

@section('content')
<div class="p-6">
    @include('partials.hero-slides-content')
</div>
@endsection
