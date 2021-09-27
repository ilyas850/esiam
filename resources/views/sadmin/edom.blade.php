@extends('layouts.master')

@section('side')

  @include('layouts.side')

@endsection
@section('content')
  <section class="content">
  <div class="box box-info">
    <div class="box-header with-border">
      <span class="fa fa-calendar-check-o"></span>
      <h3 class="box-title"><b>Pengisian EDOM Periode @foreach ($tahun as $key)
        @if ($key->status == 'ACTIVE')
          {{$key->periode_tahun}}
        @endif
      @endforeach
      @foreach ($tipe as $key)
        @if ($key->status == 'ACTIVE')
          {{$key->periode_tipe}}
        @endif
      @endforeach
      </b></h3>
    </div>
    <div class="box-body">
      @if ($edom->status == 0)
      <form class="form" role="form" method="POST" action="{{ url('simpanedom') }}">
        {{ csrf_field() }}
            <div class="form-group">
              <div class="row">
                <div class="col-lg-6">
                  <label>Atur Waktu Awal pengisian EDOM:</label>
                  <div class="input-group">
                    <span class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </span>
                    <input type="text" class="form-control"  value="{{ $now }}" disabled>

                  </div>
                </div>
                <div class="col-lg-6">
                  <label>Atur Waktu Akhir pengisian EDOM:</label>
                    <div class="input-group">
                      <span class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                      </span>
                      <input type="text" class="form-control" id="datepicker" name="waktu_akhir" required>
                    </div>
                </div>
              </div>
            </div>
            <input type="hidden" name="status" value="1">
            <input type="hidden" name="waktu_awal" value="{{$now}}">
            <input type="hidden" name="id" value="{{$edom->id}}">
            <button type="submit" class="btn btn-info btn-lg btn-block">
                Pengisian EDOM Dimulai
            </button>
        </form>
      @elseif ($edom->status == 1)
        <form class="form" role="form" method="POST" action="{{ url('edit_time_edom') }}">
        {{ csrf_field() }}
              <div class="form-group">
                <label>Hentikan Waktu Pengisian EDOM:</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" class="form-control pull-right" value="{{ $edom->waktu_awal }} sampai {{ $edom->waktu_akhir}}" readonly>
                </div>
              </div>
              <input type="hidden" name="status" value="0">
              <input type="hidden" name="id" value="{{$edom->id}}">
              <button type="button" class="btn btn-warning btn-lg btn-block" data-toggle="modal" data-target=".bs-example-modal-sm">Penutupan Pengisian EDOM</button>
              <div class="modal fade bs-example-modal-sm" id="myModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myModalLabel">Peringatan</h4>
                    </div>
                    <div class="modal-body">
                      <p>Apakah anda yakin akan memberhentikan pengisian EDOM yang sedang berjalan ?</p>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                      <button type="submit" class="btn btn-primary">Lanjutkan</button>
                    </div>
                  </div>
                </div>
              </div>
          @endif
      </form>
    </div>
  </div>
  <div class="box box-info">
    <div class="box-header with-border">
      <span class="glyphicon glyphicon-info-sign"></span>
      <h3 class="box-title">Informasi Pengisian EDOM</h3>
    </div>
    <div class="box-body">
      <div id="waktumundur">
        @if ($edom->status != 0)
          <span id="countdown"></span>
        @else
          Belum ada info pengisian EDOM
        @endif
      </div>
    </div>

    <script type = 'text/javascript' >
      //<![CDATA[
      var target_date = new Date("{{$edom->waktu_akhir}}").getTime();
      var days, hours, minutes, seconds;
      var countdown = document.getElementById("countdown");
      setInterval(function () {
       var current_date = new Date().getTime();
       var seconds_left = (target_date - current_date) / 1000;
       days = parseInt(seconds_left / 86400);
       seconds_left = seconds_left % 86400;
       hours = parseInt(seconds_left / 3600);
       seconds_left = seconds_left % 3600;
       minutes = parseInt(seconds_left / 60);
       seconds = parseInt(seconds_left % 60);
       countdown.innerHTML = days + " <span class=\'digit\'>hari</span> " + hours + " <span class=\'digit\'>jam</span> " + minutes + " <span class=\'digit\'>menit</span> " + seconds + " <span class=\'digit\'>detik menuju</span> <span class=\'judul\'>Penutupan Pengisian EDOM</span>";
      }, 1000);
      //]]>
    </script>
  </div>

    <style scoped="" type="text/css"> #waktumundur {
     background: #31266b;
     color: #fec503;
     font-size: 100%;
     text-transform: uppercase;
     text-align: center;
     padding: 20px 0;
     font-weight: bold;
     border-radius: 5px;
     line-height: 1.8em;
     font-family: Arial, sans-serif;
    }
    .digit {
     color: white
    }
    .judul {
     color: white
    }
    </style>

</section>
@endsection
