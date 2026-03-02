@extends('layouts.affiliate')
@section('title', 'Profile & Akun')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/affiliate/css/profile.css') }}">
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
<div class="profile-affiliate">
    <div class="profile-akun">Profile &amp; akun</div>
    <div class="kelola-informasi-akun-dan-pengaturan-anda">
        kelola informasi akun dan pengaturan anda
    </div>
    <div class="rectangle-1306"></div>
    <div class="zahra">{{ auth()->user()->name ?? 'Affiliate' }}</div>
    <div class="rectangle-1307"></div>
    <div class="ellipse-12"></div>
    <img class="solar-user-bold" src="{{ asset('assets/affiliate/img/profileimg/solar-user-bold0.svg') }}" />
    <div class="bergabung-sejak">Bergabung sejak:</div>
    <div class="rectangle-1308"></div>
    <div class="aktif">Aktif</div>
    <div class="kode-affiliate">kode affiliate:</div>
    <div class="_20-januari-2026">20 januari 2026</div>
    <div class="zahra2">{{ auth()->user()->name ?? 'Affiliate' }}</div>
    <div class="informasi-pribadi">Informasi Pribadi</div>
    <div class="nama-lengkap">Nama lengkap</div>
    <div class="email">Email</div>
    <div class="no-telp">No. telp</div>
    <div class="keamanan">Keamanan</div>
    <div class="password">Password</div>
    <div class="ellipse-13"></div>
    <div class="ellipse-132"></div>
    <div class="ellipse-14"></div>
    <div class="ellipse-15"></div>
    <div class="ellipse-16"></div>
    <div class="ellipse-17"></div>
    <img class="tabler-user" src="{{ asset('assets/affiliate/img/profileimg/tabler-user0.svg') }}" />
    <img class="ic-outline-email" src="{{ asset('assets/affiliate/img/profileimg/ic-outline-email0.svg') }}" />
    <img class="mynaui-telephone" src="{{ asset('assets/affiliate/img/profileimg/mynaui-telephone0.svg') }}" />
    <img class="mdi-password-outline" src="{{ asset('assets/affiliate/img/profileimg/mdi-password-outline0.svg') }}" />
    <div class="zahra-nur">{{ auth()->user()->name ?? 'Affiliate' }}</div>
    <div class="zahra-gmail-com">{{ auth()->user()->email ?? 'affiliate@gmail.com' }}</div>
    <div class="_08123456789">08123456789</div>
    <div class="ubah-password">Ubah password</div>
</div>
@endsection
