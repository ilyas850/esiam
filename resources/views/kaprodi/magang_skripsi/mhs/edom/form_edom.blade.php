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
            <li class="active">Isi Form EDOM</li>
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
                        <td>{{ $nama_dsn }}, {{ $akademik }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <div class="row">
                    <form action="{{ url('save_edom') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="id_student" value="{{ $ids }}">
                        <input type="hidden" name="id_kurperiode" value="{{ $kurper }}">
                        <input type="hidden" name="id_kurtrans" value="{{ $kurtr }}">
                        <div class="col-md-12">
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <th width="1%">
                                            <center>No</center>
                                        </th>
                                        <th width="18%">
                                            <center>Tipe EDOM</center>
                                        </th>
                                        <th width="71%">
                                            <center>Detail EDOM</center>
                                        </th>
                                        <th width="10%">
                                            <center>Nilai</center>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    @foreach ($edom as $key)
                                        <tr>
                                            <td>
                                                <center>{{ $no++ }}</center>
                                            </td>
                                            <td>{{ $key->type }}</td>
                                            <td>{{ $key->description }}</td>
                                            <td>
                                                <center>
                                                    <select class="form-control" name="nilai_edom[]" required>
                                                        <option></option>
                                                        <option value="{{ $key->id_edom }},1">Tidak Baik</option>
                                                        <option value="{{ $key->id_edom }},2">Kurang Baik</option>
                                                        <option value="{{ $key->id_edom }},3">Baik</option>
                                                        <option value="{{ $key->id_edom }},4">Sangat Baik</option>
                                                    </select>
                                                </center>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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
    </section>
@endsection
