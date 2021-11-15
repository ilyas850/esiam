@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <div class="box box-info">
      <div class="box-header">
          <h3 class="box-title">Data Dosen Pembimbing PKL</h3>
      </div>
      <div class="box-body">
        <form class="form" role="form" action="{{url('save_dsn_bim_pkl')}}" method="POST">
          {{ csrf_field() }}
          <table class="table table-condensed">
            <thead>
                <tr>
                    <th width="1%"><center>No</center></th>
                    <th width="10%"><center>NIM </center></th>
                    <th width="40%"><center>Nama</center></th>
                    <th width="400%"><center>Dosen Pembimbing</center></th>
                </tr>
            </thead>
            <tbody>
              <?php $no=1; ?>
              @foreach ($datas as $keydsn)
                <tr>
                  <td><center>{{$no++}}</center></td>
                  <td><center>{{$keydsn->nim}}</center></td>
                  <td>{{$keydsn->nama}}</td>
                  <td><center>

                    @if ($keydsn->dosen_pembimbing == null)
                        <select  name="iddosen[]" required>
                          <option></option>
                          @foreach ($dosen as $keyangk)
                            <option value="{{$keydsn->idstudent}},{{$keyangk->iddosen}},{{$keyangk->nama}}">{{$keyangk->nama}}</option>
                          @endforeach
                        </select>
                    @elseif ($keydsn->dosen_pembimbing != null)
                      {{$keydsn->dosen_pembimbing}}
                    @endif
                  </center></td>
                </tr>
                <input type="hidden" name="id_masterkode_prausta" value="{{$id2}}">

              @endforeach
            </tbody>
          </table>

          <button class="btn btn-info" type="submit">Simpan</button>
        </form>
      </div>
    </div>
  </section>
@endsection
