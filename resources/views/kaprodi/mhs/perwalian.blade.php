@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                Tabel Bimbingan Perwalian
            </div>
            <div class="box-body">


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
                                <center>Tanggal Bimbingan</center>
                            </th>
                            <th>
                                <center>Uraian Bimbingan</center>
                            </th>
                            <th>
                                <center>Validasi</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $key)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $key->periode_tahun }} - {{ $key->periode_tipe }}</td>
                                <td align="center">{{ $key->tanggal_bimbingan }}</td>
                                <td>{{ $key->isi_bimbingan }}</td>
                                <td align="center">
                                    @if ($key->validasi == 'BELUM' or $key->validasi == null)
                                        <a href="/val_bim_perwalian_kprd/{{ $key->id_transbim_perwalian }}"
                                            class="btn btn-info btn-xs">Validasi</a>
                                    @elseif ($key->validasi == 'SUDAH')
                                        <span class="badge bg-blue">Sudah</span>
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
