@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Soal UTS</h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kode </center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>Dosen</center>
                            </th>
                            <th>
                                <center>Program Studi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Soal UTS</center>
                            </th>
                            <th>
                                <center>Tipe Ujian</center>
                            </th>
                            <th>
                                <center>Komentar</center>
                            </th>
                            <th>
                                <center>Validasi</center>
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
                                    <center>{{ $item->kode }}</center>
                                </td>
                                <td>{{ $item->makul }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>
                                    <center>{{ $item->prodi }}</center>
                                </td>
                                <td>
                                    <center>{{ $item->kelas }}</center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->soal_uts == null)
                                            <span class="badge bg-yellow">Belum</span>
                                        @else
                                            <a href="/Soal Ujian/UTS/{{ $item->id_kurperiode }}/{{ $item->soal_uts }}"
                                                target="_blank" style="font: white">File</a>
                                        @endif

                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->tipe_ujian_uts == null)
                                            <span class="badge bg-yellow">Belum</span>
                                        @else
                                            {{ $item->tipe_ujian_uts }}
                                        @endif
                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->soal_uts == null)
                                            <span class="badge bg-yellow">Belum</span>
                                        @else
                                            @if ($item->komentar_uts == null)
                                                <button class="btn btn-info btn-xs" data-toggle="modal"
                                                    data-target="#modalTambahKomentar{{ $item->id_soal }}">Tambah</button>
                                            @else
                                                <a class="btn btn-success btn-xs" data-toggle="modal"
                                                    data-target="#modalTambahKomentar{{ $item->id_soal }}"> <i
                                                        class="fa fa-eye "></i> Lihat</a>
                                            @endif
                                        @endif

                                    </center>
                                </td>
                                <td>
                                    <center>
                                        @if ($item->soal_uts == null)
                                            <span class="badge bg-yellow">Belum</span>
                                        @else
                                            @if ($item->validasi_uts == 'BELUM' or $item->validasi_uts == null)
                                                <a href="/val_soal_uts/{{ $item->id_soal }}"
                                                    class="btn btn-info btn-xs">Validasi</a>
                                            @elseif ($item->validasi_uts == 'SUDAH')
                                                <span class="badge bg-blue">Sudah</span>
                                            @endif
                                        @endif
                                    </center>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalTambahKomentar{{ $item->id_soal }}" tabindex="-1"
                                aria-labelledby="modalTambahKomentar" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Komentar</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/komentar_soal_uts/{{ $item->id_soal }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <textarea class="form-control" name="komentar_uts" cols="20" rows="10"> {{ $item->komentar_uts }} </textarea>
                                                </div>
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan
                                                </button>
                                            </form>
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
