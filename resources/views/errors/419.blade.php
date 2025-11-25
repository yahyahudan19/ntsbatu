@extends('errors.layout')

@section('title', 'Sesi kadaluarsa')
@section('code', '419')

@section('headline')
    Sesi kamu sudah berakhir
@endsection

@section('message')
    Demi keamanan, sesi kamu sudah kadaluarsa atau token sudah tidak valid.
@endsection

@section('description')
    Biasanya ini terjadi jika halaman dibiarkan terlalu lama sebelum dikirim, atau kamu membuka form di beberapa tab.  
    Silakan muat ulang halaman lalu coba kirim ulang form atau ulangi proses checkout.
@endsection
