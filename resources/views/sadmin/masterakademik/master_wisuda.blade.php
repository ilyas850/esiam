@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data Wisuda Mahasiswa Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <table id="example8" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Lulus</th>
                            <th>Ukuran Toga</th>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>No. HP</th>
                            <th>E-mail</th>
                            <th>NIK</th>
                            <th>NPWP</th>
                            <th>Alamat KTP</th>
                            <th>Alamat Domisili</th>
                            <th>Nama Ayah</th>
                            <th>Nama Ibu</th>
                            <th>No. HP Ayah</th>
                            <th>No. HP Ibu</th>
                            <th>Alamat Ortu</th>
                            <th>Status Vaksin</th>
                            <th>File Vaksin</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->tahun_lulus }}</td>
                                <td>{{ $item->ukuran_toga }}</td>
                                <td>{{ $item->nim }}</td>
                                <td>{{ $item->nama_lengkap }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td>{{ $item->no_hp }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->nik }}</td>
                                <td>{{ $item->npwp }}</td>
                                <td>{{ $item->alamat_ktp }}</td>
                                <td>{{ $item->alamat_domisili }}</td>
                                <td>{{ $item->nama_ayah }}</td>
                                <td>{{ $item->nama_ibu }}</td>
                                <td>{{ $item->no_hp_ayah }}</td>
                                <td>{{ $item->no_hp_ibu }}</td>
                                <td>{{ $item->alamat_ortu }}</td>
                                <td>{{ $item->status_vaksin }}</td>
                                <td><a href="/File Vaksin/{{ $item->id_student }}/{{ $item->file_vaksin }}" target="_blank">
                                        File Vaksin</a></td>
                                <td>
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateWisuda{{ $item->id_wisuda }}" title="klik untuk edit"><i
                                            class="fa fa-edit"></i></button>
                                    @if ($item->validasi == 'BELUM')
                                        <a href="/validate_wisuda/{{ $item->id_wisuda }}" class="btn btn-info btn-xs"
                                            title="klik untuk validasi"><i class="fa fa-check"></i></a>
                                    @elseif($item->validasi == 'SUDAH')
                                        <a href="/unvalidate_wisuda/{{ $item->id_wisuda }}" class="btn btn-danger btn-xs"
                                            title="klik untuk batal validasi"><i class="fa fa-close"></i></a>
                                    @endif
                                </td>
                            </tr>

                            <div class="modal fade" id="modalUpdateWisuda{{ $item->id_wisuda }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Wisuda</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="/saveedit_wisuda/{{ $item->id_wisuda }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('put')
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>NIM</label>
                                                            <input type="number" class="form-control"
                                                                value="{{ $item->nim }}" name="nim" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nama Lengkap</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $item->nama_lengkap }}" name="nama_lengkap"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Prodi</label>
                                                            <select name="id_prodi" class="form-control" required>
                                                                <option value="{{ $item->id_prodi }}">{{ $item->prodi }}
                                                                    -
                                                                    {{ $item->konsentrasi }}</option>
                                                                @foreach ($prodi as $prd)
                                                                    <option value="{{ $prd->id_prodi }}">
                                                                        {{ $prd->prodi }} -
                                                                        {{ $prd->konsentrasi }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>No. HP</label>
                                                            <input type="number" class="form-control"
                                                                value="{{ $item->no_hp }}" name="no_hp" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" class="form-control"
                                                                value="{{ $item->email }}" name="email" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>NIK</label>
                                                            <input value="{{ $item->nik }}" class="form-control"
                                                                type="number" name="nik" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>NPWP</label>
                                                            <input value="{{ $item->npwp }}" class="form-control"
                                                                type="number" name="npwp" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Alamat KTP</label>
                                                            <textarea type="text" class="form-control" rows="4" name="alamat_ktp" required> {{ $item->alamat_ktp }} </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Alamat Domisili</label>
                                                            <textarea type="text" class="form-control" rows="4" name="alamat_domisili" required> {{ $item->alamat_domisili }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nama Ayah</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $item->nama_ayah }}" name="nama_ayah" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nama Ibu</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $item->nama_ibu }}" name="nama_ibu" required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>No. HP Ayah</label>
                                                            <input value="{{ $item->no_hp_ayah }}" class="form-control"
                                                                type="number" name="no_hp_ayah" required>

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>No. HP Ibu</label>
                                                            <input value="{{ $item->no_hp_ibu }}" class="form-control"
                                                                name="no_hp_ibu" type="number">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Alamat Orang tua</label>
                                                            <textarea type="text" class="form-control" rows="3" name="alamat_ortu" required>{{ $item->alamat_ortu }} </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Ukuran Toga</label>
                                                            <select name="ukuran_toga" class="form-control" required>
                                                                <option value="{{ $item->ukuran_toga }}">
                                                                    {{ $item->ukuran_toga }}
                                                                </option>
                                                                <option value="S">S</option>
                                                                <option value="M">M</option>
                                                                <option value="L">L</option>
                                                                <option value="XL">XL</option>
                                                                <option value="XXL">XXL</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Status Vaksin</label>
                                                            <select name="status_vaksin" class="form-control" required>
                                                                <option value="{{ $item->status_vaksin }}">
                                                                    {{ $item->status_vaksin }}
                                                                </option>
                                                                <option value="Pertama">Pertama</option>
                                                                <option value="Kedua">Kedua</option>
                                                                <option value="Booster">Booster</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Tahun Lulus</label>
                                                            <input type="number" value="{{ $item->tahun_lulus }}"
                                                                name="tahun_lulus" class="form-control" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>File Vaksin</label>
                                                    <input type="file" class="form-control" name="file_vaksin"
                                                        value="{{ $item->file_vaksin }}">
                                                    {{ $item->file_vaksin }} <br>
                                                    <span>File size max. 4mb dan format file .jpg </span>
                                                </div>
                                                <input type="hidden" name="id_student" value="{{ $item->id_student }}">
                                                <button type="submit" class="btn btn-primary">Perbarui Data</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
