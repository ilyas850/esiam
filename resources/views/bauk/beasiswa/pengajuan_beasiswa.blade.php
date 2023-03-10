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
                                <center>Validasi BAUK</center>
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
                                    @if ($item->validasi_bauk == 'BELUM')
                                        {{-- <form action="{{ url('val_penangguhan_bauk') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_penangguhan_trans"
                                                value="{{ $item->id_penangguhan_trans }}">
                                            <input type="hidden" name="id_penangguhan_kategori"
                                                value="{{ $kategori->id_penangguhan_kategori }}">
                                            <input type="hidden" name="id_periodetahun"
                                                value="{{ $thn_aktif->id_periodetahun }}">
                                            <input type="hidden" name="id_periodetipe"
                                                value="{{ $tp_aktif->id_periodetipe }}">
                                            <button type="submit" class="btn btn-info btn-xs">Validasi</button>
                                        </form> --}}
                                        <a href="/val_pengajuan_beasiswa_bauk/{{ $item->id_trans_beasiswa }}"
                                            class="btn btn-info btn-xs">Validasi</a>
                                    @elseif ($item->validasi_bauk == 'SUDAH')
                                        {{-- <form action="{{ url('batal_val_penangguhan_bauk') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_penangguhan_trans"
                                                value="{{ $item->id_penangguhan_trans }}">
                                            <input type="hidden" name="id_penangguhan_kategori"
                                                value="{{ $kategori->id_penangguhan_kategori }}">
                                            <input type="hidden" name="id_periodetahun"
                                                value="{{ $thn_aktif->id_periodetahun }}">
                                            <input type="hidden" name="id_periodetipe"
                                                value="{{ $tp_aktif->id_periodetipe }}">
                                            <button type="submit" class="btn btn-warning btn-xs">Batal</button>
                                        </form> --}}

                                        <a href="/batal_val_pengajuan_beasiswa_bauk/{{ $item->id_trans_beasiswa }}"
                                            class="btn btn-warning btn-xs">Batal</a>
                                    @endif
                                </td>
                            </tr>
                            {{-- <div class="modal fade" id="modalTambahKomentar{{ $item->id_penangguhan_trans }}"
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
                                                <input type="hidden" name="id_penangguhan_kategori"
                                                    value="{{ $kategori->id_penangguhan_kategori }}">
                                                <input type="hidden" name="id_periodetahun"
                                                    value="{{ $thn_aktif->id_periodetahun }}">
                                                <input type="hidden" name="id_periodetipe"
                                                    value="{{ $tp_aktif->id_periodetipe }}">
                                                <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
