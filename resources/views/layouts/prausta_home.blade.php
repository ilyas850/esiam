{{-- <div class="row">
  <div class="col-md-6">
    <div class="alert alert-info alert-dismissible">
      <h4>Visi Politeknik META Industri Cikarang</h4>
      <h4> <b>Visi</b> </h4>
      <h5>{{$visi}}</h5>
    </div>
  </div>
  <div class="col-md-6">
    <div class="alert alert-info alert-dismissible">
      <h4>Misi Politeknik META Industri Cikarang</h4>
      <h4> <b>Misi</b> </h4>
      <h5>{{$misi}}</h5>
    </div>
  </div>
</div> --}}
<div class="row">
    <div class="col-md-6">
        <div class="box box-widget widget-user">
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username">{{ Auth::user()->name }}</h3>
                <h5 class="widget-user-desc">PraUSTA</h5>
            </div>
            <div class="widget-user-image">
                <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
                <div class="row">
                    <div class="col-sm-4 border-right">
                        <div class="description-block">
                            <h5 class="description-header"></h5>
                            <span class="description-text"></span>
                        </div>
                    </div>
                    <div class="col-sm-4 border-right">

                    </div>
                    <div class="col-sm-4">
                        <div class="description-block">
                            <h5 class="description-header"></h5>
                            <span class="description-text"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Tahun Akademik</span>
                <span class="info-box-number">{{ $tahun->periode_tahun }}</span>
                <span class="info-box-number">{{ $tipe->periode_tipe }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-calendar-check-o"></i></span>

            <div class="info-box-content">
                <span class="info-box-text">Jadwal KRS</span>
                <span class="info-box-number">
                    @if ($time->status == 0)
                        Jadwal Belum ada
                    @elseif ($time->status == 1)
                        {{ $time->waktu_awal }} s/d {{ $time->waktu_akhir }}
                    @endif
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-6">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Informasi Terbaru</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i
                            class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    @foreach ($info as $item)
                        <li class="item">
                            <div class="product-img">
                                @if ($item->file != null)
                                    {{-- <a href="{{ asset('/data_file/' . $item->file) }}">File</a> --}}
                                    <img class="img-circle"
                                        src="{{ asset('/data_file/' . $item->file) }}">
                                    @else
                                @endif

                            </div>
                            <div class="product-info">
                                <a href="/lihat/{{ $item->id_informasi }}" class="product-title">{{ $item->judul }}
                                    <span class="label label-info pull-right">
                                        {{ date('l, d F Y', strtotime($item->created_at)) }}<br>
                                        {{ $item->created_at->diffForHumans() }}
                                    </span></a>
                                <span class="product-description">
                                    {{ $item->deskripsi }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
                <a href="/lihat_semua" class="uppercase">Lihat Semua Informasi</a>
            </div>
            <!-- /.box-footer -->
        </div>
    </div>
</div>
