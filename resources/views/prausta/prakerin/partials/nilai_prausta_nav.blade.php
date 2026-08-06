@php
    $active = isset($active) ? $active : '';
    $items = [
        [
            'key' => 'pkl',
            'label' => 'Data Nilai PKL',
            'desc' => 'Kelola nilai pembimbing dan seminar PKL mahasiswa.',
            'url' => url('/data_nilai_pkl_mahasiswa'),
            'icon' => 'fa-briefcase',
            'class' => 'type-pkl',
        ],
        [
            'key' => 'magang',
            'label' => 'Data Nilai Magang 1',
            'desc' => 'Kelola nilai pembimbing dan seminar Magang 1.',
            'url' => url('/data_nilai_magang_mahasiswa'),
            'icon' => 'fa-building',
            'class' => 'type-magang',
        ],
        [
            'key' => 'magang2',
            'label' => 'Data Nilai Magang 2',
            'desc' => 'Kelola nilai pembimbing dan seminar Magang 2.',
            'url' => url('/data_nilai_magang2_mahasiswa'),
            'icon' => 'fa-industry',
            'class' => 'type-magang2',
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
                <div class="col-md-4 col-sm-6 nilai-type-col">
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
