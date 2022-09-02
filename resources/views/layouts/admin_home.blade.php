<div class="row">
    <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>{{ $ti }} orang</h3>
                <p>Mahasiswa Teknik Industri</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>{{ $tk }} orang</h3>
                <p>Mahasiswa Teknologi Rekayasa Perangkat Lunak</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>{{ $fa }} orang</h3>
                <p>Mahasiswa Farmasi</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header with-border">
                <span class="fa fa-calendar"></span>
                <h3 class="box-title">Tambah Tahun Akademik Aktif</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('add_ta') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-xs-7">
                            <input type="text" class="form-control" name="periode_tahun" placeholder="T.A.2019/2020"
                                required>
                        </div>
                        <input type="hidden" name="status" value="ACTIVE">
                        <button type="submit" class="btn btn-info ">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Periode Tahun</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Periode Tahun</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tahun as $item)
                            <tr>
                                <td>{{ $item->periode_tahun }}</td>
                                <td>{{ $item->status }}</td>
                                <td>
                                    @if ($item->status == 'ACTIVE')
                                        <span class="badge bg-yellow">AKTIF</span>
                                    @elseif ($item->status == 'NOT ACTIVE')
                                        <form method="POST" action="{{ url('change_ta_thn') }}">
                                            <input type="hidden" name="status" value="ACTIVE">
                                            <input type="hidden" name="id_periodetahun"
                                                value="{{ $item->id_periodetahun }}">
                                            {{ csrf_field() }}
                                            <button type="submit" class="btn btn-info btn-xs">Aktifkan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Periode Tipe</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Periode Tipe</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    @foreach ($tipe as $item)
                        <tr>
                            <td>{{ $item->periode_tipe }}</td>
                            <td>{{ $item->status }}</td>
                            <td>
                                @if ($item->status == 'ACTIVE')
                                    <span class="badge bg-yellow">AKTIF</span>
                                    {{-- <form method="POST" action="{{url('change_ta_tp')}}">
                 <input type="hidden" name="status" value="NOT ACTIVE">
                 <input type="hidden" name="id_periodetipe" value="{{$item->id_periodetipe}}">
                 {{ csrf_field() }}
                 <button type="submit" class="btn btn-warning btn-xs">Nonaktifkan</button>
               </form> --}}
                                @elseif ($item->status == 'NOT ACTIVE')
                                    <form method="POST" action="{{ url('change_ta_tp') }}">
                                        <input type="hidden" name="status" value="ACTIVE">
                                        <input type="hidden" name="id_periodetipe" value="{{ $item->id_periodetipe }}">
                                        {{ csrf_field() }}
                                        <button type="submit" class="btn btn-info btn-xs">Aktifkan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <span class="fa fa-calendar-check-o"></span>
                <h3 class="box-title"><b>KRS Periode @foreach ($tahun as $key)
                            @if ($key->status == 'ACTIVE')
                                {{ $key->periode_tahun }}
                            @endif
                        @endforeach
                        @foreach ($tipe as $key)
                            @if ($key->status == 'ACTIVE')
                                {{ $key->periode_tipe }}
                            @endif
                        @endforeach
                    </b></h3>
            </div>
            <div class="box-body">
                @if ($time->status == 0)
                    <form method="POST" action="{{ url('save_krs_time') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Waktu Awal KRS:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" class="form-control" id="datepicker3"
                                            value="{{ $now }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Atur Waktu Akhir KRS:</label>
                                    <div class="input-group">
                                        <span class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <input type="text" class="form-control" id="datepicker" name="waktu_akhir"
                                            value="{{ $time->waktu_akhir }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="1">
                        <input type="hidden" name="waktu_awal" value="{{ $now }}">
                        <input type="hidden" name="id" value="{{ $time->id }}">
                        <button type="submit" class="btn btn-info btn-lg btn-block">
                            KRS Dibuka
                        </button>
                    </form>
                @elseif ($time->status == 1)
                    <form method="POST" action="{{ url('delete_time_krs') }}">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label>Hentikan Waktu Pengisian KRS:</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right"
                                    value="{{ $time->waktu_awal }} sampai {{ $time->waktu_akhir }}" readonly>
                            </div>
                        </div>
                        <input type="hidden" name="status" value="0">
                        <input type="hidden" name="id" value="{{ $time->id }}">
                        <button type="button" class="btn btn-warning btn-lg btn-block" data-toggle="modal"
                            data-target="#modal-warning">
                            Tutup Pengisian KRS
                        </button>
                        <div class="modal modal-warning fade" id="modal-warning">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Peringatan</h4>
                                    </div>
                                    <div class="modal-body">
                                        <p>Apakah anda yakin akan menutup pengisian KRS ?&hellip;</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline pull-left"
                                            data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-outline">Simpan</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->
                        </div>

                    </form>
                @endif

            </div>
        </div>
        <div class="box box-info">
            <div class="box-header with-border">
                <span class="glyphicon glyphicon-info-sign"></span>
                <h3 class="box-title">Informasi Pengisian KRS</h3>
            </div>
            <div class="box-body">
                <div id="waktumundur">
                    @if ($time->status != 0)
                        <span id="countdown"></span>
                    @else
                        Belum ada info perwalian
                    @endif
                </div>
            </div>
            <script type='text/javascript'>
                //<![CDATA[
                var target_date = new Date("{{ $time->waktu_akhir }}").getTime();
                var days, hours, minutes, seconds;
                var countdown = document.getElementById("countdown");
                setInterval(function() {
                    var current_date = new Date().getTime();
                    var seconds_left = (target_date - current_date) / 1000;
                    days = parseInt(seconds_left / 86400);
                    seconds_left = seconds_left % 86400;
                    hours = parseInt(seconds_left / 3600);
                    seconds_left = seconds_left % 3600;
                    minutes = parseInt(seconds_left / 60);
                    seconds = parseInt(seconds_left % 60);
                    countdown.innerHTML = days + " <span class=\'digit\'>hari</span> " + hours +
                        " <span class=\'digit\'>jam</span> " + minutes + " <span class=\'digit\'>menit</span> " + seconds +
                        " <span class=\'digit\'>detik menuju</span> <span class=\'judul\'>Penutupan Pengisian KRS</span>";
                }, 1000);
                //]]>
            </script>
        </div>
        <style scoped="" type="text/css">
            #waktumundur {
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
    </div>
