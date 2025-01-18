@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>
            Form Isi EDOM
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('isi_edom') }}"><i class="fa fa-pencil-square-o"></i> Input EDOM</a></li>
            <li class="active">Isi Komentar EDOM</li>
        </ol>
    </section>
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>Kode</td>
                        <td>:</td>
                        <td> {{ $makul->kode }}</td>
                    </tr>
                    <tr>
                        <td>Matakuliah</td>
                        <td>:</td>
                        <td>{{ $makul->makul }}</td>
                    </tr>
                    <tr>
                        <td>Dosen</td>
                        <td>:</td>
                        <td>{{ $dosen->nama }}, {{ $dosen->akademik }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <div class="row">
                    <form action="{{ url('save_edom_kom') }}" method="post">
                        {{ csrf_field() }}

                        <input type="hidden" name="id_student" value="{{ $ids }}">
                        <input type="hidden" name="id_kurperiode" value="{{ $kurper }}">
                        <input type="hidden" name="id_kurtrans" value="{{ $kurtr }}">
                        <input type="hidden" name="id_edom" value="17">
                        <div class="col-md-12">
                            <textarea class="textarea" name="nilai_edom" placeholder="Masukan komentar disini maksimal 1000 karakter"
                                style="width: 100%; height: 100px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"
                                required></textarea>

                            <div class="form-group">
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info btn-block">
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
