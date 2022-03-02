@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-widget widget-user">
                    <div class="widget-user-header bg-black"
                        style="background: url('adminlte/dist/img/photo1.png') center center;">
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="adminlte/img/default.jpg" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-1 border-right">
                                <div class="description-block">
                                </div>
                            </div>
                            <div class="col-sm-10 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">
                                        @if ($dosen_pa == null)
                                            Belum ada
                                        @else
                                            {{ $dosen_pa->nama }}
                                        @endif
                                    </h5>
                                    <span class="description-text">Pembimbing Akademik</span>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="description-block">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-widget widget-user">
                    <div class="widget-user-header bg-black"
                        style="background: url('adminlte/dist/img/photo2.png') center center;">
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="adminlte/img/default.jpg" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-1 border-right">
                                <div class="description-block">
                                </div>
                            </div>
                            <div class="col-sm-10 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">
                                        @if ($dosen_pkl == null)
                                            Belum ada
                                        @else
                                            {{ $dosen_pkl->nama }}
                                        @endif
                                    </h5>
                                    <span class="description-text">Pembimbing Prakerin</span>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="description-block">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-widget widget-user">
                    <div class="widget-user-header bg-black"
                        style="background: url('adminlte/dist/img/photo3.jpg') center center;">
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="adminlte/img/default.jpg" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-1 border-right">
                                <div class="description-block">
                                </div>
                            </div>
                            <div class="col-sm-10 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">
                                        @if ($dosen_sempro == null)
                                            Belum ada
                                        @else
                                            {{ $dosen_sempro->nama }}
                                        @endif
                                    </h5>
                                    <span class="description-text">Pembimbing Sempro</span>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="description-block">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-widget widget-user">
                    <div class="widget-user-header bg-black"
                        style="background: url('adminlte/dist/img/photo4.jpg') center center;">
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" src="adminlte/img/default.jpg" alt="User Avatar">
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-sm-1 border-right">
                                <div class="description-block">
                                </div>
                            </div>
                            <div class="col-sm-10 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">
                                        @if ($dosen_ta == null)
                                            Belum ada
                                        @else
                                            {{ $dosen_ta->nama }}
                                        @endif
                                    </h5>
                                    <span class="description-text">Pembimbing Tugas Akhir</span>
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="description-block">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
