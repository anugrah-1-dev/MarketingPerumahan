@extends('layouts.manager')
@section('title', 'Manajemen Unit')
@section('page-title', 'Manajemen Unit')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/manager/css/units.css') }}">
@endpush

@section('content')
@include('partials.unit-summary-content')
@endsection
