@extends('layouts.affiliate')
@section('title', 'Link Affiliate Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/affiliate/css/link.css') }}">
<style>
a, button, input, select, h1, h2, h3, h4, h5, * {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
    border: none;
    text-decoration: none;
    background: none;
    -webkit-font-smoothing: antialiased;
}
menu, ol, ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
</style>
@endpush

@section('content')
<div class="link-affiliate">
    <div class="link-affiliate-saya">Link Affiliate Saya</div>
    <div class="share-link-ini-untuk-mendapatkan-komisi">
        Share link ini untuk mendapatkan komisi
    </div>
    <img class="subtract" src="{{ asset('assets/affiliate/img/linkimg/subtract0.svg') }}" />
    <div class="link-affiliate-saya2">Link Affiliate saya</div>
    <div class="rectangle-1278"></div>
    <div class="rectangle-12782"></div>
    <div class="copy-link">Copy link</div>
    <div class="tabler-copy">
        <img class="group5" src="{{ asset('assets/affiliate/img/linkimg/group4.svg') }}" />
    </div>
    <div class="rectangle-1279"></div>
    <div class="rectangle-1280"></div>
    <div class="rectangle-1281"></div>
    <div class="qr-code">QR Code</div>
    <div class="informasi-affiliate">Informasi Affiliate</div>
    <div class="share-ke-sosial-media">Share Ke Sosial Media</div>
    <div class="solar-share-linear">
        <img class="group6" src="{{ asset('assets/affiliate/img/linkimg/group5.svg') }}" />
    </div>
    <img class="qr-code2" src="{{ asset('assets/affiliate/img/linkimg/qr-code1.svg') }}" />
    <img class="whats-app" src="{{ asset('assets/affiliate/img/linkimg/whats-app0.png') }}" />
    <img class="facebook" src="{{ asset('assets/affiliate/img/linkimg/facebook0.png') }}" />
    <img class="x" src="{{ asset('assets/affiliate/img/linkimg/x0.png') }}" />
    <img class="telegram-app" src="{{ asset('assets/affiliate/img/linkimg/telegram-app0.png') }}" />
    <div class="kode-affiliate">Kode affiliate:</div>
    <div class="zahra">{{ auth()->user()->name ?? 'Affiliate' }}</div>
    <div class="status">Status:</div>
    <div class="aktif">Aktif</div>
    <div class="bergabung-sejak">Bergabung sejak:<br /></div>
    <div class="_20-januari-2026">20 Januari 2026</div>
    <div class="total-klik-link">Total klik link:<br /></div>
    <div class="_52-link">52 link</div>
</div>
@endsection
