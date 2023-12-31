@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
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
        <div class="box box-info">
            <form class="form-horizontal" method="POST" action="{{ url('save_bap_kprd') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id_kurperiode" value="{{ $id }}">
                <div class="box-body">
                    {{-- <div class="modal fade" id="modalPilihRps" tabindex="-1" aria-labelledby="modalPilihRps"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <div class="table">
                                        <table class="table border-collapse">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Pertemuan</th>
                                                    <th>Kemampuan Akhir yang Direncanakan</th>
                                                    <th>Materi Pembelajaran</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($rps as $item)
                                                    <tr>
                                                        <td>
                                                            <input type="radio" name="id_rps" value="{{ $item->id_rps }}"
                                                                required>
                                                        </td>
                                                        <td>Pertemuan Ke - {{ $item->pertemuan }}</td>
                                                        <td>{{ $item->kemampuan_akhir_direncanakan }}</td>
                                                        <td>{{ $item->materi_pembelajaran }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    <div class="col-md-12">
                        {{-- <div class="form-group">
                            <input type="hidden" id="selectedRpsId" name="id_rps">
                            <div class="col-md-2">
                                <label>
                                    <font color="red-text">*</font>Pilih RPS
                                </label><br>
                                <button class="btn btn-success" data-toggle="modal" data-target="#modalPilihRps"><i
                                        class="fa fa-check" title="Klik untuk upload soal uas"></i>
                                    Pilih RPS</button>
                            </div>
                            <div class="col-md-5">
                                <label>
                                    <font color="red-text">*</font>Kemampuan Akhir Direncanakan
                                </label>
                                <textarea id="kemampuanAkhir" class="form-control pull-right" name="kemampuan_akhir_direncanakan" rows="3"
                                    readonly></textarea>
                            </div>
                            <div class="col-md-5">
                                <label>
                                    <font color="red-text">*</font>Materi Pembelajaran
                                </label>
                                <textarea id="materiPembelajaran" class="form-control pull-right" name="materi_pembelajaran" rows="3" readonly></textarea>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Pertemuan
                                </label>
                                <select class="form-control" name="pertemuan" required>
                                    <option></option>
                                    @foreach ($nilai_pertemuan as $item)
                                        <option value="{{ $item->id_pertemuan }}">Pertemuan Ke-{{ $item->id_pertemuan }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('pertemuan'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('pertemuan') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Tanggal
                                </label>
                                <input type="date" class="form-control pull-right" name="tanggal"
                                    placeholder="Masukan Tanggal Lahir" required>
                                @if ($errors->has('tanggal'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('tanggal') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Jam Mulai
                                </label>
                                <select class="form-control" name="jam_mulai" required>
                                    <option></option>
                                    @foreach ($jam as $key)
                                        <option value="{{ $key->jam }}">{{ $key->jam }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Jam Selesai
                                </label>
                                <select class="form-control" name="jam_selsai" required>
                                    <option></option>
                                    @foreach ($jam as $key)
                                        <option value="{{ $key->jam }}">{{ $key->jam }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Jenis Kuliah/Ujian
                                </label>
                                <select class="form-control" name="jenis_kuliah" required>
                                    <option></option>
                                    <option value="Kuliah">Kuliah</option>
                                    <option value="UTS">UTS</option>
                                    <option value="UAS">UAS</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Tipe Kuliah/Ujian
                                </label>
                                <select class="form-control" name="id_tipekuliah" required>
                                    <option></option>
                                    <option value="1">Teori</option>
                                    <option value="2">Praktikum</option>
                                    <option value="3">Teori + Praktikum</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Metode Kuliah/Ujian
                                </label>
                                <select class="form-control" name="metode_kuliah" required>
                                    <option></option>
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Link Materi Kuliah
                                </label>
                                <input type="text" class="form-control" name="link_materi" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Aktual Materi Pembelajaran
                                </label>
                                <textarea class="form-control" rows="5" name="materi_kuliah" required></textarea>
                            </div>
                            {{-- <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Alasan Pembaharuan Materi
                                </label>
                                <textarea class="form-control" rows="5" name="alasan_pembaharuan_materi" required></textarea>
                            </div> --}}
                            <div class="col-md-3">
                                <label>
                                    Aktual Materi Praktikum
                                </label>
                                <textarea class="form-control" rows="5" name="praktikum"></textarea>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Media Pembelajaran
                                </label>
                                <textarea class="form-control" rows="5" name="media_pembelajaran" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>Upload File Kuliah Tatap Muka</label>
                                <input type="file" name="file_kuliah_tatapmuka">
                                @if ($errors->has('file_kuliah_tatapmuka'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('file_kuliah_tatapmuka') }}</strong>
                                    </span>
                                @endif
                                <p class="help-block">Max. size 2 mb dengan format .jpg .jpeg </p>
                            </div>
                            <div class="col-md-3">
                                <label>Upload File Materi Tugas</label>
                                <input type="file" name="file_materi_tugas">
                                @if ($errors->has('file_materi_tugas'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('file_materi_tugas') }}</strong>
                                    </span>
                                @endif
                                <p class="help-block">Max. size 2 mb dengan format .jpg .jpeg </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </section>

    <script>
        $(document).ready(function() {
            // Menangkap perubahan pada elemen radio button di modal
            $('input[name="id_rps"]').change(function() {
                // Memperbarui nilai textarea di form dengan data yang dipilih
                var selectedRps = $('input[name="id_rps"]:checked');
                $('#kemampuanAkhir').val(selectedRps.closest('tr').find('td:nth-child(3)').text());
                $('#materiPembelajaran').val(selectedRps.closest('tr').find('td:nth-child(4)').text());
                $('#selectedRpsId').val(selectedRps.val());
            });
        });
    </script>



@endsection
