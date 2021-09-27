@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
<section class="content">
    <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title">Data Matakuliah</h3>
        </div>
        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="4%"><center>No</center></th>
                        <th width="8%"><center>Kode </center></th>
                        <th width="20%"><center>Matakuliah</center></th>
                        <th width="15%"><center>Program Studi</center></th>
                        <th width="10%"><center>Kelas</center></th>
                        <th width="10%"><center>Semester</center></th>
                        <th width="8%"></th>
                      </tr>
                </thead>
                <tbody>
                    <?php $no=1; ?>
                    @foreach ($data as $item)
                        <tr>
                            <td><center>{{$no++}}</center></td>
                            <td><center>{{$item->kode}}</center></td>
                            <td>{{$item->makul}}</td>
                            <td><center>{{$item->prodi}}</center></td>
                            <td><center>{{$item->kelas}}</center></td>
                            <td><center>{{$item->semester}}</center></td>
                            <td><center>
                                <a href="cek_jurnal_bap_wadir/{{$item->id_kurperiode}}" class="btn btn-warning btn-xs"> Jurnal BAP</a>
                            </center></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
