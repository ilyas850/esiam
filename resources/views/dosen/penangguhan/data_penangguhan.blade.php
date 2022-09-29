@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Penangguhan</h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px" rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Tahun Akademik</center>
                            </th>
                            <th rowspan="2">
                                <center>Nama</center>
                            </th>
                            <th rowspan="2">
                                <center>NIM</center>
                            </th>
                            <th rowspan="2">
                                <center>Prodi</center>
                            </th>
                            <th rowspan="2">
                                <center>Kelas</center>
                            </th>
                            <th rowspan="2">
                                <center>Jenis Penangguhan</center>
                            </th>
                            <th rowspan="2">
                                <center>Total Tunggakan</center>
                            </th>
                            <th rowspan="2">
                                <center>Rencana Pembayaran</center>
                            </th>
                            <th rowspan="2">
                                <center>Alasan</center>
                            </th>
                            <th colspan="4">
                                <center>Validasi</center>
                            </th>

                        </tr>
                        <tr>
                            <th>
                                <center>BAUK</center>
                            </th>
                            <th>
                                <center>Dosen PA</center>
                            </th>
                            <th>
                                <center>Kaprodi</center>
                            </th>
                            <th>
                                <center>BAAK</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->kelas }}</td>
                                <td align="center">{{ $item->kategori }}</td>
                                <td align="right">
                                    @currency ( $item->total_tunggakan )
                                </td>
                                <td>{{ $item->rencana_bayar }}</td>
                                <td>{{ $item->alasan }}</td>
                                <td align="center">
                                    @if ($item->validasi_bauk == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->validasi_bauk }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->validasi_bauk }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->validasi_bauk == 'BELUM')
                                        <span class="badge bg-red">Belum valid</span>
                                    @elseif ($item->validasi_bauk == 'SUDAH')
                                        @if ($item->validasi_dsn_pa == 'BELUM')
                                            <a href="/val_penangguhan_dsn_pa/{{ $item->id_penangguhan_trans }}"
                                                class="btn btn-info btn-xs">Validasi</a>
                                        @elseif ($item->validasi_dsn_pa == 'SUDAH')
                                            <a href="/batal_val_penangguhan_dsn_pa/{{ $item->id_penangguhan_trans }}"
                                                class="btn btn-warning btn-xs">Batal</a>
                                        @endif
                                    @endif

                                </td>
                                <td align="center">
                                    @if ($item->validasi_kaprodi == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->validasi_kaprodi }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->validasi_kaprodi }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->validasi_baak == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->validasi_baak }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->validasi_baak }}</span>
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
