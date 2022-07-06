@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Report Kuisioner Dosen Pembimbing PKL</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="1%">
                                <center>No</center>
                            </th>
                            <th width="30%">
                                <center>Dosen</center>
                            </th>
                            <th width="10%">
                                <center>Mhs Qty</center>
                            </th>
                            <th width="10%">
                                <center>Kuisioner Qty</center>
                            </th>
                            <th width="10%">
                                <center>Nilai Angka</center>
                            </th>
                            <th width="10%">
                                <center>Nilai Huruf</center>
                            </th>
                            <th width="5%">
                                <center>Aksi</center>
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
                                    {{ $item->nama }}
                                </td>
                                <td align="center">{{ $item->mhs_qty }}</td>
                                <td align="center">{{ $item->kuisioner_qty }}</td>
                                <td align="center">{{ $item->nilai_angka }}</td>
                                <td align="center">
                                    @if ($item->nilai_angka >= 80)
                                        A
                                    @elseif ($item->nilai_angka >= 75)
                                        B+
                                    @elseif ($item->nilai_angka >= 70)
                                        B
                                    @elseif ($item->nilai_angka >= 65)
                                        C+
                                    @elseif ($item->nilai_angka >= 60)
                                        C
                                    @elseif ($item->nilai_angka >= 50)
                                        D
                                    @elseif ($item->nilai_angka >= 0)
                                        E
                                    @endif
                                </td>
                                <td>
                                    <center>
                                        <form action="{{ url('detail_kuisioner_dsn_pkl') }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="id_dosen" value="{{ $item->iddosen }}">
                                            <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                                            <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                                            <input type="hidden" name="periodetahun" value="{{ $namaperiodetahun }}">
                                            <input type="hidden" name="periodetipe" value="{{ $namaperiodetipe }}">

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
