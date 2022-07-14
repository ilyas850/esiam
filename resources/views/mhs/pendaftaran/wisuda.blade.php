@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                Validasi Error<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($data == null)
            <form action="{{ url('save_wisuda') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <input type="hidden" name="id_student" value="{{ $id }}">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">Form Data Diri Wisuda</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>NIM</label>
                                    <input type="number" class="form-control" value="nim" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama_lengkap" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Prodi</label>
                                    <select name="prodi" class="form-control" required>
                                        <option></option>
                                        @foreach ($prodi as $item)
                                            <option value="{{ $item->id_prodi }}">{{ $item->prodi }} -
                                                {{ $item->konsentrasi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>No. HP</label>
                                    <input type="number" class="form-control" name="no_hp" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>NIK</label>
                                    <input name="nik" class="form-control" type="number" required>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>NPWP</label>
                                    <input name="npwp" class="form-control" type="number" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alamat KTP</label>
                                    <textarea type="text" class="form-control" name="alamat_ktp" rows="3" required> </textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alamat Domisili</label>
                                    <textarea type="text" class="form-control" name="alamat_domisili" rows="3" required> </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nama Ayah</label>
                                    <input type="text" class="form-control" name="nama_ayah" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Nama Ibu</label>
                                    <input type="text" class="form-control" name="nama_ibu" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>No. HP Ayah</label>
                                    <input name="no_hp_ayah" class="form-control" type="number" required>

                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>No. HP Ibu</label>
                                    <input name="no_hp_ibu" class="form-control" type="number">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alamat Orang tua</label>
                                    <textarea type="text" class="form-control" name="alamat_ortu" rows="3" required> </textarea>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Ukuran Toga</label>
                                    <select name="ukuran_toga" class="form-control" required>
                                        <option></option>
                                        <option value="S">S</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                        <option value="XXXL">XXXL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status Vaksin</label>
                                    <select name="status_vaksin" class="form-control" required>
                                        <option></option>
                                        <option value="Pertama">Pertama</option>
                                        <option value="Kedua">Kedua</option>
                                        <option value="Booster">Booster</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tahun Lulus</label>
                                    <input type="number" class="form-control" name="tahun_lulus" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>File Vaksin</label>
                                    <input type="file" class="form-control" name="file_vaksin" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <button class="btn btn-info" type="submit">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @else
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title">Data Diri (Wisuda)</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>NIM</label>
                                <input type="number" class="form-control" value="{{ $data->nim }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" value="{{ $data->nama_lengkap }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Prodi</label>
                                <select name="prodi" class="form-control" readonly>
                                    <option>{{ $data->prodi }}</option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. HP</label>
                                <input type="number" class="form-control" value="{{ $data->no_hp }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" value="{{ $data->email }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>NIK</label>
                                <input value="{{ $data->nik }}" class="form-control" type="number" readonly>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>NPWP</label>
                                <input value="{{ $data->npwp }}" class="form-control" type="number" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat KTP</label>
                                <textarea type="text" class="form-control" name="alamat_ktp" rows="3" readonly> </textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat Domisili</label>
                                <textarea type="text" class="form-control" name="alamat_domisili" rows="3" readonly> </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Ayah</label>
                                <input type="text" class="form-control" name="nama_ayah" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Nama Ibu</label>
                                <input type="text" class="form-control" name="nama_ibu" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. HP Ayah</label>
                                <input name="no_hp_ayah" class="form-control" type="number" readonly>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>No. HP Ibu</label>
                                <input name="{{$data->no_hp_ibu}}" class="form-control" type="number" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat Orang tua</label>
                                <textarea type="text" class="form-control" rows="3" readonly>{{ $data->alamat_ortu }} </textarea>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Ukuran Toga</label>
                                <select name="ukuran_toga" class="form-control" readonly>
                                    <option value="{{ $data->ukuran_toga }}">{{ $data->ukuran_toga }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Status Vaksin</label>
                                <select name="status_vaksin" class="form-control" readonly>
                                    <option value="{{ $data->status_vaksin }}">{{ $data->status_vaksin }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-red"><i class="fa fa-files-o"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">File Vaksin</span>
                                    <span class="info-box-number">
                                        @if ($data->file_vaksin == null)
                                            Belum ada
                                        @elseif ($data->file_vaksin != null)
                                            <a href="/File Vaksin/{{ $data->id_student }}/{{ $data->file_vaksin }}"
                                                target="_blank"> File Vaksin</a>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <button class="btn btn-success" data-toggle="modal"
                                data-target="#modalUpdateWisuda{{ $data->id_wisuda }}" title="klik untuk edit"><i
                                    class="fa fa-edit"></i> Edit</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modalUpdateWisuda{{ $data->id_wisuda }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Wisuda</h5>
                        </div>
                        <div class="modal-body">
                            <form action="/put_wisuda/{{ $data->id_wisuda }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                @method('put')
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="form-group">
                                            <label>Ukuran Toga</label>
                                            <select name="ukuran_toga" class="form-control" required>
                                                <option value="{{ $data->ukuran_toga }}">{{ $data->ukuran_toga }}
                                                </option>
                                                <option value="S">S</option>
                                                <option value="M">M</option>
                                                <option value="L">L</option>
                                                <option value="XL">XL</option>
                                                <option value="XXL">XXL</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status Vaksin</label>
                                            <select name="status_vaksin" class="form-control" required>
                                                <option value="{{ $data->status_vaksin }}">{{ $data->status_vaksin }}
                                                </option>
                                                <option value="Pertama">Pertama</option>
                                                <option value="Kedua">Kedua</option>
                                                <option value="Booster">Booster</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>File Vaksin</label>
                                    <input type="file" class="form-control" name="file_vaksin"
                                        value="{{ $data->file_vaksin }}" required>
                                    {{ $data->file_vaksin }} <br>
                                    <span>File size max. 4mb dan format file .jpg </span>
                                </div>

                                <button type="submit" class="btn btn-primary">Perbarui Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </section>

    <script>
        function validate() {
            var num = document.myForm.num.value;
            if (isNaN(num)) {
                document.getElementById("numloc").innerHTML = "Harap masukan angka";
                return false;
            } else {
                return true;
            }
        }
    </script>
@endsection
