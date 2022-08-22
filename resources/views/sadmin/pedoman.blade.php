@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah Pedoman Akademik</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form action="{{ url('save_pedoman_akademik') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="col-xs-3">
                            <input type="text" class="form-control" name="nama_pedoman" placeholder="Masukan Nama File">
                        </div>
                        <div class="col-xs-3">
                            <select class="form-control" name="id_periodetahun">
                                <option>-pilih tahun akademik-</option>
                                @foreach ($tahun as $thn)
                                    <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <input type="file" name="file">
                        </div>
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-info ">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Pedoman Akademik Politeknik META Industri Cikarang</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Nama File</center>
                            </th>
                            <th>
                                <center>File</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($pedoman as $keypdm)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>{{ $keypdm->nama_pedoman }}</td>
                                <td><a href="{{ asset('/pedoman/' . $keypdm->file) }}"
                                        target="_blank">{{ $keypdm->file }}</a></td>
                                <td>
                                    <center>
                                        @foreach ($tahun as $thn)
                                            @if ($keypdm->id_periodetahun == $thn->id_periodetahun)
                                                {{ $thn->periode_tahun }}
                                            @endif
                                        @endforeach
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
