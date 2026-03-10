<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'KOST AG') — Sistem Manajemen Kost</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    {{-- Google Fonts: Plus Jakarta Sans + DM Mono --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    {{-- SweetAlert2 --}}
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
        /* ═══════════════════════════════════════════
           ROOT VARIABLES
        ═══════════════════════════════════════════ */
        :root {
            --sidebar-w:       260px;
            --sidebar-bg:      #0d1117;
            --sidebar-border:  rgba(255,255,255,.06);
            --accent-blue:     #3b82f6;
            --accent-green:    #10b981;
            --accent-amber:    #f59e0b;
            --accent-rose:     #f43f5e;
            --surface:         #f4f6fb;
            --card-bg:         #ffffff;
            --text-primary:    #0f172a;
            --text-muted:      #64748b;
            --border:          #e2e8f0;
            --radius-lg:       16px;
            --radius-md:       10px;
            --shadow-sm:       0 1px 3px rgba(0,0,0,.05), 0 1px 2px rgba(0,0,0,.06);
            --shadow-md:       0 4px 16px rgba(0,0,0,.08), 0 1px 4px rgba(0,0,0,.04);
            --shadow-lg:       0 10px 40px rgba(0,0,0,.12);
            --transition:      all .2s cubic-bezier(.4,0,.2,1);
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--surface);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ═══════════════════════════════════════════
           SIDEBAR
        ═══════════════════════════════════════════ */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            min-height: 100vh;
            position: fixed;
            top: 0; left: 0;
            z-index: 200;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sidebar-border);
            transition: var(--transition);
        }

        /* Brand */
        .sidebar-brand {
            padding: 1.5rem 1.5rem 1.2rem;
            border-bottom: 1px solid var(--sidebar-border);
        }
        .brand-logo {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            box-shadow: 0 4px 12px rgba(59,130,246,.4);
        }
        .brand-text {
            font-weight: 800;
            font-size: 1.1rem;
            color: #fff;
            letter-spacing: -.3px;
            line-height: 1.2;
        }
        .brand-sub {
            font-size: .65rem;
            color: rgba(255,255,255,.3);
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-weight: 500;
        }

        /* Nav sections */
        .nav-section-label {
            font-size: .6rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255,255,255,.2);
            padding: 1.1rem 1.5rem .4rem;
        }

        .nav-item-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .6rem 1.2rem;
            margin: .1rem .75rem;
            border-radius: 9px;
            color: rgba(255,255,255,.55);
            text-decoration: none;
            font-size: .85rem;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }
        .nav-item-link:hover {
            color: rgba(255,255,255,.9);
            background: rgba(255,255,255,.07);
        }
        .nav-item-link.active {
            color: #fff;
            background: rgba(255,255,255,.1);
        }
        .nav-item-link.active-blue  { background: rgba(59,130,246,.18);  color: #93c5fd; }
        .nav-item-link.active-green { background: rgba(16,185,129,.15);  color: #6ee7b7; }
        .nav-item-link.active-amber { background: rgba(245,158,11,.15);  color: #fcd34d; }

        .nav-icon {
            width: 28px; height: 28px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 7px;
            font-size: .9rem;
            flex-shrink: 0;
        }
        .nav-item-link.active-blue  .nav-icon { background: rgba(59,130,246,.25); }
        .nav-item-link.active-green .nav-icon { background: rgba(16,185,129,.2); }
        .nav-item-link.active-amber .nav-icon { background: rgba(245,158,11,.2); }

        .nav-badge {
            margin-left: auto;
            font-size: .55rem;
            font-weight: 700;
            letter-spacing: .8px;
            padding: 2px 7px;
            border-radius: 20px;
            font-family: 'DM Mono', monospace;
        }
        .badge-qb { background: rgba(59,130,246,.2);  color: #93c5fd; }
        .badge-el { background: rgba(16,185,129,.15); color: #6ee7b7; }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--sidebar-border);
            margin-top: auto;
        }
        .sidebar-footer-text {
            font-size: .65rem;
            color: rgba(255,255,255,.2);
            text-align: center;
        }

        /* ═══════════════════════════════════════════
           MAIN CONTENT
        ═══════════════════════════════════════════ */
        .main-content {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            background: rgba(255,255,255,.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            padding: .9rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .topbar-left { display: flex; align-items: center; gap: 1rem; }
        .page-eyebrow {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--text-muted);
        }
        .page-heading {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -.3px;
        }
        .topbar-right { display: flex; align-items: center; gap: .75rem; }

        /* Method pill */
        .method-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .5px;
            padding: .3rem .85rem;
            border-radius: 20px;
            font-family: 'DM Mono', monospace;
        }
        .method-pill-qb { background: #dbeafe; color: #1d4ed8; }
        .method-pill-el { background: #d1fae5; color: #065f46; }

        /* Date chip */
        .date-chip {
            font-size: .75rem;
            color: var(--text-muted);
            background: #f1f5f9;
            padding: .35rem .9rem;
            border-radius: 20px;
            font-weight: 500;
        }

        /* ═══════════════════════════════════════════
           CONTENT AREA
        ═══════════════════════════════════════════ */
        .content-wrap { padding: 2rem; }

        /* Page header */
        .page-header-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .page-title-block .title {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -.5px;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.2;
        }
        .page-title-block .subtitle {
            font-size: .8rem;
            color: var(--text-muted);
            margin-top: .25rem;
        }
        .page-title-block .subtitle code {
            background: #f1f5f9;
            padding: .1rem .4rem;
            border-radius: 4px;
            color: #334155;
            font-family: 'DM Mono', monospace;
            font-size: .75rem;
        }

        /* ═══════════════════════════════════════════
           STAT CARDS
        ═══════════════════════════════════════════ */
        .stat-card {
            border: none;
            border-radius: var(--radius-lg);
            padding: 1.4rem;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
        .stat-card::after {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 80px; height: 80px;
            border-radius: 50%;
            transform: translate(20px,-20px);
            opacity: .12;
        }
        .stat-card.blue   { background: linear-gradient(135deg,#1d4ed8,#3b82f6); color:#fff; }
        .stat-card.green  { background: linear-gradient(135deg,#059669,#10b981); color:#fff; }
        .stat-card.rose   { background: linear-gradient(135deg,#be123c,#f43f5e); color:#fff; }
        .stat-card.amber  { background: linear-gradient(135deg,#b45309,#f59e0b); color:#fff; }
        .stat-card.purple { background: linear-gradient(135deg,#7c3aed,#a78bfa); color:#fff; }
        .stat-card.slate  { background: linear-gradient(135deg,#334155,#64748b); color:#fff; }

        .stat-card.blue::after   { background: #fff; }
        .stat-card.green::after  { background: #fff; }
        .stat-card.rose::after   { background: #fff; }
        .stat-card.amber::after  { background: #fff; }

        .stat-num {
            font-size: 2.2rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -1px;
        }
        .stat-label {
            font-size: .8rem;
            font-weight: 600;
            opacity: .85;
            margin-top: .3rem;
        }
        .stat-icon {
            position: absolute;
            right: 1.25rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 2.8rem;
            opacity: .2;
        }

        /* ═══════════════════════════════════════════
           FILTER CARD
        ═══════════════════════════════════════════ */
        .filter-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            padding: 1.25rem 1.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            margin-bottom: 1.25rem;
        }

        /* ═══════════════════════════════════════════
           TABLE CARD
        ═══════════════════════════════════════════ */
        .table-card {
            background: var(--card-bg);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            overflow: hidden;
        }
        .table-card-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
        }
        .table-card-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        .table thead th {
            background: #f8fafc;
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: .8px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding: .9rem 1.1rem;
            border-bottom: 2px solid var(--border);
            white-space: nowrap;
        }
        .table tbody td {
            padding: .9rem 1.1rem;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            font-size: .875rem;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr { transition: var(--transition); }
        .table tbody tr:hover { background: #f8fafc; }

        /* ═══════════════════════════════════════════
           BADGES
        ═══════════════════════════════════════════ */
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            font-size: .72rem;
            font-weight: 600;
            padding: .3rem .75rem;
            border-radius: 20px;
            letter-spacing: .2px;
        }
        .badge-status::before {
            content: '';
            width: 6px; height: 6px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .badge-tersedia  { background: #d1fae5; color: #065f46; }
        .badge-tersedia::before  { background: #10b981; }
        .badge-terisi    { background: #fee2e2; color: #991b1b; }
        .badge-terisi::before    { background: #f43f5e; }
        .badge-perbaikan { background: #fef3c7; color: #92400e; }
        .badge-perbaikan::before { background: #f59e0b; }
        .badge-aktif     { background: #d1fae5; color: #065f46; }
        .badge-aktif::before     { background: #10b981; }
        .badge-selesai   { background: #f1f5f9; color: #475569; }
        .badge-selesai::before   { background: #94a3b8; }

        .tipe-badge {
            font-size: .7rem;
            font-weight: 700;
            padding: .25rem .65rem;
            border-radius: 6px;
            letter-spacing: .5px;
            font-family: 'DM Mono', monospace;
        }
        .tipe-standar { background: #f1f5f9; color: #475569; }
        .tipe-deluxe  { background: #dbeafe; color: #1e40af; }
        .tipe-vip     { background: #fef3c7; color: #92400e; }

        /* ═══════════════════════════════════════════
           BUTTONS
        ═══════════════════════════════════════════ */
        .btn {
            font-weight: 600;
            font-size: .85rem;
            border-radius: 9px;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: .4rem;
        }
        .btn-primary-kost {
            background: linear-gradient(135deg,#1d4ed8,#3b82f6);
            color: #fff; border: none;
            box-shadow: 0 4px 14px rgba(59,130,246,.35);
        }
        .btn-primary-kost:hover {
            background: linear-gradient(135deg,#1e40af,#2563eb);
            color: #fff;
            box-shadow: 0 6px 20px rgba(59,130,246,.45);
            transform: translateY(-1px);
        }
        .btn-success-kost {
            background: linear-gradient(135deg,#059669,#10b981);
            color: #fff; border: none;
            box-shadow: 0 4px 14px rgba(16,185,129,.3);
        }
        .btn-success-kost:hover {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(16,185,129,.4);
        }
        .btn-action {
            width: 32px; height: 32px;
            padding: 0;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: .85rem;
            transition: var(--transition);
            border: 1.5px solid transparent;
        }
        .btn-action:hover { transform: translateY(-1px); }
        .btn-view   { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
        .btn-view:hover   { background: #dbeafe; color: #1d4ed8; }
        .btn-edit   { background: #fffbeb; color: #d97706; border-color: #fde68a; }
        .btn-edit:hover   { background: #fef3c7; color: #b45309; }
        .btn-delete { background: #fff1f2; color: #e11d48; border-color: #fecdd3; }
        .btn-delete:hover { background: #ffe4e6; color: #be123c; }
        .btn-checkout { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
        .btn-checkout:hover { background: #dcfce7; color: #166534; }

        /* ═══════════════════════════════════════════
           FORM CARD
        ═══════════════════════════════════════════ */
        .form-card {
            background: #fff;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border);
            overflow: hidden;
        }
        .form-card-header {
            padding: 1.3rem 1.75rem;
            border-bottom: 1px solid var(--border);
        }
        .form-card-header.blue-header {
            background: linear-gradient(135deg,#1e3a8a,#1d4ed8);
        }
        .form-card-header.green-header {
            background: linear-gradient(135deg,#064e3b,#059669);
        }
        .form-card-body { padding: 2rem 1.75rem; }

        .form-section-head {
            font-size: .7rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-muted);
            padding-bottom: .6rem;
            border-bottom: 2px solid var(--border);
            margin-bottom: 1.25rem;
        }

        .form-control, .form-select {
            border-radius: 9px;
            border: 1.5px solid #e2e8f0;
            font-size: .875rem;
            padding: .6rem 1rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            transition: var(--transition);
            background: #fff;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,.12);
            outline: none;
        }
        .form-control.is-invalid, .form-select.is-invalid {
            border-color: #f43f5e;
            box-shadow: none;
        }
        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: .4rem;
        }
        .form-text { font-size: .75rem; color: var(--text-muted); }
        .input-group-text {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            border-radius: 9px;
            font-size: .875rem;
            color: var(--text-muted);
        }

        /* Total harga box */
        .total-box {
            background: linear-gradient(135deg,#f0fdf4,#dcfce7);
            border: 1.5px solid #bbf7d0;
            border-radius: 12px;
            padding: 1rem 1.25rem;
        }
        .total-box-value {
            font-size: 1.4rem;
            font-weight: 800;
            color: #065f46;
            letter-spacing: -.5px;
        }

        /* ═══════════════════════════════════════════
           ALERT / FLASH
        ═══════════════════════════════════════════ */
        .flash-alert {
            border-radius: 12px;
            border: none;
            display: flex;
            align-items: center;
            gap: .75rem;
            font-size: .875rem;
            font-weight: 500;
            padding: .9rem 1.25rem;
            animation: slideDown .3s ease;
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .flash-alert.success { background: #f0fdf4; color: #166534; border-left: 4px solid #10b981; }
        .flash-alert.danger  { background: #fff1f2; color: #9f1239; border-left: 4px solid #f43f5e; }

        /* ═══════════════════════════════════════════
           CODE HINT BAR
        ═══════════════════════════════════════════ */
        .code-hint {
            background: #0f172a;
            border-radius: 10px;
            padding: .65rem 1.1rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            margin-bottom: 1.25rem;
        }
        .code-hint code {
            font-family: 'DM Mono', monospace;
            font-size: .75rem;
            color: #7dd3fc;
        }
        .code-hint-label {
            font-size: .65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #64748b;
            white-space: nowrap;
        }

        /* ═══════════════════════════════════════════
           EMPTY STATE
        ═══════════════════════════════════════════ */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }
        .empty-icon {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            opacity: .4;
        }
        .empty-title { font-weight: 700; color: var(--text-primary); }
        .empty-sub   { color: var(--text-muted); font-size: .875rem; }

        /* ═══════════════════════════════════════════
           SISA HARI BADGE
        ═══════════════════════════════════════════ */
        .sisa-badge {
            display: inline-block;
            background: #fee2e2;
            color: #991b1b;
            font-size: .65rem;
            font-weight: 700;
            padding: .15rem .5rem;
            border-radius: 5px;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0%,100% { opacity: 1; }
            50%      { opacity: .6; }
        }

        /* ═══════════════════════════════════════════
           PAGINATION
        ═══════════════════════════════════════════ */
        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            border: 1.5px solid var(--border);
            color: var(--text-muted);
            font-size: .8rem;
            font-weight: 600;
            padding: .4rem .8rem;
            transition: var(--transition);
        }
        .pagination .page-link:hover { background: #f1f5f9; border-color: #cbd5e1; color: var(--text-primary); }
        .pagination .page-item.active .page-link { background: #3b82f6; border-color: #3b82f6; color: #fff; }
        .pagination .page-item.disabled .page-link { opacity: .4; }

        /* ═══════════════════════════════════════════
           RESPONSIVE
        ═══════════════════════════════════════════ */
        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .content-wrap { padding: 1.25rem; }
        }

        /* ═══════════════════════════════════════════
           SWEETALERT2 CUSTOM
        ═══════════════════════════════════════════ */
        .swal2-popup {
            border-radius: 20px !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            padding: 2rem !important;
        }
        .swal2-title { font-weight: 800 !important; letter-spacing: -.3px !important; }
        .swal2-confirm {
            border-radius: 9px !important;
            font-weight: 700 !important;
            padding: .6rem 1.5rem !important;
        }
        .swal2-cancel {
            border-radius: 9px !important;
            font-weight: 700 !important;
            padding: .6rem 1.5rem !important;
        }
        .swal2-icon { border-width: 3px !important; }
    </style>
    @stack('styles')
</head>
<body>

{{-- ═══════ SIDEBAR ═══════ --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">
            <div class="brand-icon">🏠</div>
            <div>
                <div class="brand-text">KOST AG</div>
                <div class="brand-sub">Management System</div>
            </div>
        </div>
    </div>

    <nav class="flex-grow-1 py-2" style="overflow-y:auto;">
        <div class="nav-section-label">Utama</div>
        <a href="{{ route('dashboard') }}"
           class="nav-item-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-speedometer2"></i></span>
            Dashboard
        </a>

        <div class="nav-section-label mt-1">Query Builder</div>
        <a href="{{ route('kamar.index') }}"
           class="nav-item-link {{ request()->routeIs('kamar.*') ? 'active active-blue' : '' }}">
            <span class="nav-icon"><i class="bi bi-door-open-fill"></i></span>
            Kamar
        </a>
        <a href="{{ route('kamar.laporan') }}"
           class="nav-item-link {{ request()->routeIs('kamar.laporan') ? 'active active-blue' : '' }}">
            <span class="nav-icon"><i class="bi bi-bar-chart-fill"></i></span>
            Laporan Kamar
        </a>

        <div class="nav-section-label mt-1">Eloquent ORM</div>
        <a href="{{ route('karyawan.index') }}"
           class="nav-item-link {{ request()->routeIs('karyawan.*') ? 'active active-green' : '' }}">
            <span class="nav-icon"><i class="bi bi-person-badge-fill"></i></span>
            Karyawan
        </a>
        <a href="{{ route('penyewa.index') }}"
           class="nav-item-link {{ request()->routeIs('penyewa.*') ? 'active active-amber' : '' }}">
            <span class="nav-icon"><i class="bi bi-people-fill"></i></span>
            Penyewa
        </a>
        <a href="{{ route('penyewa.laporan') }}"
           class="nav-item-link {{ request()->routeIs('penyewa.laporan') ? 'active active-amber' : '' }}">
            <span class="nav-icon"><i class="bi bi-cash-coin"></i></span>
            Lap. Pendapatan
        </a>

        <div class="nav-section-label mt-1">Sistem</div>
        <a href="{{ route('setting.index') }}"
           class="nav-item-link {{ request()->routeIs('setting.*') ? 'active' : '' }}">
            <span class="nav-icon"><i class="bi bi-gear-fill"></i></span>
            Setting
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-footer-text">KOST_AG v1.0 · Laravel 11</div>
    </div>
</aside>

{{-- ═══════ MAIN ═══════ --}}
<div class="main-content">

    {{-- TOPBAR --}}
    <div class="topbar">
        <div class="topbar-left">
            <button class="btn btn-sm d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')"
                    style="border:1.5px solid var(--border);border-radius:8px;width:36px;height:36px;padding:0;">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div>
                <div class="page-eyebrow">@yield('breadcrumb', 'KOST AG')</div>
                <div class="page-heading">@yield('title', 'Dashboard')</div>
            </div>
        </div>
        <div class="topbar-right">
            @hasSection('method_badge')@yield('method_badge')@endif
            <div class="date-chip">
                <i class="bi bi-calendar3 me-1"></i>{{ now()->translatedFormat('d M Y') }}
            </div>
        </div>
    </div>

    {{-- FLASH via SweetAlert --}}
    @if(session('success') || session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
                customClass: { popup: 'swal2-toast-custom' }
            });
            @endif
            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                timer: 4000,
                timerProgressBar: true,
                showConfirmButton: false,
                toast: true,
                position: 'top-end',
            });
            @endif
        });
    </script>
    @endif

    {{-- CONTENT --}}
    <div class="content-wrap">
        @yield('content')
    </div>

</div>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ── Global SweetAlert delete handler ──
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-swal-delete').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form  = this.closest('form');
            const nama  = this.dataset.nama  || 'data ini';
            const type  = this.dataset.type  || 'item';
            Swal.fire({
                title: 'Hapus ' + type + '?',
                html:  'Data <strong>' + nama + '</strong> akan dihapus permanen.',
                icon:  'warning',
                iconColor: '#f43f5e',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-trash me-1"></i> Ya, Hapus',
                cancelButtonText:  '<i class="bi bi-x me-1"></i> Batal',
                confirmButtonColor: '#f43f5e',
                cancelButtonColor:  '#64748b',
                reverseButtons: true,
            }).then(result => { if (result.isConfirmed) form.submit(); });
        });
    });

    // ── Checkout confirm ──
    document.querySelectorAll('.btn-swal-checkout').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const nama = this.dataset.nama || 'penyewa';
            const kamar = this.dataset.kamar || '';
            Swal.fire({
                title: 'Checkout Penyewa?',
                html: '<b>' + nama + '</b> akan checkout dari <b>Kamar ' + kamar + '</b>.<br>Kamar akan kembali tersedia.',
                icon: 'question',
                iconColor: '#3b82f6',
                showCancelButton: true,
                confirmButtonText: '<i class="bi bi-box-arrow-right me-1"></i> Ya, Checkout',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
            }).then(result => { if (result.isConfirmed) form.submit(); });
        });
    });

    // ── Toggle status karyawan ──
    document.querySelectorAll('.btn-swal-toggle').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form   = this.closest('form');
            const nama   = this.dataset.nama;
            const status = this.dataset.status;
            Swal.fire({
                title: 'Ubah Status?',
                html: 'Status <b>' + nama + '</b> akan diubah menjadi <b>' + (status === 'aktif' ? 'Nonaktif' : 'Aktif') + '</b>.',
                icon: 'question',
                iconColor: '#f59e0b',
                showCancelButton: true,
                confirmButtonText: 'Ya, Ubah',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#64748b',
                reverseButtons: true,
            }).then(result => { if (result.isConfirmed) form.submit(); });
        });
    });
});
</script>
@stack('scripts')
</body>
</html>