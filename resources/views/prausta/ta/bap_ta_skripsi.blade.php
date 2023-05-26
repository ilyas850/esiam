@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Pilih Tipe</h3>
            </div>
            <div class="box-body">
                <a href="/data_bap_ta_mahasiswa" class="btn btn-info">Data BAP TA</a>
                <a href="/data_bap_skripsi_mahasiswa" class="btn btn-success">Data BAP Skripsi</a>
            </div>
        </div>
    </section>
@endsection
