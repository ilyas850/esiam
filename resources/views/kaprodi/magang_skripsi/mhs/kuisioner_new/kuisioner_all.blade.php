@extends('layouts.master')

@section('side')
    {{-- @include('layouts.side') --}}
@endsection
@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Kuisioner Mahasiswa</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table no-margin">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>KUISIONER KEPUASAN MAHASISWA TERHADAP EVALUASI DOSEN MENGAJAR (EDOM)</td>
                                <td>
                                    <a href=" {{ url('isi_edom_new') }} " class="btn btn-info btn-xs">Isi Kuisioner</a>
                                </td>
                            </tr>
                            <?php $no = 2; ?>
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $item->kategori_kuisioner }}</td>
                                    <td>
                                        @if ($item->id_kategori_kuisioner == 1)
                                            <a href="/isi_dosen_pa_new/{{ $item->id_kategori_kuisioner }}"
                                                class="btn btn-info btn-xs">Isi Kuisioner</a>
                                        @elseif($item->id_kategori_kuisioner == 2)
                                            <a href="/isi_dosen_pkl_new/{{ $item->id_kategori_kuisioner }}"
                                                class="btn btn-info btn-xs">Isi Kuisioner</a>
                                        @elseif($item->id_kategori_kuisioner == 3)
                                            <a href="/isi_dosen_ta_new/{{ $item->id_kategori_kuisioner }}"
                                                class="btn btn-info btn-xs">Isi Kuisioner</a>
                                        @elseif($item->id_kategori_kuisioner == 4)
                                            <a href="/isi_dosen_ta_peng1_new/{{ $item->id_kategori_kuisioner }}"
                                                class="btn btn-info btn-xs">Isi Kuisioner</a>
                                        @elseif($item->id_kategori_kuisioner == 5)
                                            <a href="/isi_dosen_ta_peng2_new/{{ $item->id_kategori_kuisioner }}"
                                                class="btn btn-info btn-xs">Isi Kuisioner</a>
                                        @elseif($item->id_kategori_kuisioner == 6)
                                            <a href="/isi_kuis_baak_new/{{ $item->id_kategori_kuisioner }}"
                                                class="btn btn-info btn-xs">Isi Kuisioner</a>
                                        @elseif($item->id_kategori_kuisioner == 7)
                                            <a href="/isi_kuis_bauk_new/{{ $item->id_kategori_kuisioner }}"
                                                class="btn btn-info btn-xs">Isi Kuisioner</a>
                                        @elseif($item->id_kategori_kuisioner == 8)
                                            <a href="/isi_kuis_perpus_new/{{ $item->id_kategori_kuisioner }}"
                                                class="btn btn-info btn-xs">Isi Kuisioner</a>
                                        @elseif($item->id_kategori_kuisioner == 9)
                                            <a href="/isi_kuis_beasiswa_new/{{ $item->id_kategori_kuisioner }}"
                                                class="btn btn-info btn-xs">Isi Kuisioner</a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
