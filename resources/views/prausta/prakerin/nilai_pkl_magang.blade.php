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
                <a href="/data_nilai_pkl_mahasiswa" class="btn btn-info">Data Nilai PKL</a>
                <a href="/data_nilai_magang_mahasiswa" class="btn btn-success">Data Nilai Magang 1</a>
                <a href="/data_nilai_magang2_mahasiswa" class="btn btn-warning">Data Nilai Magang 2</a>
            </div>
        </div>
    </section>
@endsection
