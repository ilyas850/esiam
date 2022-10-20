@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Detail Sertifikat Mahasiswa Politeknik META Industri</h3>
                        <table width="100%">
                            <tr>
                                <td width="10%">Nama</td>
                                <td width="1%">:</td>
                                <td>{{ $mhs->nama }}</td>
                            </tr>
                            <tr>
                                <td>NIM</td>
                                <td>:</td>
                                <td>{{ $mhs->nim }}
                                </td>
                            </tr>
                            <tr>
                                <td>Program Studi</td>
                                <td> : </td>
                                <td>{{ $mhs->prodi }}
                                </td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>:</td>
                                <td>{{ $mhs->kelas }}
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="box-body">
                        <table id="example8" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <center> No </center>
                                    </th>
                                    <th>
                                        <center>Nama Kegiatan</center>
                                    </th>
                                    <th>
                                        <center>Prestasi</center>
                                    </th>
                                    <th>
                                        <center>Tingkat</center>
                                    </th>
                                    <th>
                                        <center>Tanggal</center>
                                    </th>
                                    <th>
                                        <center>Sertifikat</center>
                                    </th>
                                    <th>
                                        <center>Validasi</center>
                                    </th>
                                    <th>
                                        <center>Jenis Kegiatan</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($data as $item)
                                    <tr>
                                        <td align="center">{{ $no++ }}</td>
                                        <td>{{ $item->nama_kegiatan }}</td>
                                        <td align="center">{{ $item->prestasi }}</td>
                                        <td align="center">{{ $item->tingkat }}</td>
                                        <td align="center">{{ $item->tgl_pelaksanaan }}</td>
                                        <td align="center"><a
                                                href="/Sertifikat/{{ $item->id_student }}/{{ $item->file_sertifikat }}"
                                                target="_blank" style="font: white"> File</a></td>
                                        <td align="center">
                                            @if ($item->validasi == 'BELUM' or $item->validasi == null)
                                                <span class="badge bg-yellow">BELUM</span>
                                            @elseif ($item->validasi == 'SUDAH')
                                                <span class="badge bg-green">SUDAH</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $item->deskripsi }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
