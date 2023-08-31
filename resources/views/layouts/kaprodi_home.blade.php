<div class="row">
    <div class="col-md-12">
        <div
            class="alert
          @if ($prd->kodeprodi == 22 or $prd->kodeprodi == 25) alert-info
          @elseif ($prd->kodeprodi == 23)
            alert-danger
          @elseif ($prd->kodeprodi == 24)
            alert-success @endif
          alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-smile-o"></i>Selamat Datang</h4>
            <h3><b>KAPRODI {{ $prd->prodi }} ({{ $prd->nama }})</b></h3>
        </div>
    </div>
</div>
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
                <p>Mahasiswa TRPL</p>
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
        <div class="box box-widget widget-user">
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username">{{ Auth::user()->name }}</h3>
                <h5 class="widget-user-desc">Dosen</h5>
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
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th style="width:35%">Nama</th>
                        <td style="width:5%">:</td>
                        <td>{{ $dsn->nama }}, {{ $dsn->akademik }} </td>
                    </tr>
                    <tr>
                        <th>NIK</th>
                        <td>:</td>
                        <td>{{ Auth::user()->username }}</td>
                    </tr>
                    <tr>
                        <th>Tempat, tanggal lahir</th>
                        <td>:</td>
                        <td>{{ $dsn->tmptlahir }}, {{ $dsn->tgllahir }}</td>
                    </tr>
                    <tr>
                        <th>Agama</th>
                        <td>:</td>
                        <td>{{ $dsn->agama }}</td>
                    </tr>
                    <tr>
                        <th>Jenis kelamin</th>
                        <td>:</td>
                        <td>{{ $dsn->kelamin }}</td>
                    </tr>
                    <tr>
                        <th>No HP</th>
                        <td>:</td>
                        <td>{{ $dsn->hp }}</td>
                    </tr>
                    <tr>
                        <th>E-Mail</th>
                        <td>:</td>
                        <td>{{ $dsn->email }}</td>
                    </tr>
                </tbody>
            </table>
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
                        {{ date(' d-m-Y', strtotime($time->waktu_awal)) }} s/d
                        {{ date(' d-m-Y', strtotime($time->waktu_akhir)) }}
                    @endif
                </span>
            </div>
        </div>
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
            <div class="box-body">
                <ul class="products-list product-list-in-box">
                    @foreach ($info as $item)
                        <li class="item">
                            <div class="product-img">
                                <img class="img-circle" src="/images/bell.jpg" alt="user">
                            </div>
                            <div class="product-info">
                                <a href="/lihat_kprd/{{ $item->id_informasi }}"
                                    class="product-title">{{ $item->judul }}
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
            <div class="box-footer text-center">
                <a href="/lihat_semua_kprd" class="uppercase">Lihat Semua Informasi</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Mahasiswa Mengulang</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mahasiswa</th>
                            <th>Matakuliah</th>
                            <th>Nilai</th>
                            <th>Tahun Akademik</th>
                            <th>Dosen</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($makul_mengulang as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td>{{ $item->mhs }}</td>
                                <td>{{ $item->makul }}</td>
                                <td align="center">{{ $item->nilai_AKHIR }}</td>
                                <td>{{ $item->periode_tahun }}-{{ $item->periode_tipe }}</td>
                                <td>{{ $item->nama }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
