@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h4 class="box-title">Silahkan pilih matakuliah yang akan diambil</h4>
            </div>
            <div class="box-body">
                <form action="{{ url('save_krs') }}" method="post">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-consended" id="example9">
                                <thead>
                                    <tr>
                                        <th>
                                            <center>Pilih</center>
                                        </th>
                                        <th>Semester</th>
                                        
                                        <th>Matakuliah</th>
                                        <th>SKS</th>
                                        <th>Dosen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($final_krs as $item)
                                        <tr>
                                            <td>
                                                <center>
                                                    <input type="checkbox" name="id_kurperiode[]"
                                                        value="{{ $item->id_kurperiode }},{{ $item->idkurtrans }}">
                                                </center>
                                            </td>
                                            <td>{{ $item->semester }}</td>
                                            
                                            <td>{{ $item->kode }} - {{ $item->makul }}</td>
                                            <td></td>
                                            <td>{{ $item->nama }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <br>
                            <input name="Check_All" value="Tandai Semua" onclick="check_all()" type="button"
                                class="btn btn-success">
                            <input name="Un_CheckAll" value="Hilangkan Semua Tanda" onclick="uncheck_all()" type="button"
                                class="btn btn-danger">
                            <input class="btn btn-info full-right" type="submit" name="submit" value="Simpan">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script language="javascript">
        function check_all() {
            var chk = document.getElementsByName('id_kurperiode[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = true;
        }

        function uncheck_all() {
            var chk = document.getElementsByName('id_kurperiode[]');
            for (i = 0; i < chk.length; i++)
                chk[i].checked = false;
        }
    </script>
@endsection
