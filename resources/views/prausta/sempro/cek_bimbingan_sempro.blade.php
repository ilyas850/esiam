@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Bimbingan Sempro Mahasiswa</h3>
                <br><br>
                <table width="100%">
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $mhs->nim }}</td>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $mhs->prodi }}</td>
                    </tr>
                    <tr>
                        <td>Nama</td>
                        <td>:</td>
                        <td>{{ $mhs->nama }}</td>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $mhs->kelas }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Bimbingan</th>
                            <th>Uraian Bimbingan</th>
                            <th>Validasi</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $key->tanggal_bimbingan }}</td>
                                <td>{{ $key->remark_bimbingan }}</td>
                                <td>
                                    @if ($key->validasi == 'BELUM')
                                        <span class="badge bg-warning">Belum</span>
                                    @elseif ($key->validasi == 'SUDAH')
                                        <span class="badge bg-blue">Sudah</span>
                                    @endif

                                </td>
                                <td>
                                    @if ($key->file_bimbingan == null)
                                    @elseif ($key->file_bimbingan != null)
                                        <a href="/File Bimbingan SEMPRO/{{ $key->idstudent }}/{{ $key->file_bimbingan }}"
                                            target="_blank"> File bimbingan</a>
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
