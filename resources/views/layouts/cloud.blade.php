<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laundry Cloud Dashboard')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *,
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
            background: #F0F4FA;
        }

        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-thumb {
            background: #CBD5E1;
            border-radius: 99px;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            height: 100vh;
            background: #0F172A;
            display: flex;
            flex-direction: column;
            z-index: 30;
            overflow-y: auto;
        }

        .sidebar-logo {
            padding: 22px 20px 16px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
        }

        .logo-mark {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .logo-text {
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.3px;
            line-height: 1.2;
        }

        .logo-sub {
            font-size: 10px;
            color: #64748B;
            font-weight: 500;
        }

        .sidebar-section {
            padding: 16px 12px 8px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: #334155;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 10px;
            margin: 2px 8px;
            color: #94A3B8;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all .15s;
            cursor: pointer;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, .06);
            color: #E2E8F0;
        }

        .nav-item.active {
            background: rgba(99, 102, 241, .2);
            color: #A5B4FC;
            font-weight: 600;
        }

        .nav-item svg {
            flex-shrink: 0;
        }

        .nav-badge {
            margin-left: auto;
            background: #EF4444;
            color: #fff;
            font-size: 10px;
            font-weight: 700;
            padding: 1px 6px;
            border-radius: 99px;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 12px;
            border-top: 1px solid rgba(255, 255, 255, .06);
        }

        .user-row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 10px;
            cursor: pointer;
        }

        .user-row:hover {
            background: rgba(255, 255, 255, .05);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 12px;
            font-weight: 600;
            color: #E2E8F0;
        }

        .user-role {
            font-size: 10px;
            color: #475569;
        }

        /* Main area */
        .main-wrap {
            margin-left: 220px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #E8EDF5;
            padding: 14px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 20;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-title {
            font-size: 18px;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -.3px;
        }

        .page-breadcrumb {
            font-size: 12px;
            color: #94A3B8;
            margin-top: 1px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tb-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 10px;
            border: 1.5px solid #E2E8F0;
            background: #fff;
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
        }

        .tb-btn:hover {
            border-color: #6366F1;
            color: #6366F1;
        }

        .tb-btn-primary {
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 3px 10px rgba(99, 102, 241, .3);
        }

        .tb-btn-primary:hover {
            color: #fff;
            opacity: .9;
        }

        .page-content {
            flex: 1;
            padding: 28px;
        }

        /* Cards */
        .card {
            background: #fff;
            border-radius: 18px;
            border: 1.5px solid #EEF0F6;
            box-shadow: 0 1px 10px rgba(0, 0, 0, .04);
            overflow: hidden;
        }

        .card-header {
            padding: 18px 22px 14px;
            border-bottom: 1px solid #F1F5F9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-title {
            font-size: 14px;
            font-weight: 700;
            color: #0F172A;
        }

        .card-sub {
            font-size: 12px;
            color: #94A3B8;
            margin-top: 2px;
        }

        .card-body {
            padding: 20px 22px;
        }

        /* KPI cards */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .kpi {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            border: 1.5px solid #EEF0F6;
            position: relative;
            overflow: hidden;
        }

        .kpi-icon {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 14px;
        }

        .kpi-val {
            font-size: 28px;
            font-weight: 900;
            letter-spacing: -.6px;
            color: #0F172A;
        }

        .kpi-label {
            font-size: 11px;
            font-weight: 600;
            color: #94A3B8;
            margin-top: 4px;
            letter-spacing: .02em;
        }

        .kpi-trend {
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .trend-up {
            color: #10B981;
        }

        .trend-down {
            color: #EF4444;
        }

        /* Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead {
            background: #F8F9FF;
        }

        .data-table th {
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #94A3B8;
            padding: 12px 16px;
            border-bottom: 1.5px solid #EEF0F6;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #F1F5F9;
            transition: background .1s;
        }

        .data-table tbody tr:last-child {
            border-bottom: none;
        }

        .data-table tbody tr:hover {
            background: #F8F9FF;
        }

        .data-table td {
            padding: 13px 16px;
            font-size: 13px;
            color: #334155;
        }

        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 9px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-pending {
            background: #FEF3C7;
            color: #B45309;
        }

        .badge-processing {
            background: #DBEAFE;
            color: #1D4ED8;
        }

        .badge-ready {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-delivered {
            background: #F1F5F9;
            color: #64748B;
        }

        .badge-cancelled {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .badge-paid {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-partial {
            background: #FEF3C7;
            color: #B45309;
        }

        .badge-unpaid {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .badge-active {
            background: #D1FAE5;
            color: #065F46;
        }

        .badge-inactive {
            background: #F1F5F9;
            color: #64748B;
        }

        /* Forms */
        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #64748B;
            margin-bottom: 6px;
        }

        .form-input {
            width: 100%;
            font-size: 13px;
            padding: 10px 14px;
            border-radius: 12px;
            border: 1.5px solid #E2E8F0;
            color: #1E293B;
            background: #fff;
            outline: none;
            transition: border-color .15s;
        }

        .form-input:focus {
            border-color: #6366F1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, .08);
        }

        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236B7280'%3E%3Cpath fill-rule='evenodd' d='M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 18px;
            padding-right: 36px;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            transition: all .15s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #6366F1, #8B5CF6);
            color: #fff;
            box-shadow: 0 3px 12px rgba(99, 102, 241, .35);
        }

        .btn-primary:hover {
            opacity: .9;
        }

        .btn-secondary {
            background: #fff;
            color: #64748B;
            border: 1.5px solid #E2E8F0;
        }

        .btn-secondary:hover {
            border-color: #6366F1;
            color: #6366F1;
        }

        .btn-danger {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .btn-danger:hover {
            background: #FECACA;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 8px;
        }

        /* Modal */
        .modal-bg {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .55);
            backdrop-filter: blur(6px);
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .modal {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 24px 64px rgba(0, 0, 0, .2);
            width: 100%;
            z-index: 51;
            overflow: hidden;
        }

        .modal-hdr {
            padding: 20px 24px 14px;
            border-bottom: 1px solid #F1F5F9;
        }

        .modal-title {
            font-size: 15px;
            font-weight: 800;
            color: #0F172A;
        }

        .modal-sub {
            font-size: 12px;
            color: #94A3B8;
            margin-top: 2px;
        }

        .modal-bdy {
            padding: 20px 24px;
        }

        .modal-ftr {
            padding: 14px 24px 20px;
            border-top: 1px solid #F1F5F9;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Misc */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 0;
            color: #94A3B8;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: #F8F9FD;
            border: 2px dashed #E2E8F0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
        }

        .empty-title {
            font-size: 14px;
            font-weight: 700;
            color: #64748B;
        }

        .empty-sub {
            font-size: 12px;
            color: #CBD5E1;
            margin-top: 4px;
        }

        .divider {
            height: 1px;
            background: #F1F5F9;
            margin: 16px 0;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
        }

        .alert-success {
            background: #D1FAE5;
            color: #065F46;
            border: 1px solid #A7F3D0;
        }

        .alert-error {
            background: #FEE2E2;
            color: #B91C1C;
            border: 1px solid #FECACA;
        }

        .filter-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 16px 20px;
            border-bottom: 1px solid #F1F5F9;
            flex-wrap: wrap;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 180px;
        }

        .search-wrap svg {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #94A3B8;
        }

        .search-input {
            width: 100%;
            padding: 8px 12px 8px 36px;
            border-radius: 10px;
            border: 1.5px solid #E2E8F0;
            font-size: 13px;
            outline: none;
            transition: border-color .15s;
        }

        .search-input:focus {
            border-color: #6366F1;
        }

        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            border-top: 1px solid #F1F5F9;
            font-size: 12px;
            color: #64748B;
        }

        .page-btns {
            display: flex;
            gap: 4px;
        }

        .page-btn {
            padding: 5px 10px;
            border-radius: 8px;
            border: 1.5px solid #E2E8F0;
            background: #fff;
            font-size: 12px;
            font-weight: 600;
            color: #64748B;
            cursor: pointer;
        }

        .page-btn:hover {
            border-color: #6366F1;
            color: #6366F1;
        }

        .page-btn.active {
            background: #6366F1;
            color: #fff;
            border-color: #6366F1;
        }

        /* Charts placeholder */
        .chart-area {
            height: 220px;
            background: #F8F9FD;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            color: #94A3B8;
            border: 1.5px dashed #E2E8F0;
        }

        /* Sync pulse */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: .4;
            }
        }

        .syncing {
            animation: pulse 1.5s infinite;
        }
    </style>
    @yield('styles')
</head>

<body>
    <div x-data="cloud()" class="d-flex">

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="logo-mark">
                    <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                </div>
                <div>
                    <div class="logo-text">Laundry</div>
                    <div class="logo-sub">Cloud Dashboard</div>
                </div>
            </div>

            <div class="sidebar-section">Main</div>
            <a @click.prevent="page='dashboard'" href="#" :class="page === 'dashboard' ? 'active' : ''"
                class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            <a @click.prevent="page='orders'" href="#" :class="page === 'orders' ? 'active' : ''"
                class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Orders
                <span x-show="counts.pending>0" x-text="counts.pending" class="nav-badge"></span>
            </a>
            <a @click.prevent="page='customers'" href="#" :class="page === 'customers' ? 'active' : ''"
                class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Customers
            </a>

            <div class="sidebar-section">Operations</div>
            <a @click.prevent="page='delivery'" href="#" :class="page === 'delivery' ? 'active' : ''"
                class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                </svg>
                Delivery
            </a>
            <a @click.prevent="page='inventory'" href="#" :class="page === 'inventory' ? 'active' : ''"
                class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                Inventory
                <span x-show="counts.lowStock>0" x-text="counts.lowStock" class="nav-badge"
                    style="background:#F59E0B;"></span>
            </a>
            <a @click.prevent="page='expenses'" href="#" :class="page === 'expenses' ? 'active' : ''"
                class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z" />
                </svg>
                Expenses
            </a>

            <div class="sidebar-section">Analytics</div>
            <a @click.prevent="page='reports'" href="#" :class="page === 'reports' ? 'active' : ''"
                class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Reports
            </a>

            <div class="sidebar-section">System</div>
            <a @click.prevent="page='sync'" href="#" :class="page === 'sync' ? 'active' : ''"
                class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Sync
                <span x-show="counts.syncPending>0" x-text="counts.syncPending" class="nav-badge"
                    style="background:#F59E0B;"></span>
            </a>
            <a @click.prevent="page='settings'" href="#" :class="page === 'settings' ? 'active' : ''"
                class="nav-item">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                Settings
            </a>

            <div class="sidebar-footer">
                <div class="user-row">
                    <div class="user-avatar">A</div>
                    <div>
                        <div class="user-name">Admin</div>
                        <div class="user-role">Branch Manager</div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- MAIN -->
        <div class="main-wrap">
            <header class="topbar">
                <div class="topbar-left">
                    <div>
                        <div class="page-title" x-text="pageTitle()"></div>
                        <div class="page-breadcrumb" x-text="'Laundry Cloud / '+pageTitle()"></div>
                    </div>
                </div>
                <div class="topbar-right">
                    <div style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;padding:6px 12px;border-radius:99px;"
                        :style="syncStatus.online ? 'background:#ECFDF5;color:#059669' : 'background:#FEF2F2;color:#DC2626'">
                        <span style="width:7px;height:7px;border-radius:50%;display:inline-block;"
                            :style="syncStatus.online ? 'background:#10B981' : 'background:#EF4444'"></span>
                        <span x-text="syncStatus.online?'Live':'Offline'"></span>
                    </div>
                    <a href="/pos" class="tb-btn">
                        <svg width="13" height="13" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Open POS
                    </a>
                    <button @click="doSync" :class="syncStatus.syncing ? 'syncing' : ''"
                        class="tb-btn tb-btn-primary">
                        <svg width="13" height="13" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span x-text="syncStatus.syncing?'Syncing…':'Sync Now'"></span>
                    </button>
                </div>
            </header>

            <div class="page-content">

                <!-- ════ DASHBOARD ════ -->
                <div x-show="page==='dashboard'">
                    @include('cloud.dashboard')
                </div>

                <!-- ════ ORDERS ════ -->
                <div x-show="page==='orders'">
                    @include('cloud.orders')
                </div>

                <!-- ════ CUSTOMERS ════ -->
                <div x-show="page==='customers'">
                    @include('cloud.customers')
                </div>

                <!-- ════ DELIVERY ════ -->
                <div x-show="page==='delivery'">
                    @include('cloud.delivery')
                </div>

                <!-- ════ INVENTORY ════ -->
                <div x-show="page==='inventory'">
                    @include('cloud.inventory')
                </div>

                <!-- ════ EXPENSES ════ -->
                <div x-show="page==='expenses'">
                    @include('cloud.expenses')
                </div>

                <!-- ════ REPORTS ════ -->
                <div x-show="page==='reports'">
                    @include('cloud.reports')
                </div>

                <!-- ════ SYNC ════ -->
                <div x-show="page==='sync'">
                    @include('cloud.sync')
                </div>

                <!-- ════ SETTINGS ════ -->
                <div x-show="page==='settings'">
                    @include('cloud.settings')
                </div>

            </div>
        </div>
    </div>

    <script>
        function cloud() {
            return {
                page: 'dashboard',
                syncStatus: {
                    online: true,
                    syncing: false
                },
                counts: {
                    pending: 3,
                    ready: 2,
                    lowStock: 1,
                    syncPending: 2
                },

                // ── Data stores ──
                orders: [],
                customers: [],
                services: [],
                deliveries: [],
                inventory: [],
                expenses: [],
                settings: {},

                // ── Dashboard ──
                kpi: {
                    revenue: 4820,
                    revenueChange: '+12%',
                    orders: 38,
                    ordersChange: '+5%',
                    customers: 142,
                    customersChange: '+8%',
                    collected: 4210,
                    collectedChange: '-2%',
                },
                recentOrders: [{
                        id: 1,
                        number: 'ORD-B1-20260311-0001',
                        customer: 'Amal Nasser',
                        status: 'pending',
                        total: 85,
                        time: '9:12 AM'
                    },
                    {
                        id: 2,
                        number: 'ORD-B1-20260311-0002',
                        customer: 'Sara Al Jabri',
                        status: 'processing',
                        total: 120,
                        time: '9:45 AM'
                    },
                    {
                        id: 3,
                        number: 'ORD-B1-20260311-0003',
                        customer: 'Khalid Ahmed',
                        status: 'ready',
                        total: 55,
                        time: '10:30 AM'
                    },
                    {
                        id: 4,
                        number: 'ORD-B1-20260311-0004',
                        customer: 'Fatima Zahra',
                        status: 'delivered',
                        total: 200,
                        time: '11:00 AM'
                    },
                    {
                        id: 5,
                        number: 'ORD-B1-20260311-0005',
                        customer: 'Omar Hassan',
                        status: 'pending',
                        total: 40,
                        time: '11:20 AM'
                    },
                ],
                topServices: [{
                        name: 'Standard Wash',
                        qty: 14,
                        revenue: 210
                    },
                    {
                        name: 'Dry Clean – Suit',
                        qty: 9,
                        revenue: 225
                    },
                    {
                        name: 'Shirt Ironing',
                        qty: 22,
                        revenue: 176
                    },
                    {
                        name: 'Blanket Wash',
                        qty: 6,
                        revenue: 180
                    },
                ],

                // ── Orders ──
                allOrders: [{
                        id: 1,
                        number: 'ORD-B1-20260311-0001',
                        customer: 'Amal Nasser',
                        status: 'pending',
                        payment_status: 'unpaid',
                        total: 85,
                        paid: 0,
                        time: '9:12 AM'
                    },
                    {
                        id: 2,
                        number: 'ORD-B1-20260311-0002',
                        customer: 'Sara Al Jabri',
                        status: 'processing',
                        payment_status: 'paid',
                        total: 120,
                        paid: 120,
                        time: '9:45 AM'
                    },
                    {
                        id: 3,
                        number: 'ORD-B1-20260311-0003',
                        customer: 'Khalid Ahmed',
                        status: 'ready',
                        payment_status: 'paid',
                        total: 55,
                        paid: 55,
                        time: '10:30 AM'
                    },
                    {
                        id: 4,
                        number: 'ORD-B1-20260311-0004',
                        customer: 'Fatima Zahra',
                        status: 'delivered',
                        payment_status: 'paid',
                        total: 200,
                        paid: 200,
                        time: '11:00 AM'
                    },
                    {
                        id: 5,
                        number: 'ORD-B1-20260311-0005',
                        customer: 'Omar Hassan',
                        status: 'pending',
                        payment_status: 'partial',
                        total: 40,
                        paid: 20,
                        time: '11:20 AM'
                    },
                    {
                        id: 6,
                        number: 'ORD-B1-20260311-0006',
                        customer: 'Amal Nasser',
                        status: 'cancelled',
                        payment_status: 'unpaid',
                        total: 30,
                        paid: 0,
                        time: '12:00 PM'
                    },
                ],
                orderFilter: 'all',
                orderPayFilter: 'all',
                orderPage: 1,
                orderSearch: '',
                get filteredOrders() {
                    let list = this.allOrders;
                    if (this.orderFilter !== 'all') list = list.filter(o => o.status === this.orderFilter);
                    if (this.orderSearch) list = list.filter(o => o.number.toLowerCase().includes(this.orderSearch
                        .toLowerCase()) || o.customer.toLowerCase().includes(this.orderSearch.toLowerCase()));
                    return list;
                },

                // ── Customers ──
                allCustomers: [{
                        id: 1,
                        name: 'Amal Nasser',
                        phone: '055-1234',
                        email: 'amal@example.com',
                        orders: 12,
                        wallet: 50,
                        loyalty: 120
                    },
                    {
                        id: 2,
                        name: 'Sara Al Jabri',
                        phone: '056-5678',
                        email: 'sara@example.com',
                        orders: 8,
                        wallet: 0,
                        loyalty: 80
                    },
                    {
                        id: 3,
                        name: 'Khalid Ahmed',
                        phone: '057-9012',
                        email: 'khalid@example.com',
                        orders: 24,
                        wallet: 150,
                        loyalty: 240
                    },
                    {
                        id: 4,
                        name: 'Fatima Zahra',
                        phone: '058-3456',
                        email: 'fatima@example.com',
                        orders: 5,
                        wallet: 20,
                        loyalty: 50
                    },
                    {
                        id: 5,
                        name: 'Omar Hassan',
                        phone: '059-7890',
                        email: 'omar@example.com',
                        orders: 3,
                        wallet: 0,
                        loyalty: 30
                    },
                ],
                customerSearch: '',
                get filteredCustomers() {
                    if (!this.customerSearch) return this.allCustomers;
                    const s = this.customerSearch.toLowerCase();
                    return this.allCustomers.filter(c => c.name.toLowerCase().includes(s) || c.phone.includes(s) || c
                        .email.toLowerCase().includes(s));
                },

                // ── Delivery ──
                allDeliveries: [{
                        id: 1,
                        order: 'ORD-B1-0001',
                        customer: 'Amal Nasser',
                        staff: 'Rami Khoury',
                        status: 'assigned',
                        address: '123 Main St',
                        scheduled: '2:00 PM'
                    },
                    {
                        id: 2,
                        order: 'ORD-B1-0003',
                        customer: 'Khalid Ahmed',
                        staff: 'Samir Haddad',
                        status: 'picked_up',
                        address: '45 Park Ave',
                        scheduled: '3:00 PM'
                    },
                    {
                        id: 3,
                        order: 'ORD-B1-0004',
                        customer: 'Fatima Zahra',
                        staff: 'Rami Khoury',
                        status: 'delivered',
                        address: '7 Oak Rd',
                        scheduled: '1:00 PM'
                    },
                ],
                deliveryFilter: 'all',
                deliverySearch: '',
                get filteredDeliveries() {
                    let list = this.deliveryFilter === 'all' ? this.allDeliveries : this.allDeliveries.filter(d => d
                        .status === this.deliveryFilter);
                    if (!this.deliverySearch) return list;
                    const q = this.deliverySearch.toLowerCase();
                    return list.filter(d => (d.customer_name || d.customer || '').toLowerCase().includes(q) || (d
                        .staff_name || '').toLowerCase().includes(q));
                },

                // ── Inventory ──
                allInventory: [{
                        id: 1,
                        name: 'Detergent (5L)',
                        sku: 'DET-5L',
                        quantity: 12,
                        unit: 'bottles',
                        min_quantity: 5,
                        unit_cost: 8.5,
                        category: 'chemicals',
                        description: ''
                    },
                    {
                        id: 2,
                        name: 'Fabric Softener',
                        sku: 'FAB-SF',
                        quantity: 3,
                        unit: 'bottles',
                        min_quantity: 5,
                        unit_cost: 6.0,
                        category: 'chemicals',
                        description: ''
                    },
                    {
                        id: 3,
                        name: 'Dry-Clean Fluid',
                        sku: 'DCF-1L',
                        quantity: 8,
                        unit: 'liters',
                        min_quantity: 3,
                        unit_cost: 22.0,
                        category: 'chemicals',
                        description: ''
                    },
                    {
                        id: 4,
                        name: 'Hangers (100pk)',
                        sku: 'HNG-100',
                        quantity: 200,
                        unit: 'pcs',
                        min_quantity: 50,
                        unit_cost: 0.1,
                        category: 'supplies',
                        description: ''
                    },
                    {
                        id: 5,
                        name: 'Plastic Bags',
                        sku: 'BAG-PL',
                        quantity: 400,
                        unit: 'pcs',
                        min_quantity: 100,
                        unit_cost: 0.05,
                        category: 'supplies',
                        description: ''
                    },
                ],
                inventorySearch: '',
                inventoryFilter: 'all',
                get filteredInventory() {
                    let list = this.allInventory;
                    if (this.inventoryFilter === 'low') list = list.filter(i => this.isLow(i));
                    if (!this.inventorySearch) return list;
                    const q = this.inventorySearch.toLowerCase();
                    return list.filter(i => i.name.toLowerCase().includes(q) || (i.sku || '').toLowerCase().includes(
                    q));
                },

                // ── Expenses ──
                allExpenses: [{
                        id: 1,
                        date: '2026-03-11',
                        category: 'utilities',
                        amount: 220,
                        description: 'Electricity bill',
                        created_by: 'Admin'
                    },
                    {
                        id: 2,
                        date: '2026-03-10',
                        category: 'supplies',
                        amount: 85,
                        description: 'Detergent restock',
                        created_by: 'Admin'
                    },
                    {
                        id: 3,
                        date: '2026-03-09',
                        category: 'maintenance',
                        amount: 150,
                        description: 'Washing machine fix',
                        created_by: 'Admin'
                    },
                    {
                        id: 4,
                        date: '2026-03-08',
                        category: 'salary',
                        amount: 1200,
                        description: 'Staff weekly pay',
                        created_by: 'Admin'
                    },
                    {
                        id: 5,
                        date: '2026-03-07',
                        category: 'rent',
                        amount: 800,
                        description: 'Monthly rent',
                        created_by: 'Admin'
                    },
                ],
                expenseFilter: 'all',
                get filteredExpenses() {
                    if (this.expenseFilter === 'all') return this.allExpenses;
                    return this.allExpenses.filter(e => e.category === this.expenseFilter);
                },

                // ── Reports ──
                reportPeriod: 'daily',
                dailyData: {
                    date: '2026-03-11',
                    total_orders: 38,
                    gross_revenue: 4820,
                    net_revenue: 4365,
                    collected: 4210,
                    total_collected: 4210,
                    total_due: 610,
                    total_discounts: 455,
                    total_expenses: 455,
                    net_profit: 3755,
                    orders_by_status: {
                        pending: 5,
                        processing: 8,
                        ready: 4,
                        delivered: 21
                    },
                    payments_by_method: {
                        cash: 2100,
                        card: 1800,
                        upi: 310
                    },
                },

                // ── Sync ──
                syncQueue: [{
                        id: 1,
                        model: 'Order',
                        action: 'create',
                        status: 'pending',
                        attempts: 0,
                        created: '5 min ago'
                    },
                    {
                        id: 2,
                        model: 'Customer',
                        action: 'update',
                        status: 'pending',
                        attempts: 1,
                        created: '8 min ago'
                    },
                    {
                        id: 3,
                        model: 'Order',
                        action: 'update',
                        status: 'failed',
                        attempts: 5,
                        created: '1 hr ago'
                    },
                    {
                        id: 4,
                        model: 'Order',
                        action: 'create',
                        status: 'synced',
                        attempts: 1,
                        created: '2 hr ago'
                    },
                ],

                syncStatusFilter: 'all',
                get filteredSyncQueue() {
                    if (this.syncStatusFilter === 'all') return this.syncQueue;
                    return this.syncQueue.filter(s => s.status === this.syncStatusFilter);
                },
                lastSyncTime: null,
                isOnline: navigator.onLine,

                // ── Settings ──
                appSettings: {
                    shop_name: 'Laundry Express',
                    currency: 'USD',
                    loyalty_rate: '10',
                    loyalty_redeem_rate: '0.01',
                    tax_rate: '0',
                    delivery_fee: '5',
                    auto_sync: true,
                    sms_notifications: false,
                    wallet_enabled: true,
                    loyalty_enabled: true,
                    sync_interval: '5',
                    address: '',
                    shop_phone: '',
                    receipt_footer: 'Thank you for your business!',
                    per_page: '20',
                    date_format: 'YYYY-MM-DD',
                },

                // ── Modals ──
                showOrderDetail: false,
                selectedOrder: null,
                showAddExpense: false,
                newExpense: {
                    date: '',
                    category: 'supplies',
                    amount: '',
                    description: ''
                },
                showAdjustStock: false,
                selectedItem: null,
                adjustType: 'add',
                adjustQty: '',
                adjustReason: '',

                // ── Methods ──
                pageTitle() {
                    const map = {
                        dashboard: 'Dashboard',
                        orders: 'Orders',
                        customers: 'Customers',
                        delivery: 'Delivery',
                        inventory: 'Inventory',
                        expenses: 'Expenses',
                        reports: 'Reports',
                        sync: 'Cloud Sync',
                        settings: 'Settings'
                    };
                    return map[this.page] || 'Dashboard';
                },
                fmt(v) {
                    return new Intl.NumberFormat('en-US', {
                        style: 'currency',
                        currency: 'USD'
                    }).format(v);
                },
                fmtNum(v) {
                    return new Intl.NumberFormat('en-US').format(v);
                },
                badge(s) {
                    const m = {
                        pending: 'badge-pending',
                        processing: 'badge-processing',
                        washing: 'badge-processing',
                        drying: 'badge-processing',
                        ready: 'badge-ready',
                        delivered: 'badge-delivered',
                        cancelled: 'badge-cancelled',
                        paid: 'badge-paid',
                        partial: 'badge-partial',
                        unpaid: 'badge-unpaid',
                        assigned: 'badge-processing',
                        picked_up: 'badge-processing',
                        in_transit: 'badge-processing',
                        failed: 'badge-cancelled',
                        synced: 'badge-paid',
                        active: 'badge-active',
                        inactive: 'badge-inactive'
                    };
                    return 'badge ' + (m[s] || 'badge-delivered');
                },
                isLow(item) {
                    return item.quantity <= item.min_quantity;
                },
                setOrderStatus(id, status) {
                    const o = this.allOrders.find(x => x.id === id);
                    if (o) {
                        o.status = status;
                        if (id === this.selectedOrder?.id) this.selectedOrder.status = status;
                    }
                },
                openOrder(o) {
                    this.selectedOrder = o;
                    this.showOrderDetail = true;
                },
                saveExpense() {
                    if (!this.newExpense.amount || !this.newExpense.date) return;
                    this.allExpenses.unshift({
                        id: Date.now(),
                        ...this.newExpense,
                        created_by: 'Admin'
                    });
                    this.showAddExpense = false;
                    this.newExpense = {
                        date: '',
                        category: 'supplies',
                        amount: '',
                        description: ''
                    };
                },
                openAdjust(item) {
                    this.selectedItem = item;
                    this.adjustType = 'add';
                    this.adjustQty = '';
                    this.adjustReason = '';
                    this.showAdjustStock = true;
                },
                confirmAdjust() {
                    if (!this.adjustQty || !this.selectedItem) return;
                    const qty = parseFloat(this.adjustQty);
                    if (this.adjustType === 'add') this.selectedItem.quantity += qty;
                    else if (this.adjustType === 'remove') this.selectedItem.quantity = Math.max(0, this.selectedItem
                        .quantity - qty);
                    else this.selectedItem.quantity = qty;
                    this.showAdjustStock = false;
                },
                saveSettings() {
                    alert('Settings saved!');
                },
                doSync() {
                    this.syncStatus.syncing = true;
                    setTimeout(() => {
                        this.syncStatus.syncing = false;
                        this.counts.syncPending = 0;
                        this.lastSyncTime = new Date().toLocaleTimeString();
                        this.syncQueue.filter(s => s.status === 'pending').forEach(s => s.status = 'synced');
                    }, 2000);
                },
                retrySync() {
                    this.syncQueue.filter(s => s.status === 'failed').forEach(s => s.status = 'pending');
                    this.doSync();
                },
                retrySingle(id) {
                    const s = this.syncQueue.find(x => x.id === id);
                    if (s) {
                        s.status = 'pending';
                        s.attempts = 0;
                    }
                    this.doSync();
                },
                updateDelivery(id, status) {
                    const d = this.allDeliveries.find(x => x.id === id);
                    if (d) {
                        d.status = status;
                        if (status === 'delivered') d.delivered_at = new Date().toLocaleTimeString();
                    }
                },
                deleteExpense(id) {
                    if (!confirm('Delete this expense?')) return;
                    this.allExpenses = this.allExpenses.filter(e => e.id !== id);
                },
                openAddExpense() {
                    this.newExpense = {
                        date: new Date().toISOString().slice(0, 10),
                        category: 'supplies',
                        amount: '',
                        description: ''
                    };
                    this.showAddExpense = true;
                },
                loadSettings() {
                    // Reset to saved — in real app, fetch from API
                    alert('Settings reset to saved values.');
                },
                reportDate: new Date().toISOString().slice(0, 10),
                loadReport() {
                    // In real app, fetch report from API with this.reportDate and this.reportPeriod
                    console.log('Loading report for', this.reportPeriod, this.reportDate);
                },

                init() {
                    window.addEventListener('online', () => {
                        this.syncStatus.online = true;
                        this.isOnline = true;
                    });
                    window.addEventListener('offline', () => {
                        this.syncStatus.online = false;
                        this.isOnline = false;
                    });
                },
            };
        }
    </script>

    @yield('scripts')
</body>

</html>
