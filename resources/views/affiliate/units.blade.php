@extends('layouts.affiliate')
@section('title', 'Manajemen Unit')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/admin/css/units.css') }}">
<style>
    /* Affiliate layout overrides */
    .unit-page { padding: 24px; }
</style>
@endpush

@section('content')
@include('partials.unit-summary-content')
@endsection

