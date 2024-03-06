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
                                <th rowspan="2">
                                    <center>No</center>
                                </th>
                                <th rowspan="2">
                                    <center>NIM </center>
                                </th>
                                <th rowspan="2">
                                    <center>Nama</center>
                                </th>
                                <th rowspan="2">
                                    <center>Program Studi</center>
                                </th>
                                <th rowspan="2">
                                    <center>Kelas</center>
                                </th>
                                <th rowspan="2">
                                    <center>Angkatan</center>
                                </th>
                                <th colspan="2">
                                    <center>Nilai Sempro (40%)</center>
                                </th>
                                <th colspan="2">
                                    <center>Nilai TA (60%)</center>
                                </th>
                                <th colspan="2">
                                    <center>Nilai Akhir</center>
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
                                <th>
                                    <center>Angka</center>
                                </th>
                                <th>
                                    <center>Huruf</center>
                                </th>
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
                                    <td align="center">{{ $item->nilai_angka_sempro }}</td>
                                    <td align="center">{{ $item->nilai_sempro }}</td>
                                    <td align="center">{{ $item->nilai_angka_ta }}</td>
                                    <td align="center">{{ $item->nilai_ta }}</td>
                                    <td align="center">{{ $item->NILAI_AKHIR }}</td>
                                    <td align="center">
                                        @if ($item->NILAI_AKHIR >= 80)
                                            A
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="{{ $item->id_studentrecord }},A">
                                        @elseif($item->NILAI_AKHIR >= 75)
                                            B+
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="{{ $item->id_studentrecord }},B+">
                                        @elseif($item->NILAI_AKHIR >= 70)
                                            B
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="{{ $item->id_studentrecord }},B">
                                        @elseif($item->NILAI_AKHIR >= 65)
                                            C+
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="{{ $item->id_studentrecord }},C+">
                                        @elseif($item->NILAI_AKHIR >= 60)
                                            C
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="{{ $item->id_studentrecord }},C">
                                        @elseif($item->NILAI_AKHIR >= 50)
                                            D
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="{{ $item->id_studentrecord }},D">
                                        @elseif($item->NILAI_AKHIR >= 0)
                                            E
                                            <input type="hidden" name="nilai_AKHIR[]"
                                                value="{{ $item->id_studentrecord }},E">
                                        @endif
                                    </td>
                                    {{-- <td>
                                        <center>
                                            @if ($item->nilai_huruf != null)
                                                <select name="nilai_AKHIR[]">
                                                    <option
                                                        value="{{ $item->id_studentrecord }},{{ $item->nilai_huruf }}">
                                                        {{ $item->nilai_huruf }}</option>
                                                </select>
                                            @elseif ($item->nilai_huruf != null)
                                                {{ $item->nilai_AKHIR }}
                                            @endif
                                        </center>
                                    </td> --}}
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
