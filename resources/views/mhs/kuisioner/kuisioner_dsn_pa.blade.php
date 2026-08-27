@extends('layouts.master')

@section('side')
    @include('layouts.side')
@endsection

@section('content_header')
    <section class="content-header">
        <h1>Kuisioner Dosen Pembimbing Akademik</h1>
        <ol class="breadcrumb">
            <li><a href="{{ url('home') }}"><i class="fa fa-dashboard"></i> Halaman Utama</a></li>
            <li><a href="{{ url('kuisioner') }}"><i class="fa fa-list-alt"></i> Kuisioner</a></li>
            <li class="active">Dosen PA</li>
        </ol>
    </section>
@endsection

@section('content')
    <style>
        .kuisioner-info { margin-bottom: 20px; }
        .kuisioner-info .info-box { min-height: 88px; }
        .kuisioner-info .info-box-icon { width: 70px; font-size: 27px; line-height: 88px; }
        .kuisioner-info .info-box-content { margin-left: 70px; padding-top: 12px; }
        .kuisioner-info .info-box-text, .kuisioner-info .info-box-number { overflow: visible; text-overflow: initial; white-space: normal; }
        .kuisioner-progress-text { margin-top: 7px; color: #777; font-size: 12px; }
        .kuisioner-question-row.is-answered { background: #effcf2 !important; box-shadow: inset 4px 0 0 #00a65a; }
        .kuisioner-question-number { color: #00a65a; font-weight: 700; }
        .kuisioner-rating-option { min-width: 104px; margin: 2px; padding: 7px 8px; font-size: 12px; }
        .kuisioner-rating-option input { display: none; }
        .kuisioner-rating-option.is-selected { background: #00a65a !important; border-color: #008d4c !important; box-shadow: 0 1px 3px rgba(0, 141, 76, .35); color: #fff !important; font-weight: 700; }
        .kuisioner-rating-option.is-selected:before { margin-right: 5px; content: '\2713'; }

        @media (max-width: 767px) {
            .kuisioner-info .info-box { min-height: 82px; }
            .kuisioner-info .info-box-icon { line-height: 82px; }
            .kuisioner-rating-option { display: block; width: 100%; margin: 4px 0; padding: 10px; text-align: left; }
            .kuisioner-table, .kuisioner-table tbody, .kuisioner-table tr, .kuisioner-table td { display: block; width: 100% !important; min-width: 0 !important; max-width: 100%; box-sizing: border-box; }
            .table-responsive { overflow-x: hidden; }
            .kuisioner-table thead { display: none; }
            .kuisioner-table tr.kuisioner-question-row { width: calc(100% - 24px) !important; margin: 12px; border: 1px solid #e5e5e5; border-radius: 4px; box-shadow: 0 1px 2px rgba(0, 0, 0, .05); }
            .kuisioner-table td { padding: 9px 12px !important; border: 0 !important; text-align: left !important; white-space: normal !important; overflow-wrap: break-word; word-break: break-word; }
            .kuisioner-table td:before { display: block; margin-bottom: 4px; color: #777; content: attr(data-label); font-size: 11px; font-weight: 700; text-transform: uppercase; }
            .kuisioner-table td.kuisioner-question-number:before { display: none; }
            #save-kuisioner-button { width: 100%; margin-top: 10px; }
        }
    </style>

    <section class="content">
        <div class="row kuisioner-info">
            <div class="col-sm-6"><div class="info-box"><span class="info-box-icon bg-aqua"><i class="fa fa-user"></i></span><div class="info-box-content"><span class="info-box-text">Dosen Pembimbing Akademik</span><span class="info-box-number">{{ $nama_dsn }}</span></div></div></div>
            <div class="col-sm-3"><div class="info-box"><span class="info-box-icon bg-blue"><i class="fa fa-graduation-cap"></i></span><div class="info-box-content"><span class="info-box-text">Program Studi</span><span class="info-box-number">{{ $prodi }}</span></div></div></div>
            <div class="col-sm-3"><div class="info-box"><span class="info-box-icon bg-green"><i class="fa fa-calendar"></i></span><div class="info-box-content"><span class="info-box-text">Periode</span><span class="info-box-number">{{ $periodetipe }}</span><span class="text-muted">{{ $periodetahun }}</span></div></div></div>
        </div>

        <div class="callout callout-info">
            <h4><i class="fa fa-info-circle"></i> Petunjuk Pengisian</h4>
            <p>Pilih satu nilai untuk setiap pernyataan. Semua pertanyaan wajib diisi sebelum kuisioner dapat disimpan.</p>
            <span class="label label-danger">1 Tidak Baik</span> <span class="label label-warning">2 Kurang Baik</span> <span class="label label-info">3 Baik</span> <span class="label label-success">4 Sangat Baik</span>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Mohon lengkapi seluruh pertanyaan sebelum menyimpan.</div>
        @endif

        <form action="{{ url('save_kuisioner_dsn_pa') }}" method="post" id="kuisioner-form">
            {{ csrf_field() }}
            <input type="hidden" name="id_dosen_pembimbing" value="{{ $id_dsn }}">
            <input type="hidden" name="id_periodetahun" value="{{ $idthn }}">
            <input type="hidden" name="id_periodetipe" value="{{ $idtp }}">

            <div class="box box-info">
                <div class="box-header with-border"><h3 class="box-title">Pernyataan Penilaian Dosen PA</h3><div class="box-tools pull-right"><span class="label label-info">{{ $questionnaireQuestionCount }} pertanyaan</span></div></div>
                <div class="box-body"><div class="progress progress-sm active"><div id="kuisioner-progress-bar" class="progress-bar progress-bar-aqua progress-bar-striped" style="width: 0%"></div></div><p id="kuisioner-progress-text" class="kuisioner-progress-text">0 dari {{ $questionnaireQuestionCount }} pertanyaan terisi</p></div>
                <div class="table-responsive">
                    <table class="table table-hover kuisioner-table">
                        <thead><tr><th width="6%" class="text-center">No</th><th width="23%">Aspek</th><th>Komponen</th><th width="22%">Nilai</th></tr></thead>
                        <tbody>
                            <?php $no = 1; ?>
                            @foreach ($data as $item)
                                <?php $selectedValue = old('nilai.' . $item->id_kuisioner); ?>
                                <tr class="kuisioner-question-row">
                                    <td class="text-center kuisioner-question-number" data-label="Pertanyaan">{{ $no++ }}</td>
                                    <td data-label="Aspek">{{ $item->aspek_kuisioner }}</td>
                                    <td data-label="Komponen">{{ $item->komponen_kuisioner }}</td>
                                    <td data-label="Nilai">
                                        <label class="btn btn-default btn-xs kuisioner-rating-option {{ $selectedValue == $item->id_kuisioner . ',1' ? 'is-selected' : '' }}">
                                            <input class="kuisioner-answer" type="radio" name="nilai[{{ $item->id_kuisioner }}]" value="{{ $item->id_kuisioner }},1" required {{ $selectedValue == $item->id_kuisioner . ',1' ? 'checked' : '' }}> 1 Tidak Baik
                                        </label>
                                        <label class="btn btn-default btn-xs kuisioner-rating-option {{ $selectedValue == $item->id_kuisioner . ',2' ? 'is-selected' : '' }}">
                                            <input class="kuisioner-answer" type="radio" name="nilai[{{ $item->id_kuisioner }}]" value="{{ $item->id_kuisioner }},2" {{ $selectedValue == $item->id_kuisioner . ',2' ? 'checked' : '' }}> 2 Kurang Baik
                                        </label>
                                        <label class="btn btn-default btn-xs kuisioner-rating-option {{ $selectedValue == $item->id_kuisioner . ',3' ? 'is-selected' : '' }}">
                                            <input class="kuisioner-answer" type="radio" name="nilai[{{ $item->id_kuisioner }}]" value="{{ $item->id_kuisioner }},3" {{ $selectedValue == $item->id_kuisioner . ',3' ? 'checked' : '' }}> 3 Baik
                                        </label>
                                        <label class="btn btn-default btn-xs kuisioner-rating-option {{ $selectedValue == $item->id_kuisioner . ',4' ? 'is-selected' : '' }}">
                                            <input class="kuisioner-answer" type="radio" name="nilai[{{ $item->id_kuisioner }}]" value="{{ $item->id_kuisioner }},4" {{ $selectedValue == $item->id_kuisioner . ',4' ? 'checked' : '' }}> 4 Sangat Baik
                                        </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="box-footer"><a href="{{ url('kuisioner') }}" class="btn btn-default"><i class="fa fa-arrow-left"></i> Kembali ke Kuisioner</a><button id="save-kuisioner-button" type="submit" class="btn btn-info pull-right" disabled><i class="fa fa-save"></i> Simpan Kuisioner (<span id="kuisioner-answer-count">0</span>/{{ $questionnaireQuestionCount }})</button></div>
            </div>
        </form>
    </section>

    <script>
        (function () {
            var total = {{ $questionnaireQuestionCount }}, answers = document.querySelectorAll('.kuisioner-answer'), progressBar = document.getElementById('kuisioner-progress-bar'), progressText = document.getElementById('kuisioner-progress-text'), answerCount = document.getElementById('kuisioner-answer-count'), saveButton = document.getElementById('save-kuisioner-button');
            function updateProgress() {
                var answered = 0;
                Array.prototype.forEach.call(document.querySelectorAll('.kuisioner-question-row'), function (row) {
                    var selectedAnswer = row.querySelector('.kuisioner-answer:checked');

                    if (selectedAnswer) { answered++; }
                    row.classList.toggle('is-answered', selectedAnswer !== null);

                    Array.prototype.forEach.call(row.querySelectorAll('.kuisioner-rating-option'), function (option) {
                        option.classList.toggle('is-selected', option.querySelector('.kuisioner-answer:checked') !== null);
                    });
                });
                var percentage = total ? Math.round((answered / total) * 100) : 0;
                progressBar.style.width = percentage + '%'; progressText.textContent = answered + ' dari ' + total + ' pertanyaan terisi'; answerCount.textContent = answered; saveButton.disabled = answered !== total;
            }
            Array.prototype.forEach.call(answers, function (answer) { answer.addEventListener('change', updateProgress); });
            updateProgress();
        })();
    </script>
@endsection
