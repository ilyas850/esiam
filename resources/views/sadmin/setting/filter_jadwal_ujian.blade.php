@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Jadwal Ujian <b>{{ $tahun->periode_tahun }} - {{ $tipe->periode_tipe }}
                        ({{ $prodi->prodi }} / {{ $kelas->kelas }})</b></h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('save_jadwal_ujian') }}" method="POST">
                    {{ csrf_field() }}
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>Matakuliah </center>
                                </th>
                                <th>
                                    <center>Jenis Ujian</center>
                                </th>
                                <th>
                                    <center>Tanggal</center>
                                </th>
                                <th>
                                    <center>Jam</center>
                                </th>
                                <th>
                                    <center>Ruangan</center>
                                </th>
                                <th>
                                    <center>Tipe Ujian</center>
                                </th>
                                <th>
                                    <center>Jenis Ujian</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($data as $item)
                                <tr>
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>
                                        {{ $item->makul }}
                                    </td>
                                    <td align="center">{{ $jenis_ujian }}</td>
                                    <td align="center">
                                        <input type="hidden" name="data[]"
                                            value="{{ $item->id_makul }},{{ $item->id_hari }},{{ $item->id_jam }},{{ $item->id_ruangan }}">
                                        <input type="date" name="tanggal_ujian[]" class="form-control">
                                    </td>
                                    <td align="center">{{ $item->jam }}</td>
                                    <td align="center">{{ $item->nama_ruangan }}</td>
                                    <td align="center">
                                        <select name="id_tipeujian[]" class="form-control">
                                            <option></option>
                                            <option value="1">Teori</option>
                                            <option value="2">Praktikum</option>
                                            <option value="3">Teori+Praktikum</option>
                                        </select>
                                    </td>
                                    <td align="center">{{ $item->tipe_ujian_uts }}</td>
                                </tr>
                                <input type="hidden" name="id_periodetahun" value="{{ $tahun->id_periodetahun }}">
                                <input type="hidden" name="id_periodetipe" value="{{ $tipe->id_periodetipe }}">
                                <input type="hidden" name="jenis_ujian" value="{{ $jenis_ujian }}">
                                <input type="hidden" name="idkelas" value="{{ $kelas->idkelas }}">
                                <input type="hidden" name="kodeprodi" value="{{ $kodeprodi }}">
                            @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-info" type="submit">Simpan</button>
                </form>
            </div>
        </div>
    </section>
@endsection
