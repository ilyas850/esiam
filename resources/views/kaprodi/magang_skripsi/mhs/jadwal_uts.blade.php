@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Jadwal UTS</h3>
            </div>
            <div class="box-body table-responsive no-padding">
                <table class="table table-striped">
                    <tr>
                        <th>Jadwal Ujian</th>
                        <th>Waktu Ujian</th>
                        <th>Kode</th>
                        <th>Matakuliah</th>
                        <th>Ruangan</th>
                    </tr>
                    @foreach ($jadwal_uts as $key)
                        <tr>
                            <td>
                                {{ date('l, d F Y', strtotime($key->tanggal_ujian)) }}
                            </td>
                            <td>
                                {{ $key->jam }} - {{ date('H:i', strtotime($key->jam) + 60 * 90) }}
                            </td>
                            <td>
                                {{ $key->kode }}
                            </td>
                            <td>
                                {{ $key->makul }}
                            </td>
                            <td>
                                {{ $key->nama_ruangan }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </section>
@endsection
