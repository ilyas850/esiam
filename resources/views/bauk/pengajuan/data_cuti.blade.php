@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Pengajuan Cuti</h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px" rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Tgl. Pengajuan</center>
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
                                <center>SKS Tempuh</center>
                            </th>
                            <th rowspan="2">
                                <center>Cuti sebelumnya</center>
                            </th>
                            <th rowspan="2">
                                <center>Alasan</center>
                            </th>
                            <th rowspan="2">
                                <center>No. HP</center>
                            </th>
                            <th rowspan="2">
                                <center>Alamat</center>
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
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $item->tgl_pengajuan }}</td>
                                <td align="center">{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->nama }}</td>
                                <td align="center">{{ $item->nim }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td align="center">{{ $item->kelas }}</td>
                                <td align="center">{{ $item->sks_ditempuh }}SKS</td>
                                <td align="center">{{ $item->cuti_sebelumnya }}</td>
                                <td>{{ $item->alasan }}</td>
                                <td align="center">{{ $item->no_hp }}</td>
                                <td>{{ $item->alamat }}</td>
                                <td align="center">
                                    @if ($item->val_bauk == 'BELUM')
                                        <a href="/validasi_pengajuan_cuti_bauk/{{ $item->id_trans_pengajuan }}"
                                            class="btn btn-success btn-xs" title="klik untuk validasi"><i
                                                class="fa fa-check"></i></a>
                                    @elseif ($item->val_bauk == 'SUDAH')
                                        <a href="/batal_validasi_pengajuan_cuti_bauk/{{ $item->id_trans_pengajuan }}"
                                            class="btn btn-info btn-xs" title="klik untuk batal"><i
                                                class="fa fa-rotate-left"></i></a>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->val_dsn_pa == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->val_dsn_pa }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->val_dsn_pa }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->val_kaprodi == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->val_kaprodi }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->val_kaprodi }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->val_baak == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->val_baak }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->val_baak }}</span>
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
