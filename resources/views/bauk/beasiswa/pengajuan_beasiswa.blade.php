@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Pengajuan Beasiswa <b> {{ $thn_aktif->periode_tahun }} -
                        {{ $tp_aktif->periode_tipe }}</b></h3>
            </div>
            <div class="box-body">
                <form action="{{ url('export_excel_pengajuan_beasiswa') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_periodetahun" value="{{ $thn_aktif->id_periodetahun }}">
                    <input type="hidden" name="id_periodetipe" value="{{ $tp_aktif->id_periodetipe }}">
                    <button type="submit" class="btn btn-success">Export Excel</button>
                </form>
                <br>
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Tahun Akademik</center>
                            </th>
                            <th>
                                <center>Nama</center>
                            </th>
                            <th>
                                <center>NIM</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Kelas</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                            <th>
                                <center>IPK</center>
                            </th>
                            <th>
                                <center>Beasiswa</center>
                            </th>
                            <th>
                                <center>Validasi</center>
                            </th>
                            <th>
                                <center>KHS</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->nama }}</td>
                                <td align="center">{{ $item->nim }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td align="center">{{ $item->kelas }}</td>
                                <td align="center">{{ $item->semester }}</td>
                                <td align="center">
                                    {{ $item->ipk }}
                                </td>
                                <td align="center">
                                    @if ($item->beasiswa == null)
                                        <center>
                                            <button class="btn btn-success btn-xs" data-toggle="modal"
                                                data-target="#modalTambahKomentar{{ $item->id_trans_beasiswa }}">Input</button>
                                        </center>
                                    @elseif($item->beasiswa != null)
                                        {{ $item->beasiswa }}%
                                        <button class="btn btn-warning btn-xs fa-pull-right" data-toggle="modal"
                                            data-target="#modalTambahKomentar{{ $item->id_trans_beasiswa }}"><i
                                                class="fa fa-edit"></i></button>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->validasi_bauk == 'BELUM')
                                        <a href="/val_pengajuan_beasiswa_bauk/{{ $item->id_trans_beasiswa }}"
                                            class="btn btn-success btn-xs"><i class="fa fa-check"></i></a>
                                    @elseif ($item->validasi_bauk == 'SUDAH')
                                        <a href="/batal_val_pengajuan_beasiswa_bauk/{{ $item->id_trans_beasiswa }}"
                                            class="btn btn-danger btn-xs"><i class="fa fa-close"></i></a>
                                    @endif
                                </td>
                                <td align="center">
                                    <form action="{{ url('download_khs_by_bauk') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_student" value="{{ $item->idstudent }}">
                                        <input type="hidden" name="id_periodetahun" value="{{ $item->id_periodetahun }}">
                                        <input type="hidden" name="id_periodetipe" value="{{ $item->id_periodetipe }}">
                                        <button class="btn btn-primary btn-xs"><i class="fa fa-download"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <div class="modal fade" id="modalTambahKomentar{{ $item->id_trans_beasiswa }}" tabindex="-1"
                                aria-labelledby="modalTambahKomentar" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Beasiswa Mahasiswa Semester {{ $item->semester }}</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_beasiswa/{{ $item->id_trans_beasiswa }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label>Beasiswa (%)</label>
                                                    <input type="varchar" class="form-control" name="beasiswa"
                                                        value="{{ $item->beasiswa }}"
                                                        placeholder="Masukan jumlah beasiswa Ex. 50" required>
                                                </div>
                                                <input type="hidden" name="id_student" value="{{ $item->idstudent }}">
                                                <input type="hidden" name="id_semester" value="{{ $item->id_semester }}">

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
