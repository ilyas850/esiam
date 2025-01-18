@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <b> Kartu Hasil Studi Mahasiswa (FINAL-TERM)</b>
                <table width="100%">
                    <tr>
                        <td>TA Semester</td>
                        <td>:</td>
                        <td>
                            {{ $nama_periodetahun }}
                            {{ $nama_periodetipe }}

                        </td>
                        <td align=right>Jumlah SKS Maksimal&ensp; </td>
                        <td> : </td>
                        <td>24</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $mhs->nama }}</td>
                        <td align=right>SKS Tempuh&ensp; </td>
                        <td> : </td>
                        <td>{{ $sks }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $mhs->nim }}
                        </td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td> : </td>
                        <td>{{ $mhs->prodi }}
                        </td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $mhs->kelas }}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <form class="" action="{{ url('unduh_khs_final_term') }}" method="post">
                    {{ csrf_field() }}

                    <input type="hidden" name="id_student" value="{{ $id }}">
                    <input type="hidden" name="id_periodetahun" value="{{ $idthn }}">
                    <input type="hidden" name="id_periodetipe" value="{{ $idtp }}">
                    <button type="submit" class="btn btn-danger ">Unduh KHS</button>
                </form>
                <br>
                <table class="table table-bordered">
                    <thead>
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
                            <th rowspan="2">
                                <center>SKS Teori/Praktek</center>
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
                                <center>Huruf</center>
                            </th>
                            <th>
                                <center>Angka</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($recordas as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <center>{{ $item->kode }}</center>
                                </td>
                                <td>{{ $item->makul }}</td>
                                <td>
                                    <center>{{ $item->akt_sks_teori }} / {{ $item->akt_sks_praktek }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_AKHIR }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->nilai_ANGKA }}

                                    </center>
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
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
