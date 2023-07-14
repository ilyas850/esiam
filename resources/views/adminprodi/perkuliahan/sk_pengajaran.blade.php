@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Upload SK Pengajaran</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form action="{{ url('save_sk_pengajaran') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-xs-2">
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($tahun as $thn)
                                    <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                @foreach ($tipe as $tp)
                                    <option value="{{ $tp->id_periodetipe }}">{{ $tp->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-2">
                            <input type="file" name="file" class="form-control" required>
                        </div>
                        <div class="col-xs-2">
                            <button type="submit" class="btn btn-info ">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">SK Pengajaran Dosen Politeknik META Industri Cikarang</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>File</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td><a href="{{ asset('/SK Mengajar/' . $item->file) }}" target="_blank">File</a></td>
                                <td></td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
