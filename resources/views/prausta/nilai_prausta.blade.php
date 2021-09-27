@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection

@section('content')
  <section class="content">
      <div class="box box-danger">
          <div class="box-header with-border">
              <h3 class="box-title">PraUSTA Mahasiswa</h3>
          </div>
          <form class="form" role="form" action="{{url('kode_prausta')}}" method="POST">
              {{ csrf_field() }}
              <div class="box-body">
                  <div class="row">
                      <div class="col-xs-3">
                          <label for="">Kode PraUSTA</label>
                          <select class="form-control" name="idmakul">
                              <option></option>
                              @foreach ($makul as $makul)
                                  <option value="{{$makul->idmakul}}">{{$makul->kode}} - {{$makul->makul}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-xs-3">
                          <label for="">Program Studi</label>
                          <select class="form-control" name="kodeprodi" required>
                              <option></option>
                              @foreach ($prodi as $prd)
                                  <option value="{{$prd->kodeprodi}}">{{$prd->prodi}}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-xs-3">
                          <label for="">Angkatan</label>
                          <select class="form-control" name="idangkatan" required>
                              <option></option>
                              @foreach ($angkatan as $tipee)
                                  <option value="{{$tipee->idangkatan}}">{{$tipee->angkatan}}</option>
                              @endforeach
                          </select>
                      </div>

                  </div>
              </div>
              <div class="box-footer">
                  <button type="submit" class="btn btn-success" >Filter</button>
              </div>
          </form>
      </div>
  </section>
@endsection
