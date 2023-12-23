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
                                <th width="45%">Kemampuan Akhir yang Direncanakan</th>
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
                                        <textarea type="text" class="form-control" name="kemampuan_akhir_direncanakan[]" rows="3"></textarea>
                                    </td>
                                    <td>
                                        <textarea type="text" class="form-control" name="materi_pembelajaran[]" rows="3"></textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-info btn-block" type="submit">Simpan</button>
                </form>

            </div>
        </div>
    </section>
@endsection
