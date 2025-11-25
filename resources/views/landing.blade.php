@extends('layouts.app')

@section('title', 'NTS Batu - Fresh Strawberry & Berry')

@section('content')
    {{-- Hero --}}
    @include('sections.hero')

    {{-- Pre-order info --}}
    @include('sections.preorder-info')

    {{-- Produk --}}
    @include('sections.products')

    {{-- Galeri --}}
    @include('sections.gallery')

    {{-- About --}}
    @include('sections.about')

    {{-- Testimoni --}}
    @include('sections.testimonials')

    {{-- FAQ --}}
    @include('sections.faq')

    {{-- Kontak --}}
    @include('sections.contact')
@endsection

@push('scripts')
    <script src="{{ asset('js/nts.js') }}"></script>
@endpush