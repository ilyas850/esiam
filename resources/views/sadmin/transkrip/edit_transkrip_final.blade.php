@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Form Transkrip Nilai Final</h3>
                    </div>
                    <form class="form" role="form"
                        action="/simpanedit_transkrip_final/{{ $item->id_transkrip_final }}" method="POST">
                        <input type="hidden" name="_method" value="PUT">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control" value="{{ $item->nama_lengkap }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>No. Transkrip</label>
                                    <input type="text" class="form-control" name="no_transkrip_final"
                                        value="{{ $item->no_transkrip_final }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label>NIM</label>
                                    <input type="text" class="form-control" value="{{ $item->nim }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>No. Ijazah</label>
                                    <input type="text" class="form-control" name="no_ijazah"
                                        value="{{ $item->no_ijazah }}" required>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label>Tempat & Tanggal Lahir</label>
                                    <input type="text" class="form-control"
                                        value="{{ $item->tmpt_lahir }}, {{ $tgllhr }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Tanggal Yudisium</label>
                                    <input type="date" class="form-control" name="tgl_yudisium"
                                        value="{{ $item->tgl_yudisium }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label>Program Studi</label>
                                    <input type="text" class="form-control" value="{{ $item->prodi }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label>Tanggal Wisuda</label>
                                    <input type="date" class="form-control" name="tgl_wisuda"
                                        value="{{ $item->tgl_wisuda }}" required>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-md-6">
                                    <label>Judul Laporan</label>
                                    <textarea rows="4" class="form-control" cols="80"
                                        readonly> {{ $item->judul_prausta }} </textarea>
                                </div>
                                <div class="col-md-6">
                                    <label>Jenjang Pendidikan</label>
                                    <input type="text" class="form-control" value="DIPLOMA III (D-3)" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <div class="form-group">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary">
                                        Simpan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
