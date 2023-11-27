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
                        <td width="17%">Nomor Transkrip</td>
                        <td>:</td>
                        <td>{{ $item->no_transkrip }}</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $item->nama }}</td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $item->nim }}</td>
                    </tr>
                    <tr>
                        <td>Tempat & Tanggal lahir</td>
                        <td>:</td>
                        <td>{{ $item->tmptlahir }}, {{ $item->tgllahir->isoFormat('D MMMM Y') }}</td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $item->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Jenjang Pendidikan</td>
                        <td>:</td>
                        <td>DIPLOMA III (D-3)</td>
                    </tr>
                    <tr>
                        <td>Tanggal lulus</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Nomor Induk Ijazah</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <table class="table">
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
                                    <center>{{ $key->sks }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_AKHIR }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nilai_indeks }}</center>
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
                    </tbody>
                </table>
                <br>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td width="17%">Judul Laporan</td>
                            <td>: <b>-</b></td>
                        </tr>
                        <tr>
                            <td>Pembimbing</td>
                            <td>: <b>-</b></td>
                        </tr>
                        <tr>
                            <td>Indeks Prestasi Kumulatif</td>
                            <td>:<b> {{ $keysks->nilai_sks }} / {{ $keysks->total_sks }} = {{ $keysks->ipk }}</b> </td>
                        </tr>
                        <tr>
                            <td>Predikat Kelulusan</td>
                            <td>: <b>-</b> </td>
                        </tr>
                    </tbody>
                </table>
                <br>
                <a href="{{ url('transkrip_nilai') }}" class="btn btn-success">Kembali</a>
                <a href="/print_transkrip/{{ $item->id_transkrip }}" class="btn btn-info" target="_blank">Print</a>
            </div>

        </div>
    </section>
@endsection
