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
                <a href="/data_bim_pkl_mahasiswa" class="btn btn-info">Data Bimbingan PKL</a>
                <a href="/data_bim_magang_mahasiswa" class="btn btn-success">Data Bimbingan Magang</a>
            </div>
        </div>
    </section>
@endsection
