@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <form action="{{ url('save_jenis_sertifikat') }}" method="post">
                    {{ csrf_field() }}
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

                        <div class="box-body table-responsive no-padding">
                            <table class="table table-hover">
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
                                        <center>Tanggal Pelaksanaan</center>
                                    </th>
                                    <th>
                                        <center>File Sertifikat</center>
                                    </th>
                                    <th>
                                        <center>Jenis Kegiatan</center>
                                    </th>
                                </tr>
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
                                                target="_blank" style="font: white"> File Sertifikat</a></td>
                                        <td>
                                            @if ($item->id_jeniskegiatan == null)
                                                <select name="id_jeniskegiatan[]">
                                                    <option></option>
                                                    @foreach ($jenis as $item_jns)
                                                        <option
                                                            value="{{ $item->id_sertifikat }},{{ $item_jns->id_jeniskegiatan }}">
                                                            {{ $item_jns->deskripsi }}</option>
                                                    @endforeach
                                                </select>
                                            @elseif ($item->id_jeniskegiatan != null)
                                                <select name="id_jeniskegiatan[]">
                                                    <option value="{{ $item->id_sertifikat }},{{ $item->id_jeniskegiatan }}">{{ $item->deskripsi }}
                                                    </option>
                                                    @foreach ($jenis as $item_jns)
                                                        <option
                                                            value="{{ $item->id_sertifikat }},{{ $item_jns->id_jeniskegiatan }}">
                                                            {{ $item_jns->deskripsi }}</option>
                                                    @endforeach
                                                </select>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="box-footer">
                            <input class="btn btn-success btn-block" type="submit" name="submit" value="Simpan">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
