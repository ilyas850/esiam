@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <section class="content">
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title">SK Mengajar Dosen</h3>
            </div>
            <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th class="text-center">Tahun Akademik</th>
                            <th class="text-center">Program Studi</th>
                            <th width="20%" class="text-center">SK Mengajar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        @foreach ($data as $item)
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $item->periode_tahun }} - {{ $item->periode_tipe }}</td>
                                <td>{{ $item->prodi }}</td>
                                <td class="text-center">
                                    @if ($item->file)
                                        <a href="{{ asset('SK-Mengajar/' . $item->file) }}" target="_blank" class="btn btn-xs btn-primary">
                                            <i class="fa fa-file-pdf-o"></i> Lihat File
                                        </a>
                                    @else
                                        <span class="label label-default">Tidak ada file</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
