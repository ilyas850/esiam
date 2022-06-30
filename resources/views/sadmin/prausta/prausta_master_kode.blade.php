@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">PRAUSTA Master Kode</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Kode Prausta</center>
                            </th>
                            <th>
                                <center>Nama Prausta</center>
                            </th>
                            <th>
                                <center>Tipe Prausta</center>
                            </th>
                            <th>
                                <center>SKS Prausta</center>
                            </th>
                            <th>
                                <center>Min. Bimbingan</center>
                            </th>
                            <th>
                                <center>Transkrip Nilai</center>
                            </th>
                            <th>
                                <center>Batas Waktu</center>
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
                                <td>{{ $key->prodi }}</td>
                                <td align="center">{{ $key->kode_prausta }}</td>
                                <td>{{ $key->nama_prausta }}</td>
                                <td align="center">{{ $key->tipe_prausta }}</td>
                                <td align="center">{{ $key->sks_prausta }} sks</td>
                                <td align="center">{{ $key->min_bimbingan }} kali</td>
                                <td align="center">{{ $key->transkrip_nilai }}</td>
                                <td align="center">{{ $key->batas_waktu }} hari</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </section>
@endsection
