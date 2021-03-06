
<div class="row">
    <div class="col-md-6">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                Validasi Upload Error<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="box box-widget widget-user">
            <div class="widget-user-header bg-aqua-active">
                <h3 class="widget-user-username">{{ Auth::user()->name }}</h3>
                <h5 class="widget-user-desc">Mahasiswa</h5>
            </div>
            <div class="widget-user-image">
                @if ($foto == null)
                    <img class="img-circle" src="/adminlte/img/default.jpg" alt="User Avatar">
                @else
                    <img src="{{ asset('/foto_mhs/' . $foto) }}">
                @endif
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
                        <center>
                            <a href="/ganti_foto/{{ $mhs->nim }}"><span class="fa fa-camera"></span>
                                Ganti foto</a>
                        </center>
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
                        <td style="width:25%">Nama</td>
                        <td style="width:5%">:</td>
                        <td>{{ $mhs->nama }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>NIM</td>
                        <td>:</td>
                        <td>{{ $mhs->nim }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Program Studi</td>
                        <td>:</td>
                        <td>{{ $mhs->prodi }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Kelas</td>
                        <td>:</td>
                        <td>{{ $mhs->kelas }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Angkatan</td>
                        <td>:</td>
                        <td>{{ $mhs->angkatan }}</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>No HP</td>
                        <td>:</td>
                        <td>
                            @if ($mhs->hp_baru == null)
                                {{ $mhs->hp }}
                            @elseif ($mhs->hp_baru != null)
                                {{ $mhs->hp_baru }}
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>E-Mail</td>
                        <td>:</td>
                        <td>
                            @if ($mhs->email_baru == null)
                                {{ $mhs->email }}
                            @elseif ($mhs->email_baru != null)
                                {{ $mhs->email_baru }}
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>NISN</td>
                        <td>:</td>
                        <td>{{ $mhs->nisn }}</td>
                        <td>
                            <button class="btn btn-warning btn-xs" data-toggle="modal"
                                data-target="#modalUpdateNisn{{ $mhs->idstudent }}" title="klik untuk edit"><i
                                    class="fa fa-edit"> Edit NISN</i></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Teams(Username)</td>
                        <td>:</td>
                        <td>
                            {{ $mhs->username }}
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Teams(Password)</td>
                        <td>:</td>
                        <td>
                            {{ $mhs->password }}
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            @if ($mhs->id_mhs == null)
                                <a class="btn btn-success btn-block" href="/update/{{ $mhs->idstudent }}"><i
                                        class="fa fa-edit"></i> Edit No HP dan E-mail</a>
                            @elseif ($mhs->id_mhs != null)
                                <a class="btn btn-success btn-block" href="/change/{{ $mhs->id }}"><i
                                        class="fa fa-edit"></i> Edit data No HP dan E-mail</a>
                            @endif
                        </td>
                    </tr>
                </tbody>

                <div class="modal fade" id="modalUpdateNisn{{ $mhs->idstudent }}" tabindex="-1"
                    aria-labelledby="modalUpdateKaprodi" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Update NISN</h5>
                            </div>
                            <div class="modal-body">
                                <form action="/put_nisn/{{ $mhs->idstudent }}" method="post">
                                    @csrf
                                    @method('put')
                                    <div class="form-group">
                                        <label>NISN Mahasiswa</label>
                                        <input class="form-control" type="number" name="nisn"
                                            value="{{ $mhs->nisn }}">
                                    </div>
                                    <input type="hidden" name="updated_by" value="{{ Auth::user()->name }}">
                                    <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </table>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-calendar"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Tahun Akademik</span>
                <span class="info-box-number">{{ $tahun->periode_tahun }}</span>
                <span class="info-box-number"> {{ $tipe->periode_tipe }}</span>
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
        <div class="box box-info">
            <div class="box-header with-border">
                <span class="glyphicon glyphicon-info-sign"></span>
                <h3 class="box-title">Waktu Pengisian KRS</h3>
            </div>
            <div class="box-body">
                <form role="form">
                    <div id="waktumundur">
                        @if ($time->status != 0)
                            <span id="countdown"></span>
                        @else
                            Belum ada info perwalian
                        @endif
                    </div>
                </form>
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

        <div class="box box-info">
            <div class="box-header with-border">
                <span class="glyphicon glyphicon-info-sign"></span>
                <h3 class="box-title">Waktu Pengisian EDOM</h3>
            </div>
            <div class="box-body">
                <form role="form">
                    <div id="waktumunduredom">
                        @if ($edom->status != 0)
                            <span id="countdownedom"></span>
                        @else
                            Belum ada info pengisian EDOM
                        @endif
                    </div>
                </form>
            </div>
        </div>
        <script type='text/javascript'>
            //<![CDATA[
            var target_date_edom = new Date("{{ $edom->waktu_akhir }}").getTime();
            var days_edom, hours_edom, minutes_edom, seconds_edom;
            var countdownedom = document.getElementById("countdownedom");
            setInterval(function() {
                var current_date_edom = new Date().getTime();
                var seconds_left_edom = (target_date_edom - current_date_edom) / 1000;
                days_edom = parseInt(seconds_left_edom / 86400);
                seconds_left_edom = seconds_left_edom % 86400;
                hours_edom = parseInt(seconds_left_edom / 3600);
                seconds_left_edom = seconds_left_edom % 3600;
                minutes_edom = parseInt(seconds_left_edom / 60);
                seconds_edom = parseInt(seconds_left_edom % 60);
                countdownedom.innerHTML = days_edom + " <span class=\'digit\'>hari</span> " + hours_edom +
                    " <span class=\'digit\'>jam</span> " + minutes_edom + " <span class=\'digit\'>menit</span> " +
                    seconds_edom +
                    " <span class=\'digit\'>detik menuju</span> <span class=\'judul\'>Penutupan Pengisian EDOM</span>";
            }, 1000);
            //]]>
        </script>
        <style scoped="" type="text/css">
            #waktumunduredom {

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
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Informasi Terbaru</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                            class="fa fa-minus"></i>
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
                                @if ($item->file != null)
                                    <img class="img-circle" src="/images/bell.jpg">
                                @else
                                @endif
                            </div>
                            <div class="product-info">
                                <a href="/lihat/{{ $item->id_informasi }}"
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
                <a href="/lihat_semua" class="uppercase">Lihat Semua Informasi</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Paket Matakuliah</h3>
            </div>
            <div class="box-body ">
                <table id="example8" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kurikulum</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                            <th>
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>Status</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $item->nama_kurikulum }}</td>
                                <td align="center">{{ $item->prodi }}</td>
                                <td align="center">{{ $item->semester }}</td>
                                <td align="center">{{ $item->angkatan }}</td>
                                <td>{{ $item->kode }} / {{ $item->makul }}</td>
                                <td align="center">
                                    @if ($item->id_studentrecord != null)
                                        <span class="label label-success">diambil</span>
                                    @else
                                        <span class="label label-warning">belum</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Matakuliah Wajib Ulang</h3>
            </div>
            <div class="box-body ">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>
                                <center>No</center>
                            </th>
                            <th>
                                <center>Kurikulum</center>
                            </th>
                            <th>
                                <center>Prodi</center>
                            </th>
                            <th>
                                <center>Semester</center>
                            </th>
                            <th>
                                <center>Angkatan</center>
                            </th>
                            <th>
                                <center>Matakuliah</center>
                            </th>
                            <th>
                                <center>Nilai</center>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data_mengulang as $item)
                            <tr>
                                <td align="center">{{ $no++ }}</td>
                                <td align="center">{{ $item->nama_kurikulum }}</td>
                                <td align="center">{{ $item->prodi }}</td>
                                <td align="center">{{ $item->semester }}</td>
                                <td align="center">{{ $item->angkatan }}</td>
                                <td>{{ $item->kode }} / {{ $item->makul }}</td>
                                <td align="center">
                                    {{ $item->nilai_AKHIR }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
