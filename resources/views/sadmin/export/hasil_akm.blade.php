@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Export Excel</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-xs-3">

                        <form action="{{ url('export_data_akm_xls') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_prodi" value="{{ $idprodi }}">
                            <input type="hidden" name="id_periodetahun" value="{{ $idperiodetahun }}">
                            <input type="hidden" name="id_periodetipe" value="{{ $idperiodetipe }}">
                            <button type="submit" class="btn btn-success">Export Excel</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data AKM Mahasiswa Politeknik META Industri <b>{{ $namaperiodetahun }} -
                        {{ $namaperiodetipe }} - {{ $namaprodi }}</b></h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px">
                                <center>No</center>
                            </th>
                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Nama</center>
                            </th>
                            <th>
                                <center>Status</center>
                            </th>
                            <th>
                                <center>SKS SEMESTER</center>
                            </th>
                            <th>
                                <center>IPS</center>
                            </th>
                            <th>
                                <center>TOTAL SKS</center>
                            </th>
                            <th>
                                <center>IPK</center>
                            </th>
                            <th>
                                <center>BIAYA</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data_akm as $key)
                            <tr>
                                <td>
                                    <center>{{ $no++ }}</center>
                                </td>
                                <td>
                                    <center>{{ $key->nim }}</center>
                                </td>
                                <td>{{ $key->nama }}</td>
                                <td>
                                    <center>
                                        @if ($key->status_mahasiswa == 'Aktif')
                                            @if ($key->sks_semester > 0)
                                                Aktif
                                            @elseif($key->sks_semester == 0)
                                                Non-Aktif
                                            @endif
                                        @elseif ($key->status_mahasiswa == 'Cuti')
                                            Cuti
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->sks_semester }} SKS
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->ips }}
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        {{ $key->total_sks }} SKS
                                    </center>
                                </td>
                                <td>
                                    <center>{{ $key->ipk }}</center>
                                </td>
                                <td>
                                    <center>@currency($key->biaya)</center>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
