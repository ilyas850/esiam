@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content-header">
        <h1>
            Validasi TA & Skripsi
            <small>Memuat Data Validasi...</small>
        </h1>
    </section>

    <section class="content">
        <div class="callout callout-info">
            <h4><i class="fa fa-info-circle"></i> Mengalihkan...</h4>
            <p>Halaman sedang dialihkan ke tab Validasi Tugas Akhir. Jika tidak beralih otomatis, silakan <a href="{{ url('data_val_ta_mahasiswa') }}" class="btn btn-xs btn-default">klik di sini</a>.</p>
        </div>
    </section>
@endsection

@section('script')
    <script>
        window.location.href = "{{ url('data_val_ta_mahasiswa') }}";
    </script>
@endsection