@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td>Matakuliah</td>
                        <td>:</td>
                        <td>{{ $bap->makul }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $bap->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $bap->kelas }}</td>
                        <td>Semester</td>
                        <td>:</td>
                        <td>{{ $bap->semester }}</td>
                    </tr>
                </table>
            </div>

            <div class="box-body">

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>Pertemuan</center>
                            </th>
                            <th>
                                <center>Materi Kuliah</center>
                            </th>
                            <th>
                                <center>Materi Pembelajaran (RPS)</center>
                            </th>
                            <th>
                                <center>Kesesuaian RPS</center>
                            </th>
                            <th>
                                <center>Action</center>
                            </th>
                        </tr>

                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    <center>Ke-{{ $item->pertemuan }}</center>
                                </td>
                                <td>{{ $item->materi_kuliah }}</td>
                                <td>{{ $item->materi_pembelajaran }}</td>
                                <td align="center">
                                    @if ($item->kesesuaian_rps == 'SESUAI')
                                        {{ $item->kesesuaian_rps }}
                                    @elseif ($item->kesesuaian_rps == 'TIDAK SESUAI')
                                        {{ $item->kesesuaian_rps }}
                                    @endif
                                </td>
                                <td>
                                    <center>
                                        <a href="/validasi_sesuai/{{ $item->id_bap }}"
                                            class="btn btn-success btn-xs">Sesuai</a>
                                        <a href="/validasi_tidak_sesuai/{{ $item->id_bap }}"
                                            class="btn btn-danger btn-xs">Tidak</a>
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
