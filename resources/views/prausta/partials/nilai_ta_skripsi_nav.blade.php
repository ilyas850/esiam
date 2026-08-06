@php
    $active = isset($active) ? $active : '';
    $items = [
        [
            'key' => 'ta',
            'label' => 'Data Nilai TA',
            'desc' => 'Kelola nilai pembimbing, penguji, dan validasi Tugas Akhir.',
            'url' => url('/data_nilai_ta_mahasiswa'),
            'icon' => 'fa-graduation-cap',
            'class' => 'type-ta',
        ],
        [
            'key' => 'skripsi',
            'label' => 'Data Nilai Skripsi',
            'desc' => 'Kelola nilai pembimbing, penguji, dan validasi Skripsi.',
            'url' => url('/data_nilai_skripsi_mahasiswa'),
            'icon' => 'fa-book',
            'class' => 'type-skripsi',
        ],
    ];
@endphp

<div class="box box-solid nilai-selector">
    <div class="box-header with-border">
        <h3 class="box-title">
            <i class="fa fa-list-alt"></i> Pilih Tipe Penilaian
        </h3>
    </div>
    <div class="box-body">
        <div class="row nilai-type-grid">
            @foreach ($items as $item)
                <div class="col-md-6 col-sm-6 nilai-type-col">
                    <a href="{{ $item['url'] }}"
                        class="nilai-type-option {{ $item['class'] }} {{ $active == $item['key'] ? 'active' : '' }}">
                        <span class="nilai-type-icon"><i class="fa {{ $item['icon'] }}"></i></span>
                        <span class="nilai-type-title">{{ $item['label'] }}</span>
                        <span class="nilai-type-desc">{{ $item['desc'] }}</span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
