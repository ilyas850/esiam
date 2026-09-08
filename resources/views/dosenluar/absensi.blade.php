@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-check-square-o"></i> Data Absensi Mahasiswa</h3>
                <button type="button" class="btn btn-warning btn-sm pull-right" data-toggle="modal" data-target="#modal-lintas-kelas">
                    <i class="fa fa-user-plus"></i> Tambah Mahasiswa Lintas Kelas
                </button>
            </div>
            <form action="{{ url('save_absensi_dsn') }}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="id_kurperiode" value="{{ $idk }}">
                <input type="hidden" name="id_bap" value="{{ $id }}">
                <div class="box-body">
                    <table class="table table-bordered table-striped" id="example5">
                        <thead>
                            <tr>
                                <th>
                                    <center>No</center>
                                </th>
                                <th>
                                    <center>NIM </center>
                                </th>
                                <th>
                                    <center>Nama</center>
                                </th>
                                <th>
                                    <center>Program Studi</center>
                                </th>
                                <th>
                                    <center>Kelas</center>
                                </th>
                                <th>
                                    <center>Angkatan</center>
                                </th>
                                <th>
                                    <center>Hadir</center>
                                </th>
                                <th>
                                    <center>Alpa</center>
                                </th>
                                <th>
                                    <center>Izin</center>
                                </th>
                                <th>
                                    <center>Sakit</center>
                                </th>
                                <th>
                                    <center>Aksi</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($absen as $item)
                                <tr class="{{ !empty($item->hadir_di_kelas_lain) ? 'info row-hadir-kelas-lain' : '' }}">
                                    <td>
                                        <center>{{ $no++ }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $item->nim }}</center>
                                    </td>
                                    <td>
                                        {{ $item->nama }}
                                        @if(!empty($item->hadir_di_kelas_lain))
                                            <span class="label label-primary pull-right" style="font-size:10px;"><i class="fa fa-exchange"></i> Lintas Kelas</span>
                                            <br>
                                            <span class="label label-info" style="font-size: 11px;">
                                                <i class="fa fa-check-circle"></i> {{ $item->keterangan_kelas_lain }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $item->prodi }}</td>
                                    <td>
                                        <center>{{ $item->kelas }}</center>
                                    </td>
                                    <td>
                                        <center>{{ $item->angkatan ?? '-' }}</center>
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                    value="{{ $item->id_studentrecord }},ABSEN" checked>
                                            </label>
                                        </div>
                                        @if(!empty($item->hadir_di_kelas_lain))
                                            <small class="text-success" style="font-weight: bold; display:block;">
                                                <i class="fa fa-check"></i> Otomatis
                                            </small>
                                        @endif
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                    value="{{ $item->id_studentrecord }},ALFA">
                                            </label>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                    value="{{ $item->id_studentrecord }},IZIN">
                                            </label>
                                        </div>
                                    </td>
                                    <td align="center">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                    value="{{ $item->id_studentrecord }},SAKIT">
                                            </label>
                                        </div>
                                    </td>
                                    <td align="center">-</td>
                                </tr>
                            @endforeach

                            {{-- Mahasiswa lintas kelas yang sudah tersimpan --}}
                            @if(isset($cross_absen) && count($cross_absen) > 0)
                                @foreach ($cross_absen as $item)
                                    <tr class="warning baris-lintas-kelas" data-sr-id="{{ $item->id_studentrecord }}">
                                        <td>
                                            <center>{{ $no++ }}</center>
                                        </td>
                                        <td>
                                            <center>{{ $item->nim }}</center>
                                        </td>
                                        <td>
                                            {{ $item->nama }}
                                            <span class="label label-warning pull-right"><i class="fa fa-exchange"></i> Lintas Kelas</span>
                                            @php
                                                $curTarget = 0;
                                                if (!empty($item->keterangan) && preg_match('/Target P-(\d+)/', $item->keterangan, $m)) {
                                                    $curTarget = (int) $m[1];
                                                }
                                            @endphp
                                            <div style="margin-top: 5px;">
                                                <label style="font-size: 11px; font-weight: normal; margin-bottom: 2px; color: #555;">
                                                    <i class="fa fa-bullseye text-primary"></i> <strong>Target di Kelas Asal:</strong>
                                                </label>
                                                <select name="target_pertemuan[{{ $item->id_studentrecord }}]" class="form-control input-sm" style="height: 28px; padding: 2px 8px; font-size: 12px; width: 140px; display: inline-block;">
                                                    @for($p = 1; $p <= 16; $p++)
                                                        <option value="{{ $p }}" {{ $curTarget == $p ? 'selected' : '' }}>Pertemuan {{ $p }}</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            @if(!empty($item->keterangan))
                                                <small class="text-muted"><i class="fa fa-tag"></i> {{ $item->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->prodi }}</td>
                                        <td>
                                            <center>{{ $item->kelas }}</center>
                                        </td>
                                        <td>
                                            <center>-</center>
                                        </td>
                                        <td align="center">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},ABSEN" {{ ($item->absensi == 'ABSEN' || empty($item->absensi)) ? 'checked' : '' }}>
                                                </label>
                                            </div>
                                        </td>
                                        <td align="center">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},ALFA" {{ $item->absensi == 'ALFA' ? 'checked' : '' }}>
                                                </label>
                                            </div>
                                        </td>
                                        <td align="center">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},IZIN" {{ $item->absensi == 'IZIN' ? 'checked' : '' }}>
                                                </label>
                                            </div>
                                        </td>
                                        <td align="center">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="absensi_radio[{{ $item->id_studentrecord }}]"
                                                        value="{{ $item->id_studentrecord }},SAKIT" {{ $item->absensi == 'SAKIT' ? 'checked' : '' }}>
                                                </label>
                                            </div>
                                        </td>
                                        <td align="center">
                                            <button type="button" class="btn btn-danger btn-xs hapus-baris-lintas" title="Hapus"><i class="fa fa-trash"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <br>
                    <button id="simpan" class="btn btn-success btn-block" type="submit">Simpan</button>
                </div>
            </form>
        </div>

        <!-- Modal Tambah Mahasiswa Lintas Kelas -->
        <div class="modal fade" id="modal-lintas-kelas" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-yellow">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-exchange"></i> Tambah Mahasiswa Lintas Kelas (Sit-in / Titip Hadir)</h4>
                    </div>
                    <div class="modal-body">
                        <div class="callout callout-warning">
                            <p><i class="fa fa-info-circle"></i> Cari mahasiswa yang memprogram mata kuliah yang sama dari kelas paralel lain. Saat absensi disimpan, kehadiran otomatis disinkronkan ke rekap kelas asalnya.</p>
                        </div>
                        <div class="row">
                            <div class="col-md-8 col-md-offset-2">
                                <div class="input-group">
                                    <input type="text" id="cari-mhs-input" class="form-control input-lg" placeholder="Ketik NIM atau Nama Mahasiswa...">
                                    <span class="input-group-btn">
                                        <button type="button" id="btn-cari-mhs" class="btn btn-primary btn-lg"><i class="fa fa-search"></i> Cari</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div style="margin-top: 20px;">
                            <div id="loading-cari" style="display: none;" class="text-center">
                                <i class="fa fa-spinner fa-spin fa-2x"></i> Memuat data mahasiswa...
                            </div>
                            <div class="table-responsive" id="tabel-hasil-cari-wrapper" style="display: none; max-height: 350px; overflow-y: auto;">
                                <table class="table table-bordered table-striped" id="tabel-hasil-cari">
                                    <thead>
                                        <tr class="bg-gray">
                                            <th width="15%">NIM</th>
                                            <th width="30%">Nama Mahasiswa</th>
                                            <th width="20%">Program Studi</th>
                                            <th width="15%" class="text-center">Kelas Asal</th>
                                            <th width="20%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- AJAX data -->
                                    </tbody>
                                </table>
                            </div>
                            <div id="pesan-kosong" style="display: none;" class="alert alert-warning text-center">
                                Mahasiswa tidak ditemukan di kelas paralel manapun untuk mata kuliah ini.
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $(document).on('click', '.hapus-baris-lintas', function () {
                $(this).closest('tr').remove();
                updateNomorUrut();
            });

            function updateNomorUrut() {
                $('#example5 tbody tr').each(function (index) {
                    $(this).find('td:first center').text(index + 1);
                });
            }

            $('#btn-cari-mhs').click(function () {
                cariMahasiswa();
            });

            $('#cari-mhs-input').keypress(function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    cariMahasiswa();
                }
            });

            function cariMahasiswa() {
                var q = $('#cari-mhs-input').val().trim();
                $('#loading-cari').show();
                $('#tabel-hasil-cari-wrapper').hide();
                $('#pesan-kosong').hide();

                $.ajax({
                    url: "{{ url('cari_mhs_lintas_kelas_dsn/' . $id) }}",
                    type: "GET",
                    data: { q: q },
                    dataType: "json",
                    success: function (data) {
                        $('#loading-cari').hide();
                        if (!data || data.length === 0) {
                            $('#pesan-kosong').show();
                            return;
                        }

                        var tbody = $('#tabel-hasil-cari tbody');
                        tbody.empty();

                        $.each(data, function (index, mhs) {
                            var isAlreadyInTable = $("input[name='absensi_radio[" + mhs.id_studentrecord + "]']").length > 0;
                            var btnAksi = isAlreadyInTable 
                                ? '<span class="label label-default"><i class="fa fa-check"></i> Sudah Ada</span>'
                                : '<button type="button" class="btn btn-success btn-xs btn-pilih-mhs" data-mhs=\'' + JSON.stringify(mhs) + '\'><i class="fa fa-plus"></i> Masukkan</button>';

                            var row = '<tr>' +
                                '<td><strong>' + mhs.nim + '</strong></td>' +
                                '<td>' + mhs.nama + '</td>' +
                                '<td>' + mhs.prodi + '</td>' +
                                '<td class="text-center"><span class="label label-primary">' + mhs.kelas + '</span></td>' +
                                '<td class="text-center">' + btnAksi + '</td>' +
                                '</tr>';
                            tbody.append(row);
                        });

                        $('#tabel-hasil-cari-wrapper').show();
                    },
                    error: function () {
                        $('#loading-cari').hide();
                        alert('Gagal memuat data mahasiswa.');
                    }
                });
            }

            $(document).on('click', '.btn-pilih-mhs', function () {
                var mhs = $(this).data('mhs');
                var isAlreadyInTable = $("input[name='absensi_radio[" + mhs.id_studentrecord + "]']").length > 0;
                if (isAlreadyInTable) {
                    alert('Mahasiswa ini sudah ada dalam daftar absensi!');
                    return;
                }

                var selectTargetHtml = '<div style="margin-top: 5px;">' +
                    '<label style="font-size: 11px; font-weight: normal; margin-bottom: 2px; color: #555;">' +
                    '<i class="fa fa-bullseye text-primary"></i> <strong>Target di Kelas Asal:</strong>' +
                    '</label> ' +
                    '<select name="target_pertemuan[' + mhs.id_studentrecord + ']" class="form-control input-sm" style="height: 28px; padding: 2px 8px; font-size: 12px; width: 140px; display: inline-block;">';
                for (var p = 1; p <= 16; p++) {
                    var isSelected = (p === parseInt("{{ $bap->pertemuan ?? 1 }}")) ? 'selected' : '';
                    selectTargetHtml += '<option value="' + p + '" ' + isSelected + '>Pertemuan ' + p + '</option>';
                }
                selectTargetHtml += '</select></div>';

                var noUrut = $('#example5 tbody tr').length + 1;
                var newRow = '<tr class="warning baris-lintas-kelas" data-sr-id="' + mhs.id_studentrecord + '">' +
                    '<td><center>' + noUrut + '</center></td>' +
                    '<td><center>' + mhs.nim + '</center></td>' +
                    '<td>' + mhs.nama + ' <span class="label label-warning pull-right"><i class="fa fa-exchange"></i> Lintas Kelas</span>' +
                    selectTargetHtml +
                    '</td>' +
                    '<td>' + mhs.prodi + '</td>' +
                    '<td><center>' + mhs.kelas + '</center></td>' +
                    '<td><center>-</center></td>' +
                    '<td align="center"><div class="radio"><label><input type="radio" name="absensi_radio[' + mhs.id_studentrecord + ']" value="' + mhs.id_studentrecord + ',ABSEN" checked></label></div></td>' +
                    '<td align="center"><div class="radio"><label><input type="radio" name="absensi_radio[' + mhs.id_studentrecord + ']" value="' + mhs.id_studentrecord + ',ALFA"></label></div></td>' +
                    '<td align="center"><div class="radio"><label><input type="radio" name="absensi_radio[' + mhs.id_studentrecord + ']" value="' + mhs.id_studentrecord + ',IZIN"></label></div></td>' +
                    '<td align="center"><div class="radio"><label><input type="radio" name="absensi_radio[' + mhs.id_studentrecord + ']" value="' + mhs.id_studentrecord + ',SAKIT"></label></div></td>' +
                    '<td align="center"><button type="button" class="btn btn-danger btn-xs hapus-baris-lintas" title="Hapus"><i class="fa fa-trash"></i></button></td>' +
                    '</tr>';

                $('#example5 tbody').append(newRow);
                $(this).replaceWith('<span class="label label-default"><i class="fa fa-check"></i> Sudah Ada</span>');
                updateNomorUrut();
                $('#modal-lintas-kelas').modal('hide');
            });
        });
    </script>
