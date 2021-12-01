@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <table width="100%">
                    <tr>
                        <td width="17%">Nomor Ijazah</td>
                        <td>:</td>
                        <td>{{ $item->no_ijazah }}</td>
                    </tr>
                    <tr>
                        <td width="17%">Nomor Transkrip</td>
                        <td>:</td>
                        <td>{{ $item->no_transkrip_final }}</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td> {{ $nama }}</td>
                    </tr>
                    <tr>
                        <td>Tempat & Tanggal lahir</td>
                        <td>:</td>
                        <td>{{ $item->tmptlahir }}, {{ $item->tgllahir->isoFormat('D MMMM Y') }}</td>
                    </tr>
                    <tr>
                        <td>Nomor Induk Mahasiswa</td>
                        <td>:</td>
                        <td>{{ $item->nim }}</td>
                    </tr>
                    <tr>
                        <td>Program Pendidikan</td>
                        <td>:</td>
                        <td>DIPLOMA III (D-III)</td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $item->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Kelulusan</td>
                        <td>:</td>
                        <td>{{ $tglyudi }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Wisuda</td>
                        <td>:</td>
                        <td>{{ $tglwisu }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Kode MK</center>
                            </th>
                            <th rowspan="2">
                                <center>Nama Matakuliah</center>
                            </th>
                            <th rowspan="2">
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
                                <center>Huruf</center>
                            </th>
                            <th>
                                <center>Angka</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->kode }}</center>
                                </td>
                                <td>{{ $key->makul }}</td>
                                <td>
                                    <center>{{ $key->akt_sks }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_AKHIR }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_ANGKA }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_sks }}</center>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3">
                                <center>TOTAL SKS</center>
                            </td>
                            <td>
                                <center><b>
                                        {{ $keysks->total_sks }}
                                    </b></center>
                            </td>
                            <td colspan="2"></td>
                            <td>
                                <center><b>
                                        {{ $keysks->nilai_sks }}
                                    </b></center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">Predikat : @if ($keysks->IPK >= 3.51)
                                    Cumlaude
                                @elseif ($keysks->IPK >= 3.00)
                                    Sangat Memuaskan
                                @elseif($keysks->IPK >= 2.00)
                                    Memuaskan
                                @endif
                            </td>
                            <td colspan="4">
                                <center> Indeks Prestasi Kumulatif <b>(IPK) : {{ $keysks->IPK }} </b></center>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7">Judul Tugas Akhir :</td>
                        </tr>
                        <tr>
                            <td colspan="7">{{ $item->judul_prausta }}</td>
                        </tr>
                    </tbody>
                </table>
                <br>

                <br>
                <a href="{{ url('transkrip_nilai_final') }}" class="btn btn-success">Kembali</a>
                <a href="/print_transkrip_final/{{ $item->idstudent }}" class="btn btn-info" target="_blank">Print</a>
                {{-- <a href="/export_word_transkrip_final/{{ $item->idstudent }}" class="btn btn-primary">Export
                    Word</a> --}}
                <a href="/downloadAbleFile/{{ $item->idstudent }}" class="btn btn-primary">Export
                    Word</a>

            </div>

        </div>
    </section>
@endsection
