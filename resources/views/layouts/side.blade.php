@if (Auth::user()->role == 1)
    @include('layouts.side_sadmin')

@elseif (Auth::user()->role ==2)
    @include('layouts.side_dosen_dlm')

@elseif (Auth::user()->role == 3)
    @include('layouts.side_mhs')

@elseif (Auth::user()->role == 5)
    @include('layouts.side_dosen_luar')

@elseif (Auth::user()->role == 6)
    @include('layouts.side_kaprodi')

@elseif (Auth::user()->role == 7)
    @include('layouts.side_wadir1')

@elseif (Auth::user()->role == 8)
    @include('layouts.side_bauk')

@elseif (Auth::user()->role == 9)
    @include('layouts.side_admin_prodi')

@elseif (Auth::user()->role == 11)
    @include('layouts.side_admin_prausta')

@endif
