@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Data User Mahasiswa Politeknik META Industri</h3>
                    </div>
                    <form action="{{ url('save_generate_user') }}" method="post">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <table id="example3" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <center>No</center>
                                        </th>
                                        <th>Mahasiswa</th>
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
                                            <center>Status</center>
                                        </th>
                                        <th>
                                            <center>Aksi</center>
                                        </th>
                                        <th>
                                            <center>Pilih</center>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php $no = 1; ?>
                                    @foreach ($users as $item)
                                        <tr>
                                            <td>
                                                <center>{{ $no++ }}</center>
                                            </td>
                                            <td>
                                                {{ $item->nim }} / {{ $item->nama }}</td>

                                            <td>
                                                <center>{{ $item->prodi }}</center>
                                            </td>
                                            <td>
                                                <center>{{ $item->kelas->kelas }}</center>
                                            </td>
                                            <td>
                                                <center>{{ $item->angkatan->angkatan }}</center>
                                            </td>
                                            <td>
                                                <center>
                                                    @if (optional($item->user)->role == 3)
                                                        Mahasiswa Aktif
                                                    @elseif (optional($item->user)->role == 4)
                                                        Belum Aktif
                                                    @else
                                                        Status Tidak Diketahui
                                                    @endif
                                                </center>
                                            </td>
                                            <td>
                                                <center>
                                                    @if (empty($item->user->username))
                                                        <form action="{{ url('saveuser_mhs') }}" method="post">
                                                            <input type="hidden" name="role" value="4">
                                                            <input type="hidden" name="student"
                                                                value="{{ $item->idstudent }}">
                                                            <input type="hidden" name="username"
                                                                value="{{ $item->nim }}">
                                                            <input type="hidden" name="name"
                                                                value="{{ $item->nama }}">
                                                            {{ csrf_field() }}
                                                            <button type="submit"
                                                                class="btn btn-info btn-xs">Generate</button>
                                                        </form>
                                                    @elseif(!empty($item->user->username))
                                                        <div style="display: flex; gap: 5px; justify-content: center;">
                                                            <form method="POST" action="{{ url('resetuser') }}">
                                                                <input type="hidden" name="role" value="4">
                                                                <input type="hidden" name="password"
                                                                    value="{{ $item->user->username }}">
                                                                <input type="hidden" name="id"
                                                                    value="{{ $item->user->id }}">
                                                                {{ csrf_field() }}
                                                                <button type="submit" class="btn btn-success btn-xs"
                                                                    data-toggle="tooltip" data-placement="right"
                                                                    title="klik untuk reset password">
                                                                    <i class="fa fa-refresh"></i>
                                                                </button>
                                                            </form>

                                                            <form action="/hapususer/{{ $item->id_user }}" method="post">
                                                                <button class="btn btn-danger btn-xs" type="submit"
                                                                    name="submit"
                                                                    onclick="return confirm('apakah anda yakin akan menghapus user ini?')"
                                                                    title="klik untuk hapus user">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                                {{ csrf_field() }}
                                                                <input type="hidden" name="_method" value="DELETE">
                                                            </form>
                                                        </div>
                                                    @endif
                                                </center>
                                            </td>
                                            <td align="center">

                                                <input type="checkbox" name="student[]" value="{{ $item->idstudent }}">
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
                            <input class="btn btn-info full-right" type="submit" name="submit" value="Generate">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <script language="javascript">
        function check_all() {
            var chk = document.getElementsByName('student[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = true;
        }

        function uncheck_all() {
            var chk = document.getElementsByName('student[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = false;
        }
    </script>
@endsection
