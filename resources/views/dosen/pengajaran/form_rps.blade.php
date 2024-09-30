@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><b>Rencana Pembelajaran Semester Matakuliah</b></h3><br><br>
                <table width="100%">
                    <tr>
                        <td>Kode - Matakuliah</td>
                        <td>:</td>
                        <td>{{ $data->kode }} - {{ $data->makul }}</td>
                        <td>SKS</td>
                        <td>:</td>
                        <td>{{ $data->sks }} </td>
                    </tr>
                    <tr>
                        <td>Prodi</td>
                        <td>:</td>
                        <td>{{ $data->prodi }}
                        </td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $data->kelas }}</td>
                    </tr>
                    <tr>
                        <td>Tahun Akademik</td>
                        <td>:</td>
                        <td>{{ $data->periode_tahun }} - {{ $data->periode_tipe }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <form action="{{ url('simpan_rps_makul_dsn') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_kurperiode" value="{{ $id }}">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="10%">Pertemuan</th>
                                <th width="45%">Kemampuan Akhir yang Diharapkan</th>
                                <th width="45%">Materi Pembelajaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pertemuan as $item)
                                <tr>
                                    <td>Pertemuan ke - {{ $item->pertemuan }}
                                        <input type="hidden" name="pertemuan[]" value="{{ $item->pertemuan }}">
                                    </td>
                                    <td>
                                        <textarea type="text" class="form-control" name="kemampuan_akhir_direncanakan[]" rows="3" required></textarea>
                                    </td>
                                    <td>
                                        <textarea type="text" class="form-control" name="materi_pembelajaran[]" rows="3" required></textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-info btn-block" data-toggle="modal" data-target="#modal-info"
                        type="button">Simpan</button>
                    <div class="modal modal-info fade" id="modal-info">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">Catatan</h4>
                                </div>
                                <div class="modal-body">
                                    <p>RPS tidak bisa diedit jika sudah disimpan, pastikan RPS sudah sesuai dengan PTIP
                                        &hellip;
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline pull-left"
                                        data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-outline">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
