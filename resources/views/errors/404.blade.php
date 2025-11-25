@extends('errors.layout')

@section('title', 'Halaman tidak ditemukan')
@section('code', '404')

@section('headline')
    Wah Maaf, halaman nggak ketemu
@endsection

@section('message')
    Halaman yang kamu cari mungkin sudah dipindah, dihapus, atau alamatnya salah.
@endsection

@section('description')
    Coba periksa kembali URL yang kamu ketik, atau kembali ke beranda untuk melihat produk
    <span class="font-semibold text-gray-700">strawberry & berry segar</span> dari NTS Batu.
@endsection
