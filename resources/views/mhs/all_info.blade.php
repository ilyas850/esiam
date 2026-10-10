@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content')
    <style>
        .info-card-box {
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            margin-bottom: 0;
            border: 1px solid #d2d6de;
            background: #fff;
        }

        .info-card-box:hover {
            border-color: #3c8dbc;
            box-shadow: 0 4px 14px rgba(60, 141, 188, 0.16);
            transform: translateY(-2px);
        }

        .info-item-col {
            margin-bottom: 20px;
            display: flex;
        }

        @media (max-width: 767px) {
            .info-item-col {
                margin-bottom: 15px;
            }

            .info-card-box:hover {
                transform: none;
            }
        }
    </style>

    <section class="content">
        {{-- HEADER & PENCARIAN INFORMASI --}}
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <i class="fa fa-bullhorn text-primary"></i>
                        <h3 class="box-title">Pusat Informasi &amp; Pengumuman Kampus</h3>
                        <div class="box-tools pull-right">
                            <a href="/home" class="btn btn-default btn-sm">
                                <i class="fa fa-arrow-left"></i> Kembali ke Dashboard
                            </a>
                        </div>
                    </div>
                    <div class="box-body" style="background: #fafafa; padding: 15px 20px;">
                        <div class="row">
                            <div class="col-md-7 col-sm-6 col-xs-12">
                                <p class="text-muted" style="margin: 4px 0 10px; font-size: 13px;">
                                    Menampilkan seluruh informasi akademik, surat edaran, jadwal kegiatan, dan pengumuman resmi kampus.
                                </p>
                            </div>
                            <div class="col-md-5 col-sm-6 col-xs-12">
                                <div class="input-group">
                                    <input type="text" id="searchInfoInput" class="form-control input-sm" placeholder="Cari judul atau isi pengumuman...">
                                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- DAFTAR KARTU INFORMASI --}}
        <div class="row" id="infoCardContainer" style="display: flex; flex-wrap: wrap;">
            @forelse ($info as $item)
                @php
                    $isNew = \Carbon\Carbon::parse($item->created_at)->diffInDays(\Carbon\Carbon::now()) <= 7;
                @endphp
                <div class="col-md-4 col-sm-6 col-xs-12 info-item-col" data-search="{{ strtolower($item->judul . ' ' . strip_tags($item->deskripsi)) }}">
                    <div class="box box-solid box-default info-card-box">
                        <div class="box-header with-border" style="background: #fdfdfd; padding: 10px 15px;">
                            <span class="text-muted" style="font-size: 12px;">
                                <i class="fa fa-calendar-o margin-r-5"></i> {{ date('d M Y', strtotime($item->created_at)) }} &middot; {{ $item->created_at->diffForHumans() }}
                            </span>
                            <div class="box-tools pull-right">
                                @if ($isNew)
                                    <span class="label label-danger"><i class="fa fa-bolt"></i> Baru</span>
                                @endif
                            </div>
                        </div>

                        <div class="box-body" style="padding: 15px; flex: 1 1 auto;">
                            <h4 style="margin-top: 0; margin-bottom: 10px; font-weight: 700; line-height: 1.4; font-size: 16px;">
                                <a href="/lihat/{{ $item->id_informasi }}" style="color: #2c3b41;">
                                    {{ $item->judul }}
                                </a>
                            </h4>
                            <p class="text-muted" style="font-size: 13px; line-height: 1.6; margin-bottom: 0;">
                                {{ Str::limit(strip_tags($item->deskripsi), 140) }}
                            </p>
                        </div>

                        <div class="box-footer clearfix" style="background: #fafafa; border-top: 1px solid #f4f4f4; padding: 10px 15px; margin-top: auto;">
                            <div class="pull-left" style="margin-top: 3px;">
                                @if ($item->file != null)
                                    <a href="{{ asset('/data_file/' . $item->file) }}" target="_blank" class="btn btn-default btn-xs" title="Unduh File Lampiran">
                                        <i class="fa fa-paperclip text-primary"></i> <span class="hidden-xs">Lampiran</span>
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 11px;">
                                        <i class="fa fa-minus-circle"></i> Tanpa lampiran
                                    </span>
                                @endif
                            </div>
                            <div class="pull-right">
                                <a href="/lihat/{{ $item->id_informasi }}" class="btn btn-primary btn-xs" style="padding: 4px 10px; font-weight: 600;">
                                    Baca Selengkapnya <i class="fa fa-arrow-circle-right margin-l-5"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-md-12">
                    <div class="callout callout-info text-center" style="padding: 40px 15px; margin-top: 10px;">
                        <i class="fa fa-inbox fa-3x text-muted" style="margin-bottom: 10px; display: block;"></i>
                        <h4 style="font-weight: 700;">Belum Ada Informasi</h4>
                        <p class="text-muted">Informasi dan pengumuman terbaru dari kampus akan ditampilkan di sini.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- STATE KETIKA PENCARIAN TIDAK DITEMUKAN --}}
        <div id="noMatchInfo" class="row" style="display: none;">
            <div class="col-md-12">
                <div class="callout callout-warning text-center" style="padding: 30px 15px;">
                    <i class="fa fa-search fa-2x text-yellow" style="margin-bottom: 10px; display: block;"></i>
                    <h4 style="font-weight: 700;">Pengumuman Tidak Ditemukan</h4>
                    <p>Tidak ada informasi yang sesuai dengan kata kunci yang Anda masukkan. Silakan coba kata kunci lain.</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        (function() {
            var searchInput = document.getElementById('searchInfoInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    var query = this.value.toLowerCase().trim();
                    var items = document.querySelectorAll('.info-item-col');
                    var totalMatch = 0;

                    items.forEach(function(item) {
                        var searchData = item.getAttribute('data-search') || '';
                        if (!query || searchData.indexOf(query) !== -1) {
                            item.style.display = 'flex';
                            totalMatch++;
                        } else {
                            item.style.display = 'none';
                        }
                    });

                    var noMatchBox = document.getElementById('noMatchInfo');
                    if (noMatchBox) {
                        noMatchBox.style.display = (query && totalMatch === 0) ? 'block' : 'none';
                    }
                });
            }
        })();
    </script>
@endsection