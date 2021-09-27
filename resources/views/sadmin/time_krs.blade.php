@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')

<section class="content">
  <div class="row">
    <div class="col-md-6">
          <div class="box box-primary">
            <div class="box-header">
              <h3 class="box-title"><b>KRS Periode {{$thn->periode_tahun}} {{$tp->periode_tipe}}</b></h3>
            </div>
            <form class="form-horizontal" role="form" method="POST" action="{{ url('/post_time_krs') }}">
                {{ csrf_field() }}

            <div class="box-body">
              @if ($time->status == 1)
              <div class="form-group">
                <label>Atur Waktu KRS:</label>

                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" id="reservation">
                </div>

              </div>
            @else
            @endif
        </div>
      </div>
      </div>
    <div class="col-md-6">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/post_time_krs') }}">
            {{ csrf_field() }}

          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title"><b>KRS Periode {{$thn->periode_tahun}} {{$tp->periode_tipe}}</b></h3>
            </div>
            <div class="box-body">
              @if ($time->status == 1)
							<input type="hidden" name="status" value="0">
							<input type="hidden" name="id" value="{{$time->id}}">
                <button type="button" class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target=".bs-example-modal-sm">KRS Berhenti</button>
                <div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <h4 class="modal-title" id="myModalLabel">Peringatan</h4>
                        </div>
                        <div class="modal-body">
                          <p>Apakah anda yakin akan memberhentikan KRS yang sedang berjalan ?</p>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                          <button type="submit" class="btn btn-primary">Lanjutkan</button>
                        </div>
                      </div>
                    </div>
                  </div>
									@else

                    <!-- Date range -->
                    <div class="form-group">
                      <label>Atur Waktu KRS:</label>

                      <div class="input-group">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" class="form-control pull-right" name="waktu" id="reservation" value="{{ $time->waktu }}" required>
                      </div>
                    </div>
                    <input type="hidden" name="status" value="1">
                    <input type="hidden" name="id" value="{{$time->id}}">
                          <button type="submit" class="btn btn-info btn-lg btn-block">
                              KRS Dimulai
                          </button>
						@endif
          </div>
        </form>
    </div>

  </div>

</section>

@endsection
