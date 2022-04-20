@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">KUISIONER KEPUASAN MAHASISWA TERHADAP EVALUASI DOSEN PEMBIMBING AKADEMIK </h3>
                <br><br>
                <table width="100%">
                    <tr>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $prodi }}</td>
                    </tr>
                    <tr>
                        <td>Dosen Pembimbing</td>
                        <td>:</td>
                        <td>{{ $nama_dsn }}</td>
                    </tr>
                    <tr>
                        <td>Semester</td>
                        <td>:</td>
                        <td>{{ $periodetipe }}</td>
                    </tr>
                    <tr>
                        <td>Tahun Akademik</td>
                        <td>:</td>
                        <td>{{ $periodetahun }}</td>
                    </tr>
                </table>
            </div>
            <div class="box-body ">
                <form action="{{ url('save_kuisioner_dsn_pa') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="id_student" value="{{ $ids }}">
                    <input type="hidden" name="id_dosen_pembimbing" value="{{ $id_dsn }}">
                    <input type="hidden" name="id_periodetahun" value="{{ $idthn }}">
                    <input type="hidden" name="id_periodetipe" value="{{ $idtp }}">
                    <table class="table table-striped">
                        <tr>
                            <th style="width: 10px;" align="center">No</th>
                            <th>Aspek</th>
                            <th>Komponen</th>
                            <th>Nilai</th>
                        </tr>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->aspek_kuisioner }}</td>
                                <td>{{ $item->komponen_kuisioner }}</td>
                                <td>
                                    <center>
                                        <select class="form-control" name="nilai[]" required>
                                            <option></option>
                                            <option value="{{ $item->id_kuisioner }},1">Tidak Baik</option>
                                            <option value="{{ $item->id_kuisioner }},2">Kurang Baik</option>
                                            <option value="{{ $item->id_kuisioner }},3">Baik</option>
                                            <option value="{{ $item->id_kuisioner }},4">Sangat Baik</option>
                                        </select>
                                    </center>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <div class="form-group">
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info btn-block">
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
