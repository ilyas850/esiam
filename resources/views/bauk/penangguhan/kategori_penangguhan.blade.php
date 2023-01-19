@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Kategori Penangguhan <b> {{ $thn_aktif->periode_tahun }} -
                                {{ $tp_aktif->periode_tipe }}</b></h3>
                    </div>
                    <div class="box-body">
                        <form class="form" role="form" action="{{ url('pilih_ta_penangguhan') }}" method="POST">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xs-5">
                                    <label>Periode tahun</label>
                                    <select class="form-control" name="id_periodetahun" required>
                                        <option></option>
                                        @foreach ($tahun as $key)
                                            <option value="{{ $key->id_periodetahun }}">
                                                {{ $key->periode_tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xs-4">
                                    <label>Periode tipe</label>
                                    <select class="form-control" name="id_periodetipe" required>
                                        <option></option>
                                        @foreach ($tipe as $tipee)
                                            <option value="{{ $tipee->id_periodetipe }}">{{ $tipee->periode_tipe }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-info ">Tampilkan</button>
                        </form>
                        <br>
                        <table id="example8" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>
                                        <center> No</center>
                                    </th>
                                    <th>
                                        <center>Kategori</center>
                                    </th>
                                    <th>
                                        <center>Jumlah</center>
                                    </th>
                                    <th>
                                        <center>Aksi</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                @foreach ($data as $item)
                                    <tr>
                                        <td align="center">{{ $no++ }}</td>
                                        <td>{{ $item->kategori }}</td>
                                        <td align="center">{{ $item->jml_penangguhan }}</td>
                                        <td align="center">
                                            <a href="data_penangguhan_bauk/{{ $item->id_penangguhan_kategori }}"
                                                class="btn btn-info btn-xs">Cek</a>
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
