@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Pengajuan Beasiswa</h3>
            </div>
            <div class="box-body">
                @if ($status_pengajuan->status == 0 or $status_pengajuan->status == null)
                    <div class="form-group">
                        <div class="callout callout-warning">
                            <p>Waktu Pengajuan Beasiswa Belum dibuka</p>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <div class="callout callout-info">
                            <p>{{ Carbon\Carbon::parse($status_pengajuan->waktu_awal)->formatLocalized('%d %B %Y') }} s/d
                                {{ Carbon\Carbon::parse($status_pengajuan->waktu_akhir)->formatLocalized('%d %B %Y') }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-2">
                            <a href="{{ url('pengajuan_beasiswa') }}" class="btn btn-success">Pengajuan Beasiswa</a>
                        </div>
                    </div>
                    <br>
                @endif
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                            <th>
                                <center>IPK</center>
                            </th>
                            <th>
                                <center>Validasi BAUK</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td align="center">{{ $item->semester }}</td>
                                <td align="center">{{ $item->ipk }}</td>
                                <td align="center">
                                    @if ($item->validasi_bauk == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->validasi_bauk }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->validasi_bauk }}</span>
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
