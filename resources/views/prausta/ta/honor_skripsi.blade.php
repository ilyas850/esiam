@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-danger">
            <div class="box-header">
                <h3 class="box-title">Pilih Tipe</h3>
            </div>
            <div class="box-body">
                <a href="/data_honor_ta_mahasiswa" class="btn btn-info">Data Honor TA</a>
                <a href="/data_honor_skripsi_mahasiswa" class="btn btn-success">Data Honor Skripsi</a>
            </div>
        </div>
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Filter Data Honor Skripsi Mahasiswa</h3>
            </div>
            <form class="form" role="form" action="{{ url('filter_honor_skripsi') }}" method="POST">
                {{ csrf_field() }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-3">
                            <label for="">Prodi</label>
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodi as $prd)
                                    <option value="{{ $prd->kodeprodi }}">{{ $prd->prodi }}</option>
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
                <h3 class="box-title">Data Honor Skripsi</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Mahasiswa/NIM</th>
                            <th rowspan="2">Prodi</th>
                            <th colspan="3">
                                <center>Dosen</center>
                            </th>
                            <th colspan="3">
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
                                <center>Penguji II</center>
                            </th>
                            <th>
                                <center>Pembimbing</center>
                            </th>
                            <th>
                                <center>Penguji I</center>
                            </th>
                            <th>
                                <center>Penguji II</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->dosen_pembimbing }}</td>
                                <td>{{ $item->dosen_penguji_1 }}</td>
                                <td>{{ $item->dosen_penguji_2 }}</td>
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
                                <td align="center">
                                    @if ($item->payroll_check_dosen_penguji_2 == 'SUDAH')
                                        <span class="label label-info">SUDAH</span>
                                    @elseif ($item->payroll_check_dosen_penguji_2 == 'BELUM')
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
