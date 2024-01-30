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
                                        @if ($item->komentar == null)
                                            <button class="btn btn-info btn-xs" data-toggle="modal"
                                                data-target="#modalTambahKomentar{{ $item->id_rps }}">Komentar</button>
                                        @else
                                            <a class="btn btn-warning btn-xs" data-toggle="modal"
                                                data-target="#modalTambahKomentar{{ $item->id_rps }}"> <i
                                                    class="fa fa-eye "></i> Lihat</a>
                                        @endif

                                        <a href="/validasi_sesuai/{{ $item->id_bap }}"
                                            class="btn btn-success btn-xs">Sesuai</a>
                                        <a href="/validasi_tidak_sesuai/{{ $item->id_bap }}"
                                            class="btn btn-danger btn-xs">Tidak</a>
                                    </center>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalTambahKomentar{{ $item->id_rps }}" tabindex="-1"
                                aria-labelledby="modalTambahKomentar" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Komentar RPS</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/komentar_rps_makul/{{ $item->id_rps }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <textarea class="form-control" name="komentar" cols="20" rows="10"> {{ $item->komentar }} </textarea>
                                                </div>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
