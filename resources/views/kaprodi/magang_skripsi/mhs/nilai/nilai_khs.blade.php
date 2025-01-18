@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-primary">
            <div class="panel-body">
                <center>
                    <h3>Kartu Hasil Studi Mahasiswa</h3>
                </center>
                <div class="row">
                    <div class="col-md-12">
                        <table width="100%">
                            <tr>
                                <td><b>TA Semester</b></td>
                                <td>:</td>
                                <td><b><u>
                                            {{ $periodetahun }}
                                            {{ $periodetipe }}
                                    </b></u>
                                </td>
                                <td align=right>Jumlah SKS Maksimal&ensp; </td>
                                <td> : </td>
                                <td>24</td>
                            </tr>
                            <tr>
                                <td><b>Nama</b></td>
                                <td>:</td>
                                <td><b><u>{{ $mhs->nama }}</b></u></td>
                                <td align=right>SKS Tempuh&ensp; </td>
                                <td> : </td>
                                <td>{{ $sks }}</td>
                            </tr>
                            <tr>
                                <td><b>NIM</b></td>
                                <td>:</td>
                                <td><b><u>{{ $mhs->nim }}</u></b></td>
                            </tr>
                            <tr>
                                <td><b>Program Studi</b></td>
                                <td> : </td>
                                <td><b><u>{{ $mhs->prodi }} </u></b>
                                </td>
                            </tr>
                            <tr>
                                <td><b>Kelas</b></td>
                                <td>:</td>
                                <td><b><u>{{ $mhs->kelas }}</b></u>
                                </td>
                            </tr>
                        </table>
                        <br>
                        <form class="" action="{{ url('unduh_khs_nilai') }}" method="post">
                            {{ csrf_field() }}

                            <input type="hidden" name="id_student" value="{{ $iduser }}">
                            <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                            <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                            <button type="submit" class="btn btn-info ">Unduh KHS</button>
                        </form>
                        <br>
                        <div class="box">
                            <table class="table table-condensed">
                                <tr>
                                    <th rowspan="2" style="width: 10px" align=center>
                                        <center>No</center>
                                    </th>
                                    <th rowspan="2">
                                        <center>Kode</center>
                                    </th>
                                    <th rowspan="2">
                                        <center>Matakuliah</center>
                                    </th>
                                    <th colspan="2">
                                        <center>SKS</center>
                                    </th>
                                    <th colspan="2">
                                        <center>Nilai</center>
                                    </th>
                                    <th rowspan="2">
                                        <center>Nilai x SKS</center>
                                    </th>
                                </tr>
                                <tr>
                                    <th>
                                        <center>Teori</center>
                                    </th>
                                    <th>
                                        <center>Praktek</center>
                                    </th>
                                    <th>
                                        <center>Huruf</center>
                                    </th>
                                    <th>
                                        <center>Angka</center>
                                    </th>
                                </tr>
                                <?php $no = 1; ?>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>
                                            <center>{{ $item->kode }}</center>
                                        </td>
                                        <td>{{ $item->makul }}</td>
                                        <td>
                                            <center>{{ $item->akt_sks_teori }}
                                            </center>
                                        </td>
                                        <td>
                                            <center>{{ $item->akt_sks_praktek }}</center>
                                        </td>
                                        <td>
                                            <center>{{ $item->nilai_AKHIR }}</center>
                                        </td>
                                        <td>
                                            <center>{{ $item->nilai_ANGKA }}</center>
                                        </td>
                                        <td>
                                            <center>

                                                @if ($item->nilai_AKHIR == 'A')
                                                    {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 4 }}
                                                @elseif ($item->nilai_AKHIR == 'B+')
                                                    {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 3.5 }}
                                                @elseif ($item->nilai_AKHIR == 'B')
                                                    {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 3.0 }}
                                                @elseif ($item->nilai_AKHIR == 'C+')
                                                    {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 2.5 }}
                                                @elseif ($item->nilai_AKHIR == 'C')
                                                    {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 2.0 }}
                                                @elseif ($item->nilai_AKHIR == 'D')
                                                    {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 1.0 }}
                                                @elseif ($item->nilai_AKHIR == 'E')
                                                    {{ ($item->akt_sks_praktek + $item->akt_sks_teori) * 0.0 }}
                                                @elseif ($item->nilai_AKHIR == null)
                                                    0
                                                @endif

                                            </center>
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="3">
                                        <center>Jumlah</center>
                                    </td>
                                    <td colspan="2">
                                        <center><b>{{ $sks }}</b></center>
                                    </td>
                                    <td colspan="2"></td>
                                    <td>
                                        <center><b>{{ $nxsks }}</b></center>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <center>Indeks Prestasi Semester (IPS)</center>
                                    </td>
                                    <td colspan="2">
                                        <center><b>{{ number_format($nxsks / $sks, 2) }}</b></center>
                                    </td>
                                    <td colspan="3"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
