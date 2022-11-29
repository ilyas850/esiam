@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Pengawas Ujian <b>{{ $tahun->periode_tahun }} - {{ $tipe->periode_tipe }}
                        ({{ $prodi->prodi }} / {{ $kelas->kelas }})</b></h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('save_pengawas_ujian') }}" method="POST">
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
                                    <center>SKS</center>
                                </th>
                                <th>
                                    <center>Jenis Ujian</center>
                                </th>
                                <th>
                                    <center>Dosen</center>
                                </th>
                                <th>
                                    <center>Aktual Pengawas</center>
                                </th>
                                <th>
                                    <center>Aktual Pengoreksi</center>
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
                                    <td align="center">{{ $item->sks }}</td>
                                    <td align="center">{{ $item->jenis_ujian }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td align="center">
                                        <select name="pengawas[]" class="select2">
                                            <option
                                                value="{{ $item->aktual_pengawas }},{{ $item->idmakul }},{{ $item->id_jam }},{{ $item->id_ruangan }}">
                                                {{ $item->aktual_pengawas }}
                                            </option>
                                            @foreach ($dosen as $keyangk)
                                                <option
                                                    value="{{ $keyangk->nama }},{{ $item->idmakul }},{{ $item->id_jam }},{{ $item->id_ruangan }}">
                                                    {{ $keyangk->nama }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>{{ $item->aktual_pengoreksi }}</td>
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
