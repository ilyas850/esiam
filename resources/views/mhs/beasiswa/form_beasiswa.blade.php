@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Form Pengajuan Beasiswa</h3>
            </div>
            <form action="{{ url('save_pengajuan_beasiswa') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" value="{{ $mhs->nama }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>NIM</label>
                                <input type="text" class="form-control" value="{{ $mhs->nim }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Tempat/Tanggal lahir</label>
                                <input type="text" class="form-control"
                                    value="{{ $mhs->tmptlahir }}, {{ $mhs->tgllahir->isoFormat('D MMMM Y') }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Prodi</label>
                                <input type="text" class="form-control" value="{{ $mhs->prodi }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Kelas</label>
                                <input type="text" class="form-control" value="{{ $mhs->kelas }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No HP</label>
                                <input type="text" class="form-control" value="{{ $mhs->hp }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>E-mail</label>
                                <input type="text" class="form-control" value="{{ $mhs->email }}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection
