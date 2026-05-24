<style>
    .mhs-home-page .dashboard-hero {
        background: linear-gradient(135deg, #1f6d8c 0%, #2f9bb5 55%, #d9eef2 100%);
        border-radius: 14px;
        color: #fff;
        overflow: hidden;
        position: relative;
        margin-bottom: 18px;
        box-shadow: 0 8px 24px rgba(31, 109, 140, 0.18);
    }

    .mhs-home-page .dashboard-hero::after {
        content: '';
        position: absolute;
        inset: auto -60px -60px auto;
        width: 220px;
        height: 220px;
        background: rgba(255, 255, 255, 0.12);
        border-radius: 50%;
    }

    .mhs-home-page .dashboard-hero-body {
        position: relative;
        z-index: 1;
        padding: 24px 24px 22px;
        display: grid;
        grid-template-columns: minmax(0, 1.5fr) minmax(240px, .9fr);
        gap: 18px;
        align-items: stretch;
    }

    .mhs-home-page .dashboard-hero h2 {
        margin: 0 0 8px;
        font-size: 30px;
        font-weight: 700;
    }

    .mhs-home-page .dashboard-hero p {
        margin: 0;
        max-width: 700px;
        color: rgba(255, 255, 255, 0.9);
        line-height: 1.7;
        font-size: 14px;
    }

    .mhs-home-page .hero-meta {
        margin-top: 18px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .mhs-home-page .hero-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-width: 0;
    }

    .mhs-home-page .hero-eyebrow {
        display: inline-block;
        margin-bottom: 10px;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.18);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .35px;
        text-transform: uppercase;
    }

    .mhs-home-page .hero-badge {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.16);
        border: 1px solid rgba(255, 255, 255, 0.18);
        font-size: 12px;
        font-weight: 600;
    }

    .mhs-home-page .hero-spotlight {
        background: rgba(10, 46, 60, 0.18);
        border: 1px solid rgba(255, 255, 255, 0.14);
        border-radius: 16px;
        padding: 16px 18px;
        backdrop-filter: blur(4px);
        align-self: stretch;
    }

    .mhs-home-page .hero-spotlight-title {
        display: block;
        margin-bottom: 12px;
        color: rgba(255, 255, 255, 0.82);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .mhs-home-page .hero-spotlight-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .mhs-home-page .hero-spotlight-item + .hero-spotlight-item {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
    }

    .mhs-home-page .hero-spotlight-label {
        display: block;
        color: rgba(255, 255, 255, 0.72);
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 4px;
    }

    .mhs-home-page .hero-spotlight-value {
        display: block;
        color: #fff;
        font-size: 18px;
        font-weight: 700;
        line-height: 1.45;
    }

    .mhs-home-page .hero-spotlight-note {
        display: block;
        margin-top: 2px;
        color: rgba(255, 255, 255, 0.78);
        font-size: 12px;
        line-height: 1.5;
    }

    .mhs-home-page .profile-card,
    .mhs-home-page .panel-card,
    .mhs-home-page .nav-tabs-custom,
    .mhs-home-page .info-box,
    .mhs-home-page .small-box,
    .mhs-home-page .box {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 14px rgba(0, 0, 0, 0.08);
    }

    .mhs-home-page .profile-card .box-body,
    .mhs-home-page .panel-card .box-body {
        padding: 20px;
    }

    .mhs-home-page .profile-card .box-body {
        padding: 0;
    }

    .mhs-home-page .profile-header {
        padding: 24px 20px 18px;
        text-align: center;
        background: linear-gradient(180deg, #f4fafc 0%, #ffffff 100%);
        border-bottom: 1px solid #e8eff4;
    }

    .mhs-home-page .profile-avatar {
        width: 104px;
        height: 104px;
        object-fit: cover;
        border: 4px solid #fff;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
        margin: 0 auto 12px;
    }

    .mhs-home-page .profile-name {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        color: #2c3b41;
    }

    .mhs-home-page .profile-nim {
        margin: 6px 0 14px;
        color: #7b8794;
        font-size: 14px;
    }

    .mhs-home-page .profile-subtitle {
        margin: -4px 0 14px;
        color: #6d7a86;
        font-size: 13px;
        line-height: 1.6;
    }

    .mhs-home-page .profile-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        margin-bottom: 14px;
    }

    .mhs-home-page .profile-tag {
        display: inline-block;
        padding: 7px 12px;
        border-radius: 999px;
        background: #f1f7fb;
        color: #356d84;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #d7e8f0;
    }

    .mhs-home-page .profile-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin: 0 20px;
        padding: 16px 0;
    }

    .mhs-home-page .profile-stat {
        padding: 12px 14px;
        border-radius: 12px;
        background: #f8fbfd;
        border: 1px solid #e3edf3;
        text-align: left;
    }

    .mhs-home-page .profile-stat-label {
        display: block;
        color: #8b97a3;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .3px;
        margin-bottom: 4px;
    }

    .mhs-home-page .profile-stat-value {
        display: block;
        color: #2c3b41;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.5;
        word-break: break-word;
    }

    .mhs-home-page .profile-actions {
        padding: 0 20px 20px;
    }

    .mhs-home-page .profile-actions .btn {
        margin-bottom: 8px;
        border-radius: 10px;
        font-weight: 600;
    }

    .mhs-home-page .panel-heading-note {
        display: block;
        margin-top: 4px;
        color: #8b97a3;
        font-size: 12px;
        line-height: 1.5;
    }

    .mhs-home-page .details-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .mhs-home-page .details-item {
        padding: 12px 0;
        border-bottom: 1px solid #eef2f5;
    }

    .mhs-home-page .details-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .mhs-home-page .details-label {
        display: block;
        color: #8b97a3;
        font-size: 11px;
        text-transform: uppercase;
        margin-bottom: 3px;
    }

    .mhs-home-page .details-value {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        color: #2c3b41;
        font-weight: 600;
        line-height: 1.5;
    }

    .mhs-home-page .details-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 9px;
        border-radius: 999px;
        background: #f4fafc;
        border: 1px solid #dceaf2;
        color: #356d84;
        font-size: 11px;
        font-weight: 700;
    }

    .mhs-home-page .summary-card {
        min-height: 124px;
        border-radius: 14px;
        background: #fff;
        border: 1px solid #e6edf2;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);
        padding: 16px 18px;
        position: relative;
        overflow: hidden;
    }

    .mhs-home-page .summary-card::after {
        content: '';
        position: absolute;
        right: -24px;
        bottom: -24px;
        width: 88px;
        height: 88px;
        border-radius: 50%;
        background: rgba(47, 137, 165, 0.06);
    }

    .mhs-home-page .summary-card-head {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .mhs-home-page .summary-card-icon {
        width: 44px;
        height: 44px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 20px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .mhs-home-page .summary-card-label {
        display: block;
        text-transform: uppercase;
        letter-spacing: .5px;
        color: #7b8794;
        font-size: 11px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .mhs-home-page .summary-card-value {
        display: block;
        color: #2c3b41;
        font-size: 17px;
        line-height: 1.4;
        font-weight: 800;
        word-break: break-word;
        position: relative;
        z-index: 1;
    }

    .mhs-home-page .summary-card-note {
        display: block;
        color: #7b8794;
        font-size: 12px;
        line-height: 1.5;
        margin-top: 0;
        position: relative;
        z-index: 1;
    }

    .mhs-home-page .summary-card-meta {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 8px;
        padding: 5px 10px;
        border-radius: 999px;
        background: #f5f9fb;
        color: #5f6c78;
        font-size: 11px;
        font-weight: 600;
        position: relative;
        z-index: 1;
    }

    .mhs-home-page .summary-card-aqua .summary-card-icon {
        background: linear-gradient(135deg, #17b5df 0%, #1693c8 100%);
    }

    .mhs-home-page .summary-card-green .summary-card-icon {
        background: linear-gradient(135deg, #10b15d 0%, #0b8f4b 100%);
    }

    .mhs-home-page .summary-card-yellow .summary-card-icon {
        background: linear-gradient(135deg, #fea508 0%, #e18e00 100%);
    }

    .mhs-home-page .summary-card-red .summary-card-icon {
        background: linear-gradient(135deg, #e74b37 0%, #cb3f2e 100%);
    }

    .mhs-home-page .summary-row {
        margin-bottom: 18px;
    }

    .mhs-home-page .activity-grid {
        margin-bottom: 18px;
    }

    .mhs-home-page .schedule-card {
        min-height: 234px;
        border: 1px solid #e6edf2;
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.06);
    }

    .mhs-home-page .schedule-card .box-body {
        padding: 18px;
    }

    .mhs-home-page .schedule-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 12px;
        padding: 6px 11px;
        border-radius: 999px;
        background: #edf7fb;
        color: #2f89a5;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .mhs-home-page .schedule-note {
        margin: 0 0 14px;
        color: #6d7a86;
        font-size: 13px;
        line-height: 1.6;
    }

    .mhs-home-page .countdown-box {
        background: linear-gradient(135deg, #31266b 0%, #4a3c8a 100%);
        color: #fec503;
        border-radius: 12px;
        padding: 20px 18px;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        line-height: 1.75;
        min-height: 124px;
        box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08);
    }

    .mhs-home-page .countdown-box .digit,
    .mhs-home-page .countdown-box .judul {
        color: #fff;
    }

    .mhs-home-page .activity-footer {
        margin-top: 14px;
        color: #7b8794;
        font-size: 12px;
        line-height: 1.6;
    }

    .mhs-home-page .announcement-panel .box-body {
        padding: 18px 18px 10px;
    }

    .mhs-home-page .announcement-footer {
        padding: 12px 18px 16px;
        border-top: 1px solid #eef2f5;
        background: #fbfdfe;
        text-align: right;
    }

    .mhs-home-page .panel-heading-clean {
        padding: 14px 18px;
        border-bottom: 1px solid #edf1f4;
        background: #fafcfd;
    }

    .mhs-home-page .panel-heading-clean .box-title {
        font-weight: 700;
    }

    .mhs-home-page .announcement-list {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .mhs-home-page .announcement-item {
        display: flex;
        gap: 12px;
        padding: 14px 0;
        border-bottom: 1px solid #eef2f5;
    }

    .mhs-home-page .announcement-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .mhs-home-page .announcement-icon {
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #eaf4f8;
        color: #2f89a5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .mhs-home-page .announcement-title {
        display: block;
        color: #2c3b41;
        font-weight: 700;
        line-height: 1.45;
        margin-bottom: 4px;
    }

    .mhs-home-page .announcement-meta {
        display: block;
        color: #7b8794;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .mhs-home-page .announcement-desc {
        color: #5f6c78;
        font-size: 13px;
        line-height: 1.6;
    }

    .mhs-home-page .table-wrap {
        border-radius: 12px;
        overflow-x: auto;
        overflow-y: hidden;
        border: 1px solid #e6edf2;
        background: #fff;
        -webkit-overflow-scrolling: touch;
    }

    .mhs-home-page .table-wrap .table {
        margin-bottom: 0;
        min-width: 860px;
    }

    .mhs-home-page .table-wrap .table > thead > tr > th {
        background: #f6fafc;
        color: #5f6c78;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .35px;
        border-bottom: 1px solid #e6edf2;
    }

    .mhs-home-page .table-wrap .table > tbody > tr > td,
    .mhs-home-page .table-wrap .table > thead > tr > th {
        vertical-align: middle;
    }

    .mhs-home-page .table-wrap .table > tbody > tr:hover {
        background: #f9fcfe;
    }

    .mhs-home-page .tab-panel-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 14px;
        margin-bottom: 14px;
    }

    .mhs-home-page .tab-panel-title {
        margin: 0;
        color: #2c3b41;
        font-size: 20px;
        font-weight: 700;
    }

    .mhs-home-page .tab-panel-subtitle {
        margin: 5px 0 0;
        color: #7b8794;
        font-size: 13px;
        line-height: 1.6;
    }

    .mhs-home-page .tab-panel-badge {
        display: inline-block;
        padding: 8px 12px;
        border-radius: 999px;
        background: #eef7fb;
        border: 1px solid #d7e8f0;
        color: #356d84;
        font-size: 12px;
        font-weight: 700;
        white-space: nowrap;
    }

    .mhs-home-page .row-index {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #eef7fb;
        color: #2f89a5;
        font-weight: 700;
    }

    .mhs-home-page .desktop-course-title {
        display: block;
        color: #2c3b41;
        font-weight: 700;
        line-height: 1.5;
    }

    .mhs-home-page .desktop-course-meta {
        display: block;
        color: #7b8794;
        font-size: 12px;
        margin-top: 4px;
    }

    .mhs-home-page .desktop-value-pill {
        display: inline-block;
        min-width: 44px;
        padding: 6px 10px;
        border-radius: 999px;
        background: #fff3f1;
        color: #cf4a36;
        font-weight: 700;
        text-align: center;
        border: 1px solid #f5d2cc;
    }

    .mhs-home-page .mobile-course-list {
        display: none;
    }

    .mhs-home-page .course-card {
        border: 1px solid #e7eaee;
        border-radius: 12px;
        margin-bottom: 14px;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .mhs-home-page .course-card-toggle {
        display: block;
        width: 100%;
        border: 0;
        background: transparent;
        padding: 14px;
        text-align: left;
    }

    .mhs-home-page .course-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .mhs-home-page .course-card-title {
        color: #2c3b41;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .mhs-home-page .course-card-meta {
        color: #7b8794;
        font-size: 12px;
        margin-bottom: 10px;
    }

    .mhs-home-page .course-card-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .mhs-home-page .course-card-badge {
        display: inline-block;
        min-width: 62px;
        padding: 6px 10px;
        border-radius: 999px;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .mhs-home-page .course-card-badge.status-ok {
        background: #e8f7ee;
        color: #1e8c4d;
    }

    .mhs-home-page .course-card-badge.status-warn {
        background: #fff3df;
        color: #b97900;
    }

    .mhs-home-page .course-card-badge.status-danger {
        background: #fff1ef;
        color: #d65445;
    }

    .mhs-home-page .course-card-chevron {
        color: #91a0ac;
        font-size: 14px;
        transition: transform 0.25s ease;
        margin-top: 6px;
    }

    .mhs-home-page .course-card.expanded .course-card-chevron {
        transform: rotate(180deg);
    }

    .mhs-home-page .course-card-body {
        display: none;
        padding: 0 14px 14px;
        border-top: 1px solid #eef2f5;
    }

    .mhs-home-page .course-card.expanded .course-card-body {
        display: block;
    }

    .mhs-home-page .course-card-item {
        background: #f8fafc;
        border-radius: 8px;
        padding: 9px 10px;
    }

    .mhs-home-page .course-card-label {
        display: block;
        color: #8b97a3;
        font-size: 10px;
        text-transform: uppercase;
        margin-bottom: 3px;
    }

    .mhs-home-page .course-card-value {
        display: block;
        color: #2c3b41;
        font-weight: 600;
        line-height: 1.45;
    }

    .mhs-home-page .empty-box {
        padding: 30px 16px;
        text-align: center;
        color: #7b8794;
    }

    .mhs-home-page .empty-box .fa {
        font-size: 28px;
        margin-bottom: 8px;
        color: #b7c0cb;
    }

    .mhs-home-page .nav-tabs-custom > .nav-tabs > li > a {
        font-weight: 600;
        border-radius: 0;
        color: #5f6c78;
        transition: all 0.25s ease;
        padding: 12px 18px;
        position: relative;
        border-top: 3px solid transparent;
    }

    .mhs-home-page .nav-tabs-custom > .nav-tabs > li > a:hover {
        color: #2f89a5;
        background: #f3f8fb;
    }

    .mhs-home-page .nav-tabs-custom > .nav-tabs > li.active > a,
    .mhs-home-page .nav-tabs-custom > .nav-tabs > li.active > a:hover,
    .mhs-home-page .nav-tabs-custom > .nav-tabs > li.active > a:focus {
        background: #fff;
        color: #2c3b41;
        border-top: 3px solid #2f89a5;
        border-left-color: #e6edf2;
        border-right-color: #e6edf2;
        box-shadow: none;
    }

    .mhs-home-page .nav-tabs-custom > .nav-tabs > li.active > a::after {
        content: '';
        position: absolute;
        left: 18px;
        right: 18px;
        bottom: 10px;
        height: 2px;
        background: linear-gradient(90deg, #2f89a5, #49aac2);
        border-radius: 999px;
    }

    .mhs-home-page .nav-tabs-custom > .tab-content {
        padding: 18px;
        border-top: 1px solid #eef2f5;
        background: linear-gradient(180deg, #ffffff 0%, #fbfdfe 100%);
    }

    @media (max-width: 767px) {
        .mhs-home-page .dashboard-hero-body {
            grid-template-columns: 1fr;
        }

        .mhs-home-page .dashboard-hero h2 {
            font-size: 24px;
        }

        .mhs-home-page .dashboard-hero-body {
            padding: 20px 18px;
        }

        .mhs-home-page .hero-meta {
            gap: 8px;
        }

        .mhs-home-page .hero-spotlight {
            padding: 14px 15px;
        }

        .mhs-home-page .hero-spotlight-value {
            font-size: 16px;
        }

        .mhs-home-page .summary-card {
            min-height: 0;
        }

        .mhs-home-page .announcement-footer {
            text-align: left;
        }

        .mhs-home-page .profile-stats {
            grid-template-columns: 1fr;
        }

        .mhs-home-page .desktop-course-table {
            display: none;
        }

        .mhs-home-page .mobile-course-list {
            display: block;
        }

        .mhs-home-page .course-card-grid {
            grid-template-columns: 1fr;
        }

        .mhs-home-page .nav-tabs-custom > .nav-tabs {
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
        }

        .mhs-home-page .nav-tabs-custom > .nav-tabs > li {
            float: none;
            display: inline-block;
        }

        .mhs-home-page .nav-tabs-custom > .nav-tabs > li > a {
            padding: 10px 14px;
        }

        .mhs-home-page .tab-panel-head {
            flex-direction: column;
        }
    }
</style>

@php
    $editProfileUrl = $mhs->id_mhs == null ? '/update/' . $mhs->idstudent : '/change/' . $mhs->id;
@endphp

<div class="row mhs-home-page">
    <div class="col-md-4 col-lg-3">
        <div class="box box-primary profile-card">
            <div class="box-body">
                <div class="profile-header">
                    <img class="img-circle profile-avatar" src="{{ $mhs->photo_url }}" alt="User profile picture">
                    <h3 class="profile-name">{{ $mhs->nama }}</h3>
                    <p class="profile-nim">{{ $mhs->nim }}</p>
                    <p class="profile-subtitle">
                        Ringkasan identitas mahasiswa yang aktif dipakai pada layanan akademik.
                    </p>

                    <div class="profile-tags">
                        <span class="profile-tag">{{ $mhs->display_prodi ?: '-' }}</span>
                        <span class="profile-tag">{{ $mhs->kelas ?: '-' }}</span>
                        <span class="profile-tag">Angkatan {{ $mhs->angkatan ?: '-' }}</span>
                    </div>
                </div>

                <div class="profile-stats">
                    <div class="profile-stat">
                        <span class="profile-stat-label">Virtual Account</span>
                        <span class="profile-stat-value">{{ $mhs->virtual_account ?: '-' }}</span>
                    </div>
                    <div class="profile-stat">
                        <span class="profile-stat-label">Kontak Aktif</span>
                        <span class="profile-stat-value">{{ $mhs->display_phone }}</span>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="/ganti_foto/{{ $mhs->nim }}" class="btn btn-default btn-block">
                        <i class="fa fa-camera"></i> Ganti Foto
                    </a>
                    <a href="{{ $editProfileUrl }}" class="btn btn-success btn-block">
                        <i class="fa fa-edit"></i> Edit No HP dan E-mail
                    </a>
                </div>
            </div>
        </div>

        <div class="box box-primary panel-card">
            <div class="panel-heading-clean">
                <h3 class="box-title">Data Mahasiswa</h3>
                <span class="panel-heading-note">Informasi akun dan data pendukung yang tersimpan di sistem.</span>
            </div>
            <div class="box-body">
                <ul class="details-list">
                    <li class="details-item">
                        <span class="details-label">Microsoft Teams Username</span>
                        <span class="details-value">
                            <span>{{ $mhs->username ?: '-' }}</span>
                            @if ($mhs->username)
                                <span class="details-badge"><i class="fa fa-check-circle"></i> Tersedia</span>
                            @endif
                        </span>
                    </li>
                    <li class="details-item">
                        <span class="details-label">Microsoft Teams Password</span>
                        <span class="details-value">
                            <span>{{ $mhs->password ?: '-' }}</span>
                            @if ($mhs->password)
                                <span class="details-badge"><i class="fa fa-lock"></i> Aktif</span>
                            @endif
                        </span>
                    </li>
                    <li class="details-item">
                        <span class="details-label">NISN</span>
                        <span class="details-value">
                            <span>{{ $mhs->nisn ?: '-' }}</span>
                            <a class="btn btn-warning btn-xs" data-toggle="modal"
                                data-target="#modalUpdateNisn{{ $mhs->idstudent }}" title="Edit NISN">
                                <i class="fa fa-edit"></i>
                            </a>
                        </span>
                    </li>
                    <li class="details-item">
                        <span class="details-label">No. HP</span>
                        <span class="details-value">{{ $mhs->display_phone }}</span>
                    </li>
                    <li class="details-item">
                        <span class="details-label">E-mail</span>
                        <span class="details-value">{{ $mhs->display_email }}</span>
                    </li>
                    <li class="details-item">
                        <span class="details-label">Virtual Account</span>
                        <span class="details-value">
                            <span>{{ $mhs->virtual_account ?: '-' }}</span>
                            @if ($mhs->virtual_account)
                                <span class="details-badge"><i class="fa fa-credit-card"></i> Tersimpan</span>
                            @endif
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="modal fade" id="modalUpdateNisn{{ $mhs->idstudent }}" tabindex="-1"
            aria-labelledby="modalUpdateKaprodi" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update NISN</h5>
                    </div>
                    <div class="modal-body">
                        <form action="/put_nisn/{{ $mhs->idstudent }}" method="post">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label>NISN Mahasiswa</label>
                                <input class="form-control" type="number" name="nisn" value="{{ $mhs->nisn }}">
                            </div>
                            <input type="hidden" name="updated_by" value="{{ Auth::user()->name }}">
                            <button type="submit" class="btn btn-primary">Perbarui Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-lg-9">
        <div class="dashboard-hero">
            <div class="dashboard-hero-body">
                <div class="hero-content">
                    <span class="hero-eyebrow">Portal Mahasiswa</span>
                    <h2>Halo, {{ $mhs->nama }}</h2>
                    <p>
                        Pantau status akademik, jadwal layanan, paket matakuliah, dan informasi kampus dari satu halaman
                        yang ringkas, responsif, dan lebih nyaman dipakai setiap hari.
                    </p>
                    <div class="hero-meta">
                        <span class="hero-badge">{{ $tahun ? $tahun->periode_tahun : '-' }} {{ $tipe ? $tipe->periode_tipe : '' }}</span>
                        <span class="hero-badge">Paket Matakuliah: {{ $dashboard['paket_count'] }}</span>
                        <span class="hero-badge">Matakuliah Mengulang: {{ $dashboard['mengulang_count'] }}</span>
                        <span class="hero-badge">Informasi: {{ $dashboard['info_count'] }}</span>
                    </div>
                </div>
                <div class="hero-spotlight">
                    <span class="hero-spotlight-title">Ringkasan Saat Ini</span>
                    <ul class="hero-spotlight-list">
                        <li class="hero-spotlight-item">
                            <span class="hero-spotlight-label">Status KRS</span>
                            <span class="hero-spotlight-value">{{ $dashboard['krs_status'] }}</span>
                            <span class="hero-spotlight-note">{{ $dashboard['krs_schedule'] }}</span>
                        </li>
                        <li class="hero-spotlight-item">
                            <span class="hero-spotlight-label">Status EDOM</span>
                            <span class="hero-spotlight-value">{{ $dashboard['edom_status'] }}</span>
                            <span class="hero-spotlight-note">{{ $dashboard['edom_schedule'] }}</span>
                        </li>
                        <li class="hero-spotlight-item">
                            <span class="hero-spotlight-label">Informasi Kampus</span>
                            <span class="hero-spotlight-value">{{ $dashboard['info_count'] }} Info Aktif</span>
                            <span class="hero-spotlight-note">Pantau pembaruan terbaru dari kampus.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row summary-row">
            <div class="col-sm-6 col-lg-3">
                <div class="summary-card summary-card-aqua">
                    <div class="summary-card-head">
                        <div class="summary-card-icon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div>
                            <span class="summary-card-label">Tahun Akademik</span>
                            <span class="summary-card-value">{{ $tahun ? $tahun->periode_tahun : '-' }}</span>
                        </div>
                    </div>
                    <span class="summary-card-note">Periode akademik aktif</span>
                    <span class="summary-card-meta">
                        <i class="fa fa-bookmark-o"></i>
                        {{ $tipe ? $tipe->periode_tipe : '-' }}
                    </span>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="summary-card summary-card-green">
                    <div class="summary-card-head">
                        <div class="summary-card-icon">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                        <div>
                            <span class="summary-card-label">Status KRS</span>
                            <span class="summary-card-value">{{ $dashboard['krs_status'] }}</span>
                        </div>
                    </div>
                    <span class="summary-card-note">Akses jadwal pengisian KRS dari panel aktivitas.</span>
                    <span class="summary-card-meta">
                        <i class="fa fa-clock-o"></i>
                        {{ $time && (int) $time->status === 1 ? 'Jadwal tersedia' : 'Menunggu jadwal' }}
                    </span>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="summary-card summary-card-yellow">
                    <div class="summary-card-head">
                        <div class="summary-card-icon">
                            <i class="fa fa-clipboard"></i>
                        </div>
                        <div>
                            <span class="summary-card-label">Status EDOM</span>
                            <span class="summary-card-value">{{ $dashboard['edom_status'] }}</span>
                        </div>
                    </div>
                    <span class="summary-card-note">Pantau periode evaluasi dosen dari dashboard ini.</span>
                    <span class="summary-card-meta">
                        <i class="fa fa-clipboard"></i>
                        {{ $edom && (int) $edom->status === 1 ? 'Periode berjalan' : 'Belum dibuka' }}
                    </span>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="summary-card summary-card-red">
                    <div class="summary-card-head">
                        <div class="summary-card-icon">
                            <i class="fa fa-repeat"></i>
                        </div>
                        <div>
                            <span class="summary-card-label">Makul Mengulang</span>
                            <span class="summary-card-value">{{ $dashboard['mengulang_count'] }}</span>
                        </div>
                    </div>
                    <span class="summary-card-note">Daftar matakuliah yang perlu perhatian akademik.</span>
                    <span class="summary-card-meta">
                        <i class="fa fa-repeat"></i>
                        {{ $dashboard['mengulang_count'] > 0 ? 'Perlu ditinjau' : 'Aman' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#activity" data-toggle="tab">Aktivitas</a></li>
                <li><a href="#timeline" data-toggle="tab">Matakuliah Paket</a></li>
                <li><a href="#settings" data-toggle="tab">Matakuliah Mengulang</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane" id="activity">
                    <div class="tab-panel-head">
                        <div>
                            <h3 class="tab-panel-title">Aktivitas Akademik</h3>
                            <p class="tab-panel-subtitle">
                                Pantau jadwal layanan akademik aktif dan pembaruan informasi kampus dari satu tempat.
                            </p>
                        </div>
                        <span class="tab-panel-badge">{{ $dashboard['info_count'] }} Info Tersedia</span>
                    </div>

                    <div class="row activity-grid">
                        <div class="col-md-6">
                            <div class="box box-info schedule-card">
                                <div class="panel-heading-clean">
                                    <h3 class="box-title"><i class="glyphicon glyphicon-info-sign"></i> Waktu Pengisian KRS</h3>
                                </div>
                                <div class="box-body">
                                    <span class="schedule-status">
                                        <i class="fa fa-calendar-check-o"></i>
                                        {{ $dashboard['krs_status'] }}
                                    </span>
                                    <p class="schedule-note">
                                        Pastikan pengisian KRS dilakukan sesuai jadwal agar proses perwalian berjalan lancar.
                                    </p>
                                    <div id="krs-countdown" class="countdown-box"
                                        data-target="{{ $time && (int) $time->status === 1 ? $time->waktu_akhir : '' }}"
                                        data-message="menuju Penutupan Pengisian KRS">
                                        @if (!$time || (int) $time->status === 0)
                                            Belum ada info perwalian
                                        @endif
                                    </div>
                                    <div class="activity-footer">{{ $dashboard['krs_schedule'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box box-info schedule-card">
                                <div class="panel-heading-clean">
                                    <h3 class="box-title"><i class="glyphicon glyphicon-info-sign"></i> Waktu Pengisian EDOM</h3>
                                </div>
                                <div class="box-body">
                                    <span class="schedule-status">
                                        <i class="fa fa-clipboard"></i>
                                        {{ $dashboard['edom_status'] }}
                                    </span>
                                    <p class="schedule-note">
                                        Pantau periode EDOM agar evaluasi dosen dapat diselesaikan sebelum jadwal berakhir.
                                    </p>
                                    <div id="edom-countdown" class="countdown-box"
                                        data-target="{{ $edom && (int) $edom->status === 1 ? $edom->waktu_akhir : '' }}"
                                        data-message="menuju Penutupan Pengisian EDOM">
                                        @if (!$edom || (int) $edom->status === 0)
                                            Belum ada info pengisian EDOM
                                        @endif
                                    </div>
                                    <div class="activity-footer">{{ $dashboard['edom_schedule'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-primary panel-card announcement-panel">
                                <div class="panel-heading-clean">
                                    <h3 class="box-title">Informasi Terbaru</h3>
                                </div>
                                <div class="box-body">
                                    @if ($info->count())
                                        <ul class="announcement-list">
                                            @foreach ($info as $item)
                                                <li class="announcement-item">
                                                    <div class="announcement-icon">
                                                        <i class="fa fa-bell"></i>
                                                    </div>
                                                    <div>
                                                        <a href="/lihat/{{ $item->id_informasi }}" class="announcement-title">
                                                            {{ $item->judul }}
                                                        </a>
                                                        <span class="announcement-meta">
                                                            {{ date('d-m-Y', strtotime($item->created_at)) }} · {{ $item->created_at->diffForHumans() }}
                                                        </span>
                                                        <div class="announcement-desc">
                                                            {{ \Illuminate\Support\Str::limit(strip_tags($item->deskripsi), 180) }}
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="empty-box">
                                            <i class="fa fa-info-circle"></i>
                                            <div>Belum ada informasi terbaru.</div>
                                        </div>
                                    @endif
                                </div>
                                <div class="box-footer announcement-footer">
                                    <span class="text-muted pull-left hidden-xs">
                                        @if ($info->count())
                                            Menampilkan {{ $info->count() }} informasi terbaru.
                                        @else
                                            Belum ada informasi yang ditampilkan.
                                        @endif
                                    </span>
                                    @if ($dashboard['calendar_url'])
                                        <a href="{{ $dashboard['calendar_url'] }}" target="_blank" class="btn btn-default btn-sm">
                                            <i class="fa fa-download"></i> Unduh Kalender Akademik
                                        </a>
                                    @endif
                                    <a href="/lihat_semua" class="btn btn-primary btn-sm">
                                        <i class="fa fa-arrow-right"></i> Lihat Semua Informasi
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="timeline">
                    <div class="tab-panel-head hidden-xs">
                        <div>
                            <h4 class="tab-panel-title">Paket Matakuliah</h4>
                            <p class="tab-panel-subtitle">
                                Daftar matakuliah dalam kurikulum aktif yang menjadi acuan pengambilan studi Anda.
                            </p>
                        </div>
                        <span class="tab-panel-badge">{{ $dashboard['paket_count'] }} Matakuliah</span>
                    </div>

                    <div class="desktop-course-table table-wrap hidden-xs">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 80px;"><center>No</center></th>
                                    <th><center>Kurikulum</center></th>
                                    <th><center>Prodi</center></th>
                                    <th style="width: 90px;"><center>Semester</center></th>
                                    <th style="width: 90px;"><center>Angkatan</center></th>
                                    <th><center>Matakuliah</center></th>
                                    <th style="width: 110px;"><center>Status</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $index => $item)
                                    <tr>
                                        <td align="center"><span class="row-index">{{ $index + 1 }}</span></td>
                                        <td align="center">{{ $item->nama_kurikulum }}</td>
                                        <td align="center">{{ $item->prodi }}</td>
                                        <td align="center">{{ $item->semester }}</td>
                                        <td align="center">{{ $item->angkatan }}</td>
                                        <td>
                                            <span class="desktop-course-title">{{ $item->makul }}</span>
                                            <span class="desktop-course-meta">{{ $item->kode }}</span>
                                        </td>
                                        <td align="center">
                                            @if ($item->id_studentrecord != null)
                                                <span class="label label-success">Diambil</span>
                                            @else
                                                <span class="label label-warning">Belum</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mobile-course-list visible-xs-block">
                        @forelse ($data as $item)
                            <div class="course-card">
                                <button type="button" class="course-card-toggle">
                                    <div class="course-card-top">
                                        <div>
                                            <div class="course-card-title">{{ $item->kode }} / {{ $item->makul }}</div>
                                            <div class="course-card-meta">{{ $item->nama_kurikulum }}</div>
                                        </div>
                                        <div class="text-right">
                                            @if ($item->id_studentrecord != null)
                                                <span class="course-card-badge status-ok">Diambil</span>
                                            @else
                                                <span class="course-card-badge status-warn">Belum</span>
                                            @endif
                                            <div class="course-card-chevron">
                                                <i class="fa fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                                <div class="course-card-body">
                                    <div class="course-card-grid">
                                        <div class="course-card-item">
                                            <span class="course-card-label">Prodi</span>
                                            <span class="course-card-value">{{ $item->prodi }}</span>
                                        </div>
                                        <div class="course-card-item">
                                            <span class="course-card-label">Semester</span>
                                            <span class="course-card-value">{{ $item->semester }}</span>
                                        </div>
                                        <div class="course-card-item">
                                            <span class="course-card-label">Angkatan</span>
                                            <span class="course-card-value">{{ $item->angkatan }}</span>
                                        </div>
                                        <div class="course-card-item">
                                            <span class="course-card-label">Status</span>
                                            <span class="course-card-value">
                                                @if ($item->id_studentrecord != null)
                                                    Sudah diambil
                                                @else
                                                    Belum diambil
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-box">
                                <i class="fa fa-book"></i>
                                <div>Belum ada paket matakuliah.</div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="tab-pane" id="settings">
                    <div class="tab-panel-head hidden-xs">
                        <div>
                            <h4 class="tab-panel-title">Matakuliah Mengulang</h4>
                            <p class="tab-panel-subtitle">
                                Daftar matakuliah dengan nilai yang masih perlu diperbaiki pada pengambilan berikutnya.
                            </p>
                        </div>
                        <span class="tab-panel-badge">{{ $dashboard['mengulang_count'] }} Matakuliah</span>
                    </div>

                    <div class="desktop-course-table table-wrap hidden-xs">
                        <table id="example3" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 80px;"><center>No</center></th>
                                    <th><center>Kurikulum</center></th>
                                    <th><center>Prodi</center></th>
                                    <th style="width: 90px;"><center>Semester</center></th>
                                    <th style="width: 90px;"><center>Angkatan</center></th>
                                    <th><center>Matakuliah</center></th>
                                    <th style="width: 90px;"><center>Nilai</center></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_mengulang as $index => $item)
                                    <tr>
                                        <td align="center"><span class="row-index">{{ $index + 1 }}</span></td>
                                        <td align="center">{{ $item->nama_kurikulum }}</td>
                                        <td align="center">{{ $item->prodi }}</td>
                                        <td align="center">{{ $item->semester }}</td>
                                        <td align="center">{{ $item->angkatan }}</td>
                                        <td>
                                            <span class="desktop-course-title">{{ $item->makul }}</span>
                                            <span class="desktop-course-meta">{{ $item->kode }}</span>
                                        </td>
                                        <td align="center"><span class="desktop-value-pill">{{ $item->nilai_AKHIR }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mobile-course-list visible-xs-block">
                        @forelse ($data_mengulang as $item)
                            <div class="course-card">
                                <button type="button" class="course-card-toggle">
                                    <div class="course-card-top">
                                        <div>
                                            <div class="course-card-title">{{ $item->kode }} / {{ $item->makul }}</div>
                                            <div class="course-card-meta">{{ $item->nama_kurikulum }}</div>
                                        </div>
                                        <div class="text-right">
                                            <span class="course-card-badge status-danger">{{ $item->nilai_AKHIR }}</span>
                                            <div class="course-card-chevron">
                                                <i class="fa fa-chevron-down"></i>
                                            </div>
                                        </div>
                                    </div>
                                </button>
                                <div class="course-card-body">
                                    <div class="course-card-grid">
                                        <div class="course-card-item">
                                            <span class="course-card-label">Prodi</span>
                                            <span class="course-card-value">{{ $item->prodi }}</span>
                                        </div>
                                        <div class="course-card-item">
                                            <span class="course-card-label">Semester</span>
                                            <span class="course-card-value">{{ $item->semester }}</span>
                                        </div>
                                        <div class="course-card-item">
                                            <span class="course-card-label">Angkatan</span>
                                            <span class="course-card-value">{{ $item->angkatan }}</span>
                                        </div>
                                        <div class="course-card-item">
                                            <span class="course-card-label">Nilai</span>
                                            <span class="course-card-value">{{ $item->nilai_AKHIR }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-box">
                                <i class="fa fa-check-circle"></i>
                                <div>Tidak ada matakuliah yang wajib diulang.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        function initCourseCards() {
            var toggles = document.querySelectorAll('.course-card-toggle');
            Array.prototype.forEach.call(toggles, function(toggle, index) {
                var card = toggle.closest('.course-card');
                if (!card) {
                    return;
                }

                if (index === 0) {
                    card.classList.add('expanded');
                }

                toggle.addEventListener('click', function() {
                    card.classList.toggle('expanded');
                });
            });
        }

        function initCountdown(elementId) {
            var el = document.getElementById(elementId);
            if (!el) {
                return;
            }

            var target = el.getAttribute('data-target');
            var message = el.getAttribute('data-message') || '';
            if (!target) {
                return;
            }

            var targetDate = new Date(target).getTime();
            if (isNaN(targetDate)) {
                return;
            }

            var render = function() {
                var now = new Date().getTime();
                var diff = Math.floor((targetDate - now) / 1000);

                if (diff <= 0) {
                    el.innerHTML = "<span class='judul'>Waktu layanan telah berakhir</span>";
                    return;
                }

                var days = parseInt(diff / 86400, 10);
                diff = diff % 86400;
                var hours = parseInt(diff / 3600, 10);
                diff = diff % 3600;
                var minutes = parseInt(diff / 60, 10);
                var seconds = parseInt(diff % 60, 10);

                el.innerHTML = days + " <span class='digit'>hari</span> " +
                    hours + " <span class='digit'>jam</span> " +
                    minutes + " <span class='digit'>menit</span> " +
                    seconds + " <span class='digit'>detik</span><br>" +
                    "<span class='judul'>" + message + "</span>";
            };

            render();
            setInterval(render, 1000);
        }

        initCourseCards();
        initCountdown('krs-countdown');
        initCountdown('edom-countdown');
    })();
</script>
