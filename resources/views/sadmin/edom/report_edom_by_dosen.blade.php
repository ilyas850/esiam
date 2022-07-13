@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title"> <b> Laporan Rekapitulasi EDOM (Per Dosen)</b></h3>
                <table width="100%">
                    <tr>
                        <td width="20%">Tahun Akademik / Semester</td>
                        <td>:</td>
                        <td>{{ $thn }} / {{ $tp }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <form action="{{ url('download_report_edom_by_dosen') }}" method="POST">
                    {{ csrf_field() }}

                    <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                    <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                    <input type="hidden" name="periodetahun" value="{{ $thn }}">
                    <input type="hidden" name="periodetipe" value="{{ $tp }}">
                    <button type="submit" class="btn btn-danger">Download PDF</button>
                </form>
                <br>
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
                                <center>MAKUL Qty</center>
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
                            <th>
                                <center>Download</center>
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
                                    <center>
                                        {{ $item->makul_qty }}
                                    </center>

                                </td>
                                <td>
                                    <center>
                                        {{ $item->mhs_qty }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $item->edom_qty }}
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
                                        <form action="detail_edom_dosen" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id_dosen" value="{{ $item->id_dosen }}">
                                            <input type="hidden" name="nama" value="{{ $item->nama }}">
                                            <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                                            <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                                            <input type="hidden" name="periodetahun" value="{{ $thn }}">
                                            <input type="hidden" name="periodetipe" value="{{ $tp }}">

                                            <button type="submit" class="btn btn-success btn-xs">Detail</button>
                                        </form>
                                        {{-- <a href="/detail_edom_dosen/{{ $item->id_dosen }}"
                                            class="btn btn-success btn-xs">Detail</a> --}}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        <form action="download_detail_edom_dosen" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id_dosen" value="{{ $item->id_dosen }}">
                                            <input type="hidden" name="nama" value="{{ $item->nama }}">
                                            <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                                            <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                                            <input type="hidden" name="periodetahun" value="{{ $thn }}">
                                            <input type="hidden" name="periodetipe" value="{{ $tp }}">

                                            <button type="submit" class="btn btn-danger btn-xs">PDF</button>
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
