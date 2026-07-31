@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title"> <b> Data List Mahasiswa </b></h3>
                <table width="100%">
                    <tr>
                        <td>Tahun Akademik</td>
                        <td>:</td>
                        <td>{{ $tahun->periode_tahun }}</td>
                        <td>Prodi</td>
                        <td>:</td>
                        <td>{{ $prodi->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Semester</td>
                        <td>:</td>
                        <td>{{ $tipe->periode_tipe }}</td>
                        <td>Tipe PraUSTA</td>
                        <td>:</td>
                        <td>{{ $tp_prausta }}</td>
                    </tr>
                </table>
            </div>
            <form action="{{ url('save_nilai_to_trans') }}" method="post">
                {{ csrf_field() }}
                <div class="box-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th rowspan="2" width="5%">
                                    <center>No</center>
                                </th>
                                <th rowspan="2" width="10%">
                                    <center>NIM </center>
                                </th>
                                <th rowspan="2" width="25%">
                                    <center>Nama</center>
                                </th>
                                <th rowspan="2" width="15%">
                                    <center>Program Studi</center>
                                </th>
                                <th rowspan="2" width="10%">
                                    <center>Kelas</center>
                                </th>
                                <th rowspan="2" width="10%">
                                    <center>Angkatan</center>
                                </th>
                                <th colspan="2">
                                    <center>Nilai PKL</center>
                                </th>
                                <th rowspan="2">
                                    <center>Nilai Transkrip</center>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <center>Angka</center>
                                </th>
                                <th>
                                    <center>Huruf</center>
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
                                        <center>{{ $item->nim }}</center>
                                    </td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->prodi }}</td>
                                    <td>
                                        <center>{{ $item->kelas }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $item->angkatan }}</center>
                                    </td>
                                    @php
                                        $hurufFinal = $item->nilai_huruf;
                                        if (empty($hurufFinal) && isset($item->nilai_angka) && $item->nilai_angka !== null) {
                                            if ($item->nilai_angka >= 80) $hurufFinal = 'A';
                                            elseif ($item->nilai_angka >= 75) $hurufFinal = 'B+';
                                            elseif ($item->nilai_angka >= 70) $hurufFinal = 'B';
                                            elseif ($item->nilai_angka >= 65) $hurufFinal = 'C+';
                                            elseif ($item->nilai_angka >= 60) $hurufFinal = 'C';
                                            elseif ($item->nilai_angka >= 50) $hurufFinal = 'D';
                                            elseif ($item->nilai_angka >= 0) $hurufFinal = 'E';
                                        }
                                    @endphp
                                    <td align="center">
                                        {{ $item->nilai_angka ?? '-' }}
                                    </td>
                                    <td>
                                        <center>
                                            @if ($hurufFinal == 'A')
                                                A
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="{{ $item->id_studentrecord }},A">
                                            @elseif($hurufFinal == 'B+')
                                                B+
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="{{ $item->id_studentrecord }},B+">
                                            @elseif($hurufFinal == 'B')
                                                B
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="{{ $item->id_studentrecord }},B">
                                            @elseif($hurufFinal == 'C+')
                                                C+
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="{{ $item->id_studentrecord }},C+">
                                            @elseif($hurufFinal == 'C')
                                                C
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="{{ $item->id_studentrecord }},C">
                                            @elseif($hurufFinal == 'D')
                                                D
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="{{ $item->id_studentrecord }},D">
                                            @elseif($hurufFinal == 'E')
                                                E
                                                <input type="hidden" name="nilai_AKHIR[]"
                                                    value="{{ $item->id_studentrecord }},E">
                                            @endif
                                        </center>
                                    </td>
                                    <td align="center">
                                        {{ $item->nilai_AKHIR }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <input class="btn btn-info btn-block" type="submit" name="submit" value="Simpan">
                </div>
            </form>
        </div>
    </section>
@endsection
