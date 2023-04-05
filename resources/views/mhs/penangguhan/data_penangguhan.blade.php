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
                @if ($status_penangguhan->status == 0 or $status_penangguhan->status == null)
                    <div class="form-group">
                        <div class="callout callout-warning">
                            <p>Waktu Penangguhan Belum dibuka</p>
                        </div>
                    </div>
                @else
                    <div class="form-group">
                        <div class="callout callout-info">
                            <p> {{ Carbon\Carbon::parse($status_penangguhan->waktu_awal)->formatLocalized('%A, %d %B %Y') }}
                                s/d
                                {{ Carbon\Carbon::parse($status_penangguhan->waktu_akhir)->formatLocalized('%A, %d %B %Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-2">
                            <button type="button" class="btn btn-success mr-5" data-toggle="modal"
                                data-target="#addsertifikat">
                                Input Data Penangguhan
                            </button>
                        </div>
                    </div>
                    <br>
                @endif

                <div class="modal fade" id="addsertifikat" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="post" action="{{ url('post_penangguhan') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Input Data Penangguhan</h5>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Jenis Penangguhan</label>
                                        <select name="id_penangguhan_kategori" class="form-control">
                                            <option></option>
                                            @foreach ($kategori_penangguhan as $kategori)
                                                <option value="{{ $kategori->id_penangguhan_kategori }}">
                                                    {{ $kategori->kategori }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Rencana Pembayaran</label>
                                        <input type="text" name="rencana_bayar" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Alasan</label>
                                        <textarea name="alasan" class="form-control" cols="10" rows="5" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
                            <th rowspan="2">
                                <center>Aksi</center>
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
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->kategori }}</td>
                                <td align="right">@currency ( $item->total_tunggakan )</td>
                                <td>{{ $item->rencana_bayar }}</td>
                                <td>{{ $item->alasan }}</td>
                                <td align="center">
                                    @if ($item->validasi_bauk == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->validasi_bauk }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->validasi_bauk }}</span>
                                    @endif

                                </td>
                                <td align="center">
                                    @if ($item->validasi_dsn_pa == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->validasi_dsn_pa }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->validasi_dsn_pa }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->validasi_kaprodi == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->validasi_kaprodi }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->validasi_kaprodi }}</span>
                                    @endif
                                </td>
                                <td align="center">
                                    @if ($item->validasi_baak == 'BELUM')
                                        <span class="badge bg-yellow">{{ $item->validasi_baak }}</span>
                                    @else
                                        <span class="badge bg-green">{{ $item->validasi_baak }}</span>
                                    @endif
                                </td>
                                <td align="center">


                                    @if ($item->validasi_bauk == 'BELUM')
                                        <button class="btn btn-success btn-xs" data-toggle="modal"
                                            data-target="#modalUpdateSertifikat{{ $item->id_penangguhan_trans }}"
                                            title="klik untuk edit"><i class="fa fa-edit"></i></button>
                                        <a class="btn btn-danger btn-xs"
                                            href="/batal_penangguhan/{{ $item->id_penangguhan_trans }}"
                                            onclick="return confirm('anda yakin akan mebatalkan ini ?')"><i
                                                class="fa fa-trash"></i></a>
                                    @elseif ($item->validasi_dsn_pa == 'SUDAH' && $item->validasi_kaprodi == 'SUDAH' && $item->validasi_baak == 'SUDAH')
                                        @if ($item->id_penangguhan_kategori == 1)
                                            <a href="penangguhan_krs/{{ $item->id_penangguhan_trans }}"
                                                class="btn btn-info btn-xs">KRS</a>
                                        @elseif($item->id_penangguhan_kategori == 2)
                                            @if ($item->status_penangguhan == 'OPEN')
                                                <a href="penangguhan_absen_ujian/{{ $item->id_penangguhan_trans }}"
                                                    class="btn btn-primary btn-xs">Absen</a>
                                            @endif
                                            {{-- <a href="penangguhan_kartu_uts/{{ $item->id_penangguhan_trans }}"
                                                class="btn btn-warning btn-xs">UTS</a> --}}
                                        @elseif($item->id_penangguhan_kategori == 3)
                                            @if ($item->status_penangguhan == 'OPEN')
                                                <a href="penangguhan_absen_ujian/{{ $item->id_penangguhan_trans }}"
                                                    class="btn btn-primary btn-xs">Absen</a>
                                            @endif
                                            {{-- <a href="penangguhan_kartu_uas/{{ $item->id_penangguhan_trans }}"
                                                class="btn btn-danger btn-xs">UAS</a> --}}
                                        @elseif($item->id_penangguhan_kategori == 4)
                                            <a href="penangguhan_yudisium/{{ $item->id_penangguhan_trans }}"
                                                class="btn btn-primary btn-xs">Yudisium</a>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            <div class="modal fade" id="modalUpdateSertifikat{{ $item->id_penangguhan_trans }}"
                                tabindex="-1" aria-labelledby="modalUpdateSertifikat" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Penangguhan</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/put_penangguhan/{{ $item->id_penangguhan_trans }}"
                                                method="post" enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="form-group">
                                                    <label>Jenis Penangguhan</label>
                                                    <select name="id_penangguhan_kategori" class="form-control">
                                                        <option value="{{ $item->id_penangguhan_kategori }}">
                                                            {{ $item->kategori }}</option>
                                                        @foreach ($kategori_penangguhan as $kategori)
                                                            <option value="{{ $kategori->id_penangguhan_kategori }}">
                                                                {{ $kategori->kategori }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Rencana Pembayaran</label>
                                                    <input type="text" name="rencana_bayar" class="form-control"
                                                        value="{{ $item->rencana_bayar }}" required>
                                                </div>
                                                <div class="form-group">
                                                    <label>Alasan</label>
                                                    <textarea name="alasan" class="form-control" cols="10" rows="5" required>{{ $item->alasan }}</textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Perbarui Data</button>
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
