@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Cek KRS Mahasiswa Sebelumnya</h3>
            </div>
            <div class="box-body">
                <form class="form" role="form" action="{{ url('filter_krs') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-xs-3">
                            <label>Tahun Akademik</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($periodetahun_all as $key)
                                    <option value="{{ $key->id_periodetahun }}">
                                        {{ $key->periode_tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label>Semester</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                @foreach ($periodetipe_all as $key)
                                    <option value="{{ $key->id_periodetipe }}">
                                        {{ $key->periode_tipe }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-xs-3">
                            <button type="submit" class="btn btn-info ">Pilih</button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"> <b> Kartu Rencana Studi Mahasiswa {{ $periodetahun }} - {{ $periodetipe }} </b></h3>
                <table width="100%">
                    <tr>
                        <td>Nama</td>
                        <td> : </td>
                        <td>{{ $data_mhs->nama }}</td>
                        <td align=right>Jumlah SKS Maksimal</td>
                        <td>:</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td> : </td>
                        <td>{{ $data_mhs->nim }}</td>
                        <td align=right>SKS Tempuh&ensp;</td>
                        <td>:</td>
                        <td>{{ $sks }}</td>
                    </tr>
                    <tr>
                        <td>Prodi</td>
                        <td> : </td>
                        <td> {{ $data_mhs->prodi }} - {{ $data_mhs->konsentrasi }} </td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $data_mhs->kelas }} </td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-1">
                        <form action="{{ url('unduh_krs') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                            <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                            <button type="submit" class="btn btn-warning">Unduh KRS</button>
                        </form>
                    </div>
                    <div class="col-xs-1">
                        <form action="{{ url('input_krs') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                            <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                            <button type="submit" class="btn btn-success">Input KRS</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="box-title"> Matakuliah yang diambil</h4>
                        <table class="table table-condensed" id="example9">
                            <thead>
                                <tr>
                                    <th>
                                        <center>Tanggal KRS</center>
                                    </th>
                                    <th>
                                        <center>Semester</center>
                                    </th>
                                    <th>Matakuliah</th>
                                    <th>
                                        <center>Hari</center>
                                    </th>
                                    <th>
                                        <center>Jam</center>
                                    </th>
                                    <th>
                                        <center>Ruangan</center>
                                    </th>
                                    <th>
                                        <center>SKS (T/P)</center>
                                    </th>
                                    <th>Dosen</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($record as $item)
                                    <tr>
                                        <td>
                                            <center>
                                                {{ $item->tanggal_krs }}
                                            </center>
                                        </td>
                                        <td align="center">{{ $item->semester }} </td>
                                        <td>{{ $item->kode }}/{{ $item->makul }}</td>
                                        <td align="center">
                                            @if ($item->hari == 'MONDAY')
                                                SENIN
                                            @elseif($item->hari == 'TUESDAY')
                                                SELASA
                                            @elseif($item->hari == 'WEDNESDAY')
                                                RABU
                                            @elseif($item->hari == 'THURSDAY')
                                                KAMIS
                                            @elseif($item->hari == 'FRIDAY')
                                                JUMAT
                                            @elseif($item->hari == 'SATURDAY')
                                                SABTU
                                            @endif
                                        </td>
                                        <td align="center">{{ $item->jam }}</td>
                                        <td align="center">{{ $item->nama_ruangan }}</td>
                                        <td>
                                            <center>{{ $item->akt_sks_teori }}/{{ $item->akt_sks_praktek }}</center>
                                        </td>
                                        <td>{{ $item->nama }}</td>
                                        <td>
                                            @if ($item->remark == 0)
                                                <form method="POST" action="{{ url('batalkrs') }}">
                                                    <input type="hidden" name="status" value="DROPPED">
                                                    <input type="hidden" name="id_studentrecord"
                                                        value="{{ $item->id_studentrecord }}">
                                                    {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-danger btn-xs"
                                                        title="klik untuk batal" data-toggle="tooltip"
                                                        data-placement="right"
                                                        onclick="return confirm('apakah anda yakin akan membatalkan matakuliah ini?')">Batal</button>
                                                </form>
                                            @elseif ($item->remark == 1)
                                                <span class="badge bg-green">valid</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
