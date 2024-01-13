@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">Data SKPI, Ijazah dan Transkrip Mahasiswa Politeknik META Industri</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <form class="form" role="form" action="{{ url('filter_skpi') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="col-xs-3">
                            <select class="form-control" name="kodeprodi" required>
                                <option></option>
                                @foreach ($prodiall as $key)
                                    <option value="{{ $key->kodeprodi }}">
                                        {{ $key->prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xs-3">
                            <select class="form-control" name="idangkatan" required>
                                <option></option>
                                @foreach ($angkatan as $keyan)
                                    <option value="{{ $keyan->idangkatan }}">
                                        {{ $keyan->angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Filter</button>
                    </form>
                </div>
                <br>
                <table id="example1" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Mahasiswa</th>
                            <th>Program Studi</th>
                            <th>Kelas</th>
                            <th>
                                No. SKPI
                            </th>
                            <th>
                                No. Ijazah
                            </th>
                            <th>
                                No. Transkrip
                            </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $item->nim }} - {{ $item->nama_lengkap }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td align="center">{{ $item->kelas }}</td>
                                <td>{{ $item->no_skpi }}</td>
                                <td>{{ $item->no_ijazah }}</td>
                                <td>{{ $item->no_transkrip }}</td>
                                <td>
                                    <a href="/download_skpi/{{ $item->id_skpi }}" class="btn btn-info btn-xs"
                                        title="download SKPI"><i class="fa fa-download"></i></a>
                                    <a href="/unduh_ijazah/{{ $item->id_yudisium }}" class="btn btn-danger btn-xs"
                                        title="download Ijazah"><i class="fa fa-download"></i></a>
                                    <a href="/downloadAbleFile/{{ $item->idstudent }}" class="btn btn-warning btn-xs"
                                        title="download Transkrip"><i class="fa fa-download"></i></a>
                                    <button class="btn btn-success btn-xs" data-toggle="modal"
                                        data-target="#modalUpdateWisuda{{ $item->idstudent }}" title="klik untuk edit"><i
                                            class="fa fa-edit"></i></button>
                                </td>

                            </tr>
                            <div class="modal fade" id="modalUpdateWisuda{{ $item->idstudent }}" tabindex="-1"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Data Mahasiswa</h5>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('saveedit_data_mhs') }}" method="post"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <input type="hidden" name="id_student" value="{{ $item->idstudent }}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>No. Ijazah</label>
                                                            <input type="number" class="form-control"
                                                                value="{{ $item->no_ijazah }}" name="no_ijazah" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>No. Transkrip</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $item->no_transkrip }}" name="no_transkrip"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label>Tempat Lahir</label>
                                                            <input type="text" class="form-control" name="tmpt_lahir"
                                                                value="{{ $item->tmpt_lahir }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <div class="form-group">
                                                            <label>Tanggal Lahir</label>
                                                            <input type="date" class="form-control" name="tgl_lahir"
                                                                value="{{ date('Y-m-d', strtotime($item->tgl_lahir)) }}"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>No. HP</label>
                                                            <input type="number" class="form-control"
                                                                value="{{ $item->no_hp }}" name="no_hp">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Email</label>
                                                            <input type="email" class="form-control"
                                                                value="{{ $item->email }}" name="email">
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
                                                                type="number" name="npwp">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Alamat KTP</label>
                                                            <textarea type="text" class="form-control" rows="4" name="alamat_ktp"> {{ $item->alamat_ktp }} </textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Alamat Domisili</label>
                                                            <textarea type="text" class="form-control" rows="4" name="alamat_domisili"> {{ $item->alamat_domisili }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nama Ayah</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $item->nama_ayah }}" name="nama_ayah">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Nama Ibu</label>
                                                            <input type="text" class="form-control"
                                                                value="{{ $item->nama_ibu }}" name="nama_ibu">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>No. HP Ayah</label>
                                                            <input value="{{ $item->no_hp_ayah }}" class="form-control"
                                                                type="number" name="no_hp_ayah">
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
                                                            <textarea type="text" class="form-control" rows="3" name="alamat_ortu">{{ $item->alamat_ortu }} </textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>Ukuran Toga</label>
                                                            <select name="ukuran_toga" class="form-control">
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
                                                            <label>Tahun Lulus</label>
                                                            <input type="number" value="{{ $item->tahun_lulus }}"
                                                                name="tahun_lulus" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
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
