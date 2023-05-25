@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h4 class="box-title"><b>Edit Nilai Skripsi</b></h4>
            </div>
            <div class="box-body">
                <table width="100%">
                    <tr>
                        <td style="width: 10%">Nama</td>
                        <td style="width: 2%">:</td>
                        <td style="width: 88%">{{ $datadiri->nama }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $datadiri->nim }}</td>
                    </tr>
                </table>
            </div>
        </div>
        <form class="" action="{{ url('put_nilai_skripsi_dospeng1') }}" method="post" enctype="multipart/form-data"
            name="autoSumForm">
            {{ csrf_field() }}
            <input type="hidden" name="id_settingrelasi_prausta" value="{{ $id }}">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title"><b>Form Penilaian Dosen Penguji I</b> </h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="3%" align="center">No</th>
                                <th width="25%">
                                    <center>Komponen Penilaian</center>
                                </th>
                                <th width="57%">
                                    <center>Acuan Penilaian</center>
                                </th>
                                <th width="10%">
                                    <center>Bobot (%)</center>
                                </th>
                                <th width="5%">
                                    <center>Nilai</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($nilai_pem as $item)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>{{ $item->komponen }}</td>
                                    <td>{{ $item->acuan }}</td>
                                    <td>
                                        <center>{{ $item->bobot }}%</center>
                                    </td>
                                    <td>
                                        <center>
                                            <input type="hidden" name="id_trans_penilaian[]"
                                                value="{{ $item->id_trans_penilaian }}">
                                            <input type="number" name="nilai[]" value="{{ $item->nilai }}" required>
                                            <center>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <button type="submit" class="btn btn-info">Simpan</button>
        </form>
    </section>
@endsection
