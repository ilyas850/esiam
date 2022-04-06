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
                    <div class="box-body">
                        <table id="example3" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="3%">
                                        <center>No</center>
                                    </th>
                                    <th width="35%">Nama Mahasiswa</th>
                                    <th width="10%">
                                        <center>NIM</center>
                                    </th>
                                    <th width="15%">
                                        <center>Program Studi</center>
                                    </th>
                                    <th width="15%">
                                        <center>Kelas</center>
                                    </th>
                                    <th>
                                        <center>Status</center>
                                    </th>
                                    <th>
                                        <center>Aksi</center>
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
                                        <td>{{ $item->nama }}</td>
                                        <td>
                                            <center>{{ $item->nim }}</center>
                                        </td>
                                        <td>
                                            <center>{{ $item->prodi }}</center>
                                        </td>
                                        <td>
                                            <center>{{ $item->kelas }}</center>
                                        </td>
                                        <td>
                                            <center>
                                                @if ($item->role == 3)
                                                    Mahasiswa Aktif
                                                @elseif ($item->role == 4)
                                                    Belum Aktif
                                                @endif
                                            </center>
                                        </td>
                                        <td>
                                            <center>
                                                @if ($item->username == null)
                                                    <a class="btn btn-info btn-xs"
                                                        href="/usermhs/{{ $item->nim }}">Generate</a>
                                                @elseif($item->username != null)
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs">Pilih</button>
                                                        <button type="button" class="btn btn-warning btn-xs dropdown-toggle"
                                                            data-toggle="dropdown">
                                                            <span class="caret"></span>
                                                            <span class="sr-only">Toggle Dropdown</span>
                                                        </button>
                                                        <ul class="dropdown-menu" role="menu">
                                                            <li>
                                                                <form method="POST" action="{{ url('resetuser') }}">
                                                                    <input type="hidden" name="role" value="4">
                                                                    <input type="hidden" name="password"
                                                                        value="{{ $item->username }}">
                                                                    <input type="hidden" name="id"
                                                                        value="{{ $item->id }}">
                                                                    {{ csrf_field() }}
                                                                    <button type="submit"
                                                                        class="btn btn-success btn-block btn-xs"
                                                                        data-toggle="tooltip"
                                                                        data-placement="right">Reset</button>
                                                                </form>
                                                            </li>
                                                            <li>
                                                                <form action="/hapususer/{{ $item->id_user }}"
                                                                    method="post">
                                                                    <button class="btn btn-danger btn-block btn-xs"
                                                                        title="klik untuk hapus" type="submit" name="submit"
                                                                        onclick="return confirm('apakah anda yakin akan menghapus user ini?')">Hapus</button>
                                                                    {{ csrf_field() }}
                                                                    <input type="hidden" name="_method" value="DELETE">
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                @endif
                                            </center>
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
