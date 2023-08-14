@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Mengundurkan Diri Manahsiswa Bimbingan</h3>
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
                                <center>Semester</center>
                            </th>
                            <th rowspan="2">
                                <center>Alasan</center>
                            </th>
                            <th rowspan="2">
                                <center>No. HP</center>
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
                                <td align="center">{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->nama }}</td>
                                <td align="center">{{ $item->nim }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td align="center">{{ $item->kelas }}</td>
                                <td align="center">Semester {{ $item->semester_keluar }}</td>
                                <td>{{ $item->alasan }}</td>
                                <td align="center">{{ $item->no_hp }}</td>
                                <td align="center">
                                    @if ($item->val_bauk == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->val_bauk }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->val_bauk }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->val_bauk == 'BELUM')
                                        <span class="badge bg-red">Belum valid</span>
                                    @elseif ($item->val_bauk == 'SUDAH')
                                        @if ($item->val_dsn_pa == 'BELUM')
                                            <a href="/val_pengajuan_kprd/{{ $item->id_trans_pengajuan }}"
                                                class="btn btn-success btn-xs" title="klik untuk validasi"><i
                                                    class="fa fa-check"></i></a>
                                        @elseif ($item->val_dsn_pa == 'SUDAH')
                                            <a href="/batal_val_pengajuan_kprd/{{ $item->id_trans_pengajuan }}"
                                                class="btn btn-danger btn-xs" title="klik untuk batal"><i
                                                    class="fa fa-close"></i></a>
                                        @endif
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
