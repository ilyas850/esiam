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
                    <form action="{{ url('save_validasi_all_sertifikat') }}" method="post">
                        {{ csrf_field() }}
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
                                        <center>Sertifikat</center>
                                    </th>
                                    <th>
                                        <center>Jenis Sertifikat</center>
                                    </th>
                                    <th>
                                        <center>Validasi</center>
                                    </th>
                                    <th></th>
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
                                                target="_blank" style="font: white"> File</a></td>
                                        <td>{{ $item->deskripsi }}</td>
                                        <td align="center">
                                            @if ($item->validasi == 'BELUM' or $item->validasi == null)
                                                <a href="/validasi_sertifikat/{{ $item->id_sertifikat }}"
                                                    class="btn btn-info btn-xs">Validasi</a>
                                            @elseif($item->validasi == 'SUDAH')
                                                <a href="/batal_validasi_sertifikat/{{ $item->id_sertifikat }}"
                                                    class="btn btn-danger btn-xs">Batal</a>
                                            @endif
                                        </td>
                                        <td align="center">
                                            <input type="checkbox" name="id_sertifikat[]"
                                                value="{{ $item->id_sertifikat }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        <div class="box-footer">
                            <input name="Check_All" value="Tandai Semua" onclick="check_all()" type="button"
                                class="btn btn-success">
                            <input name="Un_CheckAll" value="Hilangkan Semua Tanda" onclick="uncheck_all()" type="button"
                                class="btn btn-warning">
                            <input class="btn btn-info full-right" type="submit" name="submit" value="Validasi">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script language="javascript">
        function check_all() {
            var chk = document.getElementsByName('id_sertifikat[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = true;
        }

        function uncheck_all() {
            var chk = document.getElementsByName('id_sertifikat[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = false;
        }
    </script>
@endsection
