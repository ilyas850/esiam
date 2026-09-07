@extends('layouts.master')

@section('side')

    @include('layouts.side')

@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-users"></i> Data Peserta KRS Mahasiswa</h3>
                <div class="box-tools pull-right">
                    <a href="{{ url('data_krs') }}" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Kembali ke Rekap KRS</a>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table id="example1" class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr class="bg-gray-light">
                            <th style="width: 40px; text-align: center;">No</th>
                            <th style="width: 120px; text-align: center;">NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th style="width: 80px; text-align: center;">Kelas</th>
                            <th style="width: 80px; text-align: center;">Angkatan</th>
                            <th style="width: 80px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @forelse ($data as $item)
                            <tr>
                                <td align="center" style="vertical-align: middle;">{{ $no++ }}</td>
                                <td align="center" style="vertical-align: middle;"><strong>{{ $item->nim }}</strong></td>
                                <td style="vertical-align: middle;">{{ $item->nama }}</td>
                                <td style="vertical-align: middle;">
                                    <strong>{{ $item->prodi }}</strong>
                                    @if (!empty($item->konsentrasi) && $item->konsentrasi != '-')
                                        <br><small class="text-muted"><i class="fa fa-tag"></i> {{ $item->konsentrasi }}</small>
                                    @endif
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label label-info">{{ $item->kelas }}</span>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    <span class="label label-default">{{ $item->angkatan }}</span>
                                </td>
                                <td align="center" style="vertical-align: middle;">
                                    @if ($item->status == 'TAKEN')
                                        <a class="btn btn-danger btn-xs btn-flat"
                                            href="{{ url('batalkrs/' . $item->id_studentrecord) }}"
                                            onclick="return confirm('Apakah Anda yakin ingin membatalkan KRS untuk mahasiswa {{ $item->nama }} ({{ $item->nim }})?');">
                                            <i class="fa fa-times"></i> Batal
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="padding: 20px;">
                                    <i class="fa fa-info-circle"></i> Tidak ada mahasiswa yang terdaftar di kelas ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
