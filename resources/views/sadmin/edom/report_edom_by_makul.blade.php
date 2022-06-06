@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"> <b> Laporan Rekapitulasi EDOM (Per Matakuliah)</b></h3>
                <table width="100%">
                    <tr>
                        <td width="20%">Tahun Akademik / Semester</td>
                        <td>:</td>
                        <td>{{ $thn }} / {{ $tp }}</td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $prd }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <table class="table table-bordered" id="example1">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Dosen</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>MHS Qty</center>
                            </th>
                            <th>
                                <center>EDOM Qty</center>
                            </th>
                            <th>
                                <center>Nilai Angka</center>
                            </th>
                            <th>
                                <center>Nilai Huruf</center>
                            </th>
                            <th>
                                <center>Aksi</center>
                            </th>
                        </tr>
                    </thead>
                    <?php $no = 1; ?>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    {{ $item->nama }}
                                </td>
                                <td>
                                    {{ $item->makul }}
                                </td>
                                <td>
                                    <center>
                                        {{ $item->kelas }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $item->jml_mhs }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $item->mhs_isi_edom }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $item->nilai_edom }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->nilai_edom >= 80)
                                            A
                                        @elseif ($item->nilai_edom >= 75)
                                            B+
                                        @elseif ($item->nilai_edom >= 70)
                                            B
                                        @elseif ($item->nilai_edom >= 65)
                                            C+
                                        @elseif ($item->nilai_edom >= 60)
                                            C
                                        @elseif ($item->nilai_edom >= 50)
                                            D
                                        @elseif ($item->nilai_edom >= 0)
                                            E
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <form action="detail_edom_makul" method="POST">
                                            {{ csrf_field() }}

                                            <input type="hidden" name="id_kurperiode" value="{{ $item->id_kurperiode }}">
                                            {{-- <input type="hidden" name="id_dosen" value="{{ $item->id_dosen }}">
                                            <input type="hidden" name="nama" value="{{ $item->nama }}">
                                            <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                                            <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                                            <input type="hidden" name="periodetahun" value="{{ $thn }}">
                                            <input type="hidden" name="periodetipe" value="{{ $tp }}"> --}}

                                            <button type="submit" class="btn btn-success btn-xs">Detail</button>
                                        </form>
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
