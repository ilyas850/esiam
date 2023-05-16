@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Bimbingan Magang Mahasiswa</h3>
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
                    <tr>
                        <td>Dosen Pembimbing</td>
                        <td>:</td>
                        <td>{{ $mhs->dosen_pembimbing }}, {{ $mhs->akademik }}</td>
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
                            <th>Komentar</th>
                            <th>Validasi</th>
                            <th>File</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $key->tanggal_bimbingan }}</td>
                                <td>{{ $key->remark_bimbingan }}</td>
                                <td align="center">
                                    <a class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalTambahKomentar{{ $key->id_transbimb_prausta }}"> <i
                                            class="fa fa-eye "></i> Lihat</a>
                                </td>
                                <td align="center">
                                    @if ($key->validasi == 'BELUM')
                                        <span class="badge bg-warning">Belum</span>
                                    @elseif ($key->validasi == 'SUDAH')
                                        <span class="badge bg-blue">Sudah</span>
                                    @endif

                                </td>
                                <td>
                                    @if ($key->file_bimbingan == null)
                                    @elseif ($key->file_bimbingan != null)
                                        <a href="/File Bimbingan Magang/{{ $key->idstudent }}/{{ $key->file_bimbingan }}"
                                            target="_blank"> File bimbingan</a>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="modalTambahKomentar{{ $key->id_transbimb_prausta }}"
                                tabindex="-1" aria-labelledby="modalTambahKomentar" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Komentar Bimbingan</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <textarea class="form-control" name="komentar_bimbingan" cols="20"
                                                    rows="10"> {{ $key->komentar_bimbingan }} </textarea>
                                            </div>
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </section>
@endsection
