@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    @include('prausta.partials.nilai_akhir_style')

    <section class="content nilai-akhir">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="nilai-page-title">Nilai TA dan Skripsi</h3>
                <div class="nilai-page-subtitle">Pilih tipe penilaian untuk melihat, mengedit, mengunduh form, atau melakukan validasi nilai mahasiswa.</div>
            </div>
        </div>

        @include('prausta.partials.nilai_ta_skripsi_nav', ['active' => ''])
    </section>
@endsection
