@extends('layouts.master')

@section('side')
@include('layouts.side')
@endsection

@section('content')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_1" data-toggle="tab">Data Skripsi</a></li>
                    <li><a href="#tab_2" data-toggle="tab">Data Bimbingan Skripsi</a></li>
                    <li><a href="#tab_3" data-toggle="tab">Pengajuan Skripsi</a></li>
                    <li><a href="#tab_4" data-toggle="tab">Upload Draft Laporan Skripsi</a></li>
                </ul>
                <div class="tab-content">
                </div>
            </div>
        </div>
    </div>
</section>
@endsection