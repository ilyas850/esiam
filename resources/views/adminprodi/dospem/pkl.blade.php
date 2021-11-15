@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
    <div class="box box-danger">
      <div class="box-header with-border">
        <h3 class="box-title">Setting Dosen Pembimbing PKL</h3>
      </div>
      <div class="box-body">
        <form class="form" role="form" action="{{url('view_mhs_bim_pkl')}}" method="POST">
          {{ csrf_field() }}
          <div class="row">
            <div class="col-xs-3">
              <label for="">Angkatan</label>
              <select class="form-control" name="idangkatan" required>
                <option></option>
                @foreach ($angkatan as $keyangk)
                  <option value="{{$keyangk->idangkatan}}">{{$keyangk->angkatan}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-xs-3">
              <label for="">Prodi</label>
              <select class="form-control" name="kodeprodi" required>
                <option></option>
                @foreach ($prodi as $keyprd)
                  <option value="{{$keyprd->kodeprodi}},{{$keyprd->id_masterkode_prausta}}">{{$keyprd->prodi}}</option>
                @endforeach
              </select>
            </div>
          </div>
          <br>
          <div class="row">
            <div class="col-xs-3">
              <button type="submit" class="btn btn-success " >Lihat</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
@endsection
