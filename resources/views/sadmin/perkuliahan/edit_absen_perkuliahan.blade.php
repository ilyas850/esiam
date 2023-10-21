@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Absensi Mahasiswa</h3>
            </div>
            <div class="box-body">
                <form action="{{ url('save_edit_absensi_admin') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_bap" value="{{ $id }}">
                    <div class="form-group">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <center>No</center>
                                    </th>
                                    <th>
                                        <center>NIM </center>
                                    </th>
                                    <th>
                                        <center>Nama</center>
                                    </th>
                                    <th>
                                        <center>Program Studi</center>
                                    </th>
                                    <th>
                                        <center>Kelas</center>
                                    </th>
                                    <th>
                                        <center>Angkatan</center>
                                    </th>
                                    <th>
                                        <center>Absensi</center>
                                    </th>
                                    <th>
                                        <center>Pilih</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>
                                            <center>{{ $no++ }}</center>
                                        </td>
                                        <td>
                                            <center>{{ $item->nim }}</center>
                                        </td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->prodi }}</td>
                                        <td>
                                            <center>{{ $item->kelas }}</center>
                                        </td>
                                        <td>
                                            <center>{{ $item->angkatan }}</center>
                                        </td>
                                        <td align="center">
                                            @if ($item->absensi == 'ABSEN')
                                                HADIR
                                            @elseif($item->absensi == 'IZIN')
                                                IZIN
                                            @elseif($item->absensi == 'SAKIT')
                                                SAKIT
                                            @elseif($item->absensi == 'ALFA')
                                                ALFA
                                            @elseif($item->absensi == 'HADIR')
                                                TIDAK HADIR
                                            @endif
                                        </td>
                                        <td align="center">
                                            <input type="checkbox" name="id_absensi[]" value="{{ $item->id_absensi }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <input name="Check_All" value="Tandai Semua" onclick="check_all()" type="button"
                            class="btn btn-success">
                        <input name="Un_CheckAll" value="Hilangkan Semua Tanda" onclick="uncheck_all()" type="button"
                            class="btn btn-warning">
                        <input class="btn btn-danger full-right" type="submit" name="submit" value="Hapus">
                    </div>

                </form>
            </div>
        </div>
    </section>
    <script language="javascript">
        function check_all() {
            var chk = document.getElementsByName('id_absensi[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = true;
        }

        function uncheck_all() {
            var chk = document.getElementsByName('id_absensi[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = false;
        }
    </script>
@endsection
