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
            <form class="form-horizontal" action="/simpanedit_bap_dsn/{{ $id }}" method="POST"
                enctype="multipart/form-data">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="id_kurperiode" value="{{ $bap->id_kurperiode }}">
                {{ csrf_field() }}
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
                            <input type="hidden" id="selectedRpsId" name="id_rps" value="{{ $bap->id_rps }}">
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
                                <textarea id="kemampuanAkhir" class="form-control" name="kemampuan_akhir_direncanakan" rows="3" readonly>{{ $bap->kemampuan_akhir_direncanakan }}</textarea>
                            </div>
                            <div class="col-md-5">
                                <label>
                                    <font color="red-text">*</font>Materi Pembelajaran
                                </label>
                                <textarea id="materiPembelajaran" class="form-control" name="materi_pembelajaran" rows="3" readonly>
                                    {{ $bap->materi_pembelajaran }}
                                </textarea>
                            </div>
                        </div> --}}
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>
                                    <font color ="red-text">*</font>Pertemuan
                                </label>
                                <select class="form-control" name="pertemuan" required>
                                    <option value="{{ $bap->pertemuan }}">Pertemuan Ke-{{ $bap->pertemuan }}</option>
                                    <option value="1">Pertemuan Ke-1</option>
                                    <option value="2">Pertemuan Ke-2</option>
                                    <option value="3">Pertemuan Ke-3</option>
                                    <option value="4">Pertemuan Ke-4</option>
                                    <option value="5">Pertemuan Ke-5</option>
                                    <option value="6">Pertemuan Ke-6</option>
                                    <option value="7">Pertemuan Ke-7</option>
                                    <option value="8">Pertemuan Ke-8</option>
                                    <option value="9">Pertemuan Ke-9</option>
                                    <option value="10">Pertemuan Ke-10</option>
                                    <option value="11">Pertemuan Ke-11</option>
                                    <option value="12">Pertemuan Ke-12</option>
                                    <option value="13">Pertemuan Ke-13</option>
                                    <option value="14">Pertemuan Ke-14</option>
                                    <option value="15">Pertemuan Ke-15</option>
                                    <option value="16">Pertemuan Ke-16</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color = "red-text">*</font>Tanggal
                                </label>
                                <input type="date" class="form-control pull-right" name="tanggal"
                                    value="{{ $bap->tanggal }}" required>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color = "red-text">*</font>Jam Mulai
                                </label>
                                <select class="form-control" name="jam_mulai" required>
                                    <option value="{{ $bap->jam_mulai }}">{{ $bap->jam_mulai }}</option>
                                    @foreach ($jam as $key)
                                        <option value="{{ $key->jam }}">{{ $key->jam }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color = "red-text">*</font>Jam Selesai
                                </label>
                                <select class="form-control" name="jam_selsai" required>
                                    <option value="{{ $bap->jam_selsai }}">{{ $bap->jam_selsai }}</option>
                                    @foreach ($jam as $key)
                                        <option value="{{ $key->jam }}">{{ $key->jam }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>
                                    <font color ="red-text">*</font>Jenis Kuliah/Ujian
                                </label>
                                <select class="form-control" name="jenis_kuliah" required>
                                    <option value="{{ $bap->jenis_kuliah }}">{{ $bap->jenis_kuliah }}</option>
                                    <option value="Kuliah">Kuliah</option>
                                    <option value="UTS">UTS</option>
                                    <option value="UAS">UAS</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color ="red-text">*</font>Tipe Kuliah/Ujian
                                </label>
                                @if ($cek_mk->akt_sks_praktek == 0)
                                    <select class="form-control" name="id_tipekuliah" required>
                                        <option value="1">Teori</option>
                                    </select>
                                @elseif($cek_mk->akt_sks_praktek > 0)
                                    <select class="form-control" name="id_tipekuliah" required>
                                        <option value="{{ $bap->id_tipekuliah }}">
                                            @if ($bap->id_tipekuliah == 1)
                                                Teori
                                            @elseif($bap->id_tipekuliah == 2)
                                                Praktikum
                                            @elseif($bap->id_tipekuliah == 3)
                                                Teori + Praktikum
                                            @endif
                                        </option>
                                        <option value="1">Teori</option>
                                        <option value="2">Praktikum</option>
                                        <option value="3">Teori + Praktikum</option>
                                    </select>
                                @endif
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color ="red-text">*</font>Metode Kuliah/Ujian
                                </label>
                                <select class="form-control" name="metode_kuliah" required>
                                    <option value="{{ $bap->metode_kuliah }}">{{ $bap->metode_kuliah }}</option>
                                    <option value="Online">Online</option>
                                    <option value="Offline">Offline</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Link Materi Kuliah
                                </label>
                                <input type="text" class="form-control" name="link_materi"
                                    value="{{ $bap->link_materi }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Aktual Materi Pembelajaran
                                </label>
                                <textarea class="form-control" rows="5" name="materi_kuliah" required>{{ $bap->materi_kuliah }}</textarea>
                            </div>
                            {{-- <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Alasan Pembaharuan Materi
                                </label>
                                <textarea class="form-control" rows="5" name="alasan_pembaharuan_materi" required>{{ $bap->alasan_pembaharuan_materi }}</textarea>
                            </div> --}}
                            <div class="col-md-3">
                                <label>
                                    Aktual Materi Praktikum
                                </label>
                                <textarea class="form-control" rows="5" name="praktikum">{{ $bap->praktikum }}</textarea>
                            </div>
                            <div class="col-md-3">
                                <label>
                                    <font color="red-text">*</font>Media Pembelajaran/Ujian
                                </label>
                                <textarea class="form-control" rows="5" name="media_pembelajaran" required>{{ $bap->media_pembelajaran }}</textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <label>Upload File Kuliah Tatap Muka</label>
                                <input type="file" name="file_kuliah_tatapmuka">{{ $bap->file_kuliah_tatapmuka }}

                                <p class="help-block">Max. size 2 mb dengan format .jpg .jpeg </p>
                            </div>
                            {{-- <div class="col-md-3">
                                <label>Upload File Materi Kuliah/Ujian</label>
                                <input type="file" name="file_materi_kuliah">{{$bap->file_materi_kuliah}}
                                
                                <p class="help-block">Max. size 4 mb dengan format .png .jpg .jpeg .pdf .doc</p>
                            </div> --}}
                            <div class="col-md-3">
                                <label>Upload File Materi Tugas</label>
                                <input type="file" name="file_materi_tugas">{{ $bap->file_materi_tugas }}

                                <p class="help-block">Max. size 2 mb dengan format .jpg .jpeg </p>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary">
                                    Simpan Perubahan
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
