@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter Data Honor PKL Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_honor_pkl') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Periode Tahun</label>
                            <select class="form-control" name="id_periodetahun" required>
                                <option></option>
                                @foreach ($prd_thn as $thn)
                                    <option value="{{ $thn->id_periodetahun }}">{{ $thn->periode_tahun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <label for="">Periode Tipe</label>
                            <select class="form-control" name="id_periodetipe" required>
                                <option></option>
                                @foreach ($prd_tp as $tipee)
                                    <option value="{{ $tipee->id_periodetipe }}">{{ $tipee->periode_tipe }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-success">Filter</button>
                </div>
            </form>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Honor PKL <b> {{ $namaperiodetahun }} - {{ $namaperiodetipe }} </b></h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Mahasiswa/NIM</th>
                            <th rowspan="2">Prodi</th>
                            <th colspan="2">
                                <center>Dosen</center>
                            </th>
                            <th colspan="2">
                                <center>Honor</center>
                            </th>

                        </tr>
                        <tr>
                            <th>
                                <center>Pembimbing</center>
                            </th>
                            <th>
                                <center>Penguji I</center>
                            </th>
                            <th>
                                <center>Pembimbing</center>
                            </th>
                            <th>
                                <center>Penguji I</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->nama }}/{{ $item->nim }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->dosen_pembimbing }}</td>
                                <td>{{ $item->dosen_penguji_1 }}</td>
                                <td align="center">
                                    @if ($item->payroll_check_dosen_pembimbing == 'SUDAH')
                                        <span class="label label-info">SUDAH</span>
                                    @elseif ($item->payroll_check_dosen_pembimbing == 'BELUM')
                                        <span class="label label-warning">BELUM</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->payroll_check_dosen_penguji_1 == 'SUDAH')
                                        <span class="label label-info">SUDAH</span>
                                    @elseif ($item->payroll_check_dosen_penguji_1 == 'BELUM')
                                        <span class="label label-warning">BELUM</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
