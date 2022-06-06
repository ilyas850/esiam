@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <center>
                    <h3>Kartu Rencana Studi Mahasiswa</h3>
                </center>
                <table width="100%">
                    <tr>
                        <td>TA Semester</td>
                        <td> : </td>
                        <td>{{ $periodetahun }} ({{ $periodetipe }})</td>
                        <td align=right>Jumlah SKS Maksimal</td>
                        <td>:</td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td> : </td>
                        <td>{{ $data_mhs->nama }}</td>
                        <td align=right>SKS Tempuh&ensp;</td>
                        <td>:</td>
                        <td>{{ $sks }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td> : </td>
                        <td>{{ $data_mhs->nim }}</td>
                    </tr>
                    <tr>
                        <td>Jurusan</td>
                        <td> : </td>
                        <td> {{ $data_mhs->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $data_mhs->kelas }} </td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                {{-- <div class="row">
                    <div class="col-md-12">
                        <h4 class="box-title">List Matakuliah</h4>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td>Pilih</td>
                                    <td>Semester</td>
                                    <td>Kode</td>
                                    <td>Matakuliah</td>
                                    <td>Dosen</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($final_krs as $item)
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td>{{ $item->semester }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->makul }}</td>
                                        <td>{{ $item->nama }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div> --}}
                <a class="btn btn-warning" href="{{ url('unduh_krs') }}">Unduh KRS</a>
                <a class="btn btn-success" href="{{ url('input_krs') }}">Input KRS</a>
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="box-title">Matakuliah yang diambil</h3>
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <th>Tanggal KRS</th>
                                    <th>Semester</th>
                                    <th>Kode</th>
                                    <th>Matakuliah</th>
                                    <th>Hari</th>
                                    <th>Jam</th>
                                    <th>Ruangan</th>
                                    <th>SKST</th>
                                    <th>SKSP</th>
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
                                        <td>{{ $item->semester }} </td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->makul }}</td>
                                        <td>{{ $item->hari }}</td>
                                        <td>{{ $item->jam }}</td>
                                        <td>{{ $item->nama_ruangan }}</td>
                                        <td>
                                            <center>{{ $item->akt_sks_teori }}</center>
                                        </td>
                                        <td>
                                            <center>{{ $item->akt_sks_praktek }}</center>
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
