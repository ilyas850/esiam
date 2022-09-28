@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Penangguhan</h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="4px" rowspan="2">
                                <center>No</center>
                            </th>
                            <th rowspan="2">
                                <center>Tahun Akademik</center>
                            </th>
                            <th rowspan="2">
                                <center>Nama</center>
                            </th>
                            <th rowspan="2">
                                <center>NIM</center>
                            </th>
                            <th rowspan="2">
                                <center>Prodi</center>
                            </th>
                            <th rowspan="2">
                                <center>Kelas</center>
                            </th>
                            <th rowspan="2">
                                <center>Jenis Penangguhan</center>
                            </th>
                            <th rowspan="2">
                                <center>Total Tunggakan</center>
                            </th>
                            <th rowspan="2">
                                <center>Rencana Pembayaran</center>
                            </th>
                            <th rowspan="2">
                                <center>Alasan</center>
                            </th>
                            <th colspan="4">
                                <center>Validasi</center>
                            </th>

                        </tr>
                        <tr>
                            <th>
                                <center>BAUK</center>
                            </th>
                            <th>
                                <center>Dosen PA</center>
                            </th>
                            <th>
                                <center>Kaprodi</center>
                            </th>
                            <th>
                                <center>BAAK</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->nama }}</td>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->kelas }}</td>
                                <td align="center">{{ $item->kategori }}</td>
                                <td align="right">
                                    @if ($item->total_tunggakan == null)
                                        <center>
                                            <button class="btn btn-info btn-xs" data-toggle="modal"
                                                data-target="#modalTambahKomentar{{ $item->id_penangguhan_trans }}">Edit
                                                Tunggakan</button>
                                        </center>
                                    @elseif($item->total_tunggakan != null)
                                        @currency ( $item->total_tunggakan )
                                        <button class="btn btn-warning btn-xs" data-toggle="modal"
                                            data-target="#modalTambahKomentar{{ $item->id_penangguhan_trans }}"><i
                                                class="fa fa-edit"></i></button>
                                    @endif

                                </td>
                                <td>{{ $item->rencana_bayar }}</td>
                                <td>{{ $item->alasan }}</td>
                                <td align="center">
                                    @if ($item->validasi_bauk == 'BELUM')
                                        <a href="/val_penangguhan_bauk/{{ $item->id_penangguhan_trans }}"
                                            class="btn btn-info btn-xs">Validasi</a>
                                    @elseif ($item->validasi_bauk == 'SUDAH')
                                        <a href="/batal_val_penangguhan_bauk/{{ $item->id_penangguhan_trans }}"
                                            class="btn btn-warning btn-xs">Batal</a>
                                    @endif
                                </td>
                                <td align="center">{{ $item->validasi_dsn_pa }}</td>
                                <td align="center">{{ $item->validasi_kaprodi }}</td>
                                <td align="center">{{ $item->validasi_baak }}</td>
                            </tr>
                            <div class="modal fade" id="modalTambahKomentar{{ $item->id_penangguhan_trans }}"
                                tabindex="-1" aria-labelledby="modalTambahKomentar" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tunggakan</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_tunggakan/{{ $item->id_penangguhan_trans }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label>Nominal Tunggakan</label>
                                                    <input type="number" class="form-control" name="total_tunggakan"
                                                        value="{{ $item->total_tunggakan }}" required>
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
