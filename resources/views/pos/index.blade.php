@extends('layouts.app')
@section('title', 'POS — Laundry System')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
<style>
*,body{font-family:'Inter',sans-serif;-webkit-font-smoothing:antialiased;}
html,body,#app,#app>main{height:100%;margin:0;padding:0;}
::-webkit-scrollbar{width:4px;height:4px;}
::-webkit-scrollbar-thumb{background:#CBD5E1;border-radius:99px;}

/* ──── Root ──── */
.pos-root{display:flex;height:100vh;overflow:hidden;background:#F8F9FD;}

/* ──── Rail ──── */
.rail{width:64px;background:#fff;border-right:1px solid #E8EDF5;display:flex;flex-direction:column;align-items:center;padding:14px 0;gap:4px;flex-shrink:0;}
.rail-logo{width:38px;height:38px;border-radius:14px;background:linear-gradient(135deg,#6366F1 0%,#8B5CF6 100%);display:flex;align-items:center;justify-content:center;margin-bottom:12px;box-shadow:0 4px 14px rgba(99,102,241,.4);}
.rail-btn{width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;cursor:pointer;color:#94A3B8;border:none;background:transparent;transition:all .15s;position:relative;}
.rail-btn:hover{background:#F1F5FF;color:#6366F1;}
.rail-btn.active{background:linear-gradient(135deg,#6366F1,#8B5CF6);color:#fff;box-shadow:0 4px 12px rgba(99,102,241,.35);}
.rail-badge{position:absolute;top:-3px;right:-3px;background:#EF4444;color:#fff;font-size:9px;font-weight:700;min-width:16px;height:16px;border-radius:99px;display:flex;align-items:center;justify-content:center;padding:0 4px;border:2px solid #fff;}
.rail-spacer{flex:1;}

/* ──── Main/Panel splits ──── */
.pos-main{flex:1;display:flex;flex-direction:column;overflow:hidden;min-width:0;}
.pos-topbar{background:#fff;border-bottom:1px solid #E8EDF5;padding:14px 22px;display:flex;align-items:center;justify-content:space-between;flex-shrink:0;}
.pos-title{font-size:20px;font-weight:800;color:#0F172A;letter-spacing:-.4px;}
.pos-subtitle{font-size:11px;color:#94A3B8;margin-top:1px;}
.topbar-right{display:flex;align-items:center;gap:10px;}
.status-pill{display:flex;align-items:center;gap:5px;padding:5px 12px;border-radius:99px;font-size:11px;font-weight:600;}
.sp-online{background:#ECFDF5;color:#059669;}
.sp-offline{background:#FEF2F2;color:#DC2626;}
.sp-dot{width:6px;height:6px;border-radius:50%;}
.sp-dot-online{background:#10B981;}
.sp-dot-offline{background:#EF4444;}
.sp-shift{background:#EFF6FF;color:#2563EB;border:1px solid #BFDBFE;}
.topbar-btn{display:flex;align-items:center;gap:6px;padding:7px 14px;border-radius:10px;border:1.5px solid #E2E8F0;background:#fff;font-size:12px;font-weight:600;color:#64748B;cursor:pointer;transition:all .15s;}
.topbar-btn:hover{border-color:#6366F1;color:#6366F1;}

/* ──── Category pills ──── */
.cat-bar{padding:12px 22px;background:#fff;border-bottom:1px solid #F1F5F9;display:flex;gap:6px;overflow-x:auto;flex-shrink:0;}
.cat-pill{padding:6px 16px;border-radius:99px;border:1.5px solid #E2E8F0;background:#fff;font-size:12px;font-weight:600;color:#64748B;cursor:pointer;transition:all .15s;white-space:nowrap;}
.cat-pill:hover{border-color:#6366F1;color:#6366F1;}
.cat-pill.active{background:linear-gradient(135deg,#6366F1,#8B5CF6);color:#fff;border-color:transparent;box-shadow:0 3px 10px rgba(99,102,241,.3);}

/* ──── Service grid ──── */
.svc-grid{flex:1;overflow-y:auto;padding:18px 20px;display:grid;grid-template-columns:repeat(3,1fr);gap:12px;align-content:start;}
@media(min-width:1280px){.svc-grid{grid-template-columns:repeat(4,1fr);}}

.svc-card{background:#fff;border:1.5px solid #EEF0F6;border-radius:16px;padding:16px;cursor:pointer;transition:all .18s cubic-bezier(.4,0,.2,1);text-align:left;}
.svc-card:hover{border-color:#6366F1;box-shadow:0 0 0 3px rgba(99,102,241,.1),0 6px 20px rgba(99,102,241,.12);transform:translateY(-2px);}
.svc-card:active{transform:scale(.975);}

.svc-icon-wrap{width:38px;height:38px;border-radius:12px;display:flex;align-items:center;justify-content:center;margin-bottom:10px;}
.svc-cat-label{font-size:10px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;margin-bottom:4px;}
.svc-name{font-size:13px;font-weight:700;color:#1E293B;line-height:1.35;margin-bottom:8px;}
.svc-price{font-size:20px;font-weight:800;letter-spacing:-.5px;color:#0F172A;}

/* icon color themes */
.ic-wash{background:#EFF6FF;color:#3B82F6;}
.ic-dry{background:#FAF5FF;color:#9333EA;}
.ic-iron{background:#FFF7ED;color:#F97316;}
.cl-wash{color:#3B82F6;}
.cl-dry{color:#9333EA;}
.cl-iron{color:#F97316;}

/* ──── Cart panel ──── */
.cart-panel{
  width:340px;
  background:#fff;
  border-left:1px solid #E8EDF5;
  box-shadow:-4px 0 24px rgba(0,0,0,.06);
  display:flex;
  flex-direction:column;
  flex-shrink:0;
}
.cart-header{
  padding:20px 20px 16px;
  border-bottom:1px solid #F1F5F9;
  background: linear-gradient(160deg,#FAFAFF 0%,#fff 100%);
}
.cart-title-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;}
.cart-h1{font-size:17px;font-weight:800;color:#0F172A;letter-spacing:-.3px;}
.cart-badge{
  display:inline-flex;align-items:center;justify-content:center;
  min-width:22px;height:22px;padding:0 6px;
  border-radius:99px;background:linear-gradient(135deg,#6366F1,#8B5CF6);
  color:#fff;font-size:11px;font-weight:700;margin-left:8px;
}
.cart-icon-btn{width:32px;height:32px;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;border:none;background:transparent;color:#94A3B8;transition:all .15s;}
.cart-icon-btn:hover{background:#FEE2E2;color:#EF4444;}
.cart-icon-btn.close:hover{background:#F1F5F9;color:#475569;}

/* Customer section */
.cust-section{background:#F8F9FD;border-radius:14px;padding:12px;}
.cust-section-label{
  font-size:10px;font-weight:700;color:#6366F1;
  letter-spacing:.08em;text-transform:uppercase;margin-bottom:8px;
  display:flex;align-items:center;gap:5px;
}
.cust-row{display:flex;gap:6px;}
.cust-select{
  flex:1;font-size:13px;font-weight:500;padding:9px 12px;
  border-radius:12px;border:1.5px solid #E2E8F0;
  color:#1E293B;background:#fff;outline:none;transition:border-color .15s;
  box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.cust-select:focus{border-color:#6366F1;}
.cust-add-btn{
  width:40px;display:flex;align-items:center;justify-content:center;
  border-radius:12px;border:1.5px solid #E2E8F0;
  color:#6366F1;cursor:pointer;background:#fff;transition:all .15s;
  box-shadow:0 1px 4px rgba(0,0,0,.04);
}
.cust-add-btn:hover{border-color:#6366F1;background:#EEF2FF;}

/* Cart items */
.cart-items{flex:1;overflow-y:auto;padding:12px 16px;background:#F4F6FB;display:flex;flex-direction:column;gap:8px;}
.cart-empty{
  flex:1;display:flex;flex-direction:column;
  align-items:center;justify-content:center;text-align:center;padding:32px 16px;
}
.cart-empty-icon{
  width:64px;height:64px;border-radius:20px;background:#fff;
  border:2px dashed #E2E8F0;display:flex;align-items:center;
  justify-content:center;margin:0 auto 12px;color:#CBD5E1;
}
.cart-empty-title{font-size:14px;font-weight:700;color:#64748B;}
.cart-empty-sub{font-size:12px;color:#CBD5E1;margin-top:4px;}

/* Item card with left accent */
.c-item{
  background:#fff;
  border:1.5px solid #EEF0F6;
  border-left:3px solid #6366F1;
  border-radius:14px;
  padding:12px 14px;
  box-shadow:0 1px 6px rgba(0,0,0,.04);
}
.c-item-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;}
.c-item-name{font-size:13px;font-weight:700;color:#1E293B;flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;margin-right:8px;}
.c-item-price{
  font-size:11px;font-weight:600;color:#6366F1;
  background:#EEF2FF;padding:2px 7px;border-radius:6px;
  white-space:nowrap;
}
.c-subtotal{font-size:11px;color:#94A3B8;margin-top:2px;}

/* Qty control */
.qty-row{
  display:flex;align-items:center;
  border:1.5px solid #E2E8F0;border-radius:10px;
  overflow:hidden;background:#F8F9FD;flex-shrink:0;
}
.qty-btn{
  width:28px;height:28px;display:flex;align-items:center;
  justify-content:center;font-size:15px;font-weight:700;
  color:#64748B;cursor:pointer;border:none;background:transparent;transition:all .15s;
}
.qty-btn:hover{background:#EEF2FF;color:#6366F1;}
.qty-val{width:28px;text-align:center;font-size:13px;font-weight:700;color:#1E293B;}

/* Compact note */
.c-note-wrap{margin-top:6px;}
.c-note{
  width:100%;font-size:11px;padding:5px 10px;
  border-radius:8px;border:1.5px solid #EEF0F6;
  background:#F8FAFF;color:#64748B;font-style:italic;
  outline:none;transition:border-color .15s;box-sizing:border-box;
  resize:none;
}
.c-note:focus{border-color:#A5B4FC;background:#fff;}
.c-note::placeholder{color:#CBD5E1;}

/* Cart footer */
.cart-footer{
  padding:16px 20px 20px;
  background:#fff;
  border-top:1px solid #F1F5F9;
}
.cart-summary-box{
  background:#F8F9FD;border-radius:14px;padding:12px 14px;margin-bottom:14px;
}
.cart-row{display:flex;justify-content:space-between;align-items:center;font-size:12px;color:#94A3B8;margin-bottom:6px;}
.cart-row:last-child{margin-bottom:0;}
.cart-total-row{
  display:flex;justify-content:space-between;align-items:center;
  padding-top:10px;border-top:1px dashed #E2E8F0;margin-top:8px;
}
.cart-total-label{font-size:14px;font-weight:800;color:#1E293B;}
.cart-total-val{
  font-size:26px;font-weight:900;color:#6366F1;
  letter-spacing:-.5px;
}

/* Checkout button */
.btn-checkout{
  width:100%;padding:15px;
  border-radius:16px;
  background:linear-gradient(135deg,#6366F1 0%,#8B5CF6 100%);
  color:#fff;font-size:15px;font-weight:700;
  border:none;cursor:pointer;
  display:flex;align-items:center;justify-content:center;gap:10px;
  box-shadow:0 8px 24px rgba(99,102,241,.45);
  transition:all .18s cubic-bezier(.4,0,.2,1);
  letter-spacing:-.2px;
}
.btn-checkout:hover{
  box-shadow:0 12px 32px rgba(99,102,241,.55);
  transform:translateY(-2px);
}
.btn-checkout:active{transform:scale(.97);}
.btn-checkout:disabled{
  opacity:.35;cursor:not-allowed;
  transform:none!important;box-shadow:none!important;
  background:#CBD5E1;
}
.btn-checkout-icon{
  width:28px;height:28px;border-radius:8px;
  background:rgba(255,255,255,.2);
  display:flex;align-items:center;justify-content:center;
}

/* ──── Orders tab ──── */
.orders-page{flex:1;overflow-y:auto;padding:22px 24px;background:#F8F9FD;}
.orders-topbar{display:flex;align-items:center;justify-content:space-between;margin-bottom:18px;}
.orders-title{font-size:22px;font-weight:800;color:#0F172A;letter-spacing:-.4px;}
.orders-sub{font-size:12px;color:#94A3B8;margin-top:2px;}
.ofilter-wrap{display:flex;align-items:center;gap:8px;}
.ofilter-bar{display:flex;gap:4px;padding:4px;border-radius:14px;background:#fff;border:1.5px solid #E2E8F0;}
.of-pill{padding:5px 12px;border-radius:10px;font-size:11px;font-weight:600;color:#64748B;cursor:pointer;transition:all .15s;border:none;background:transparent;}
.of-pill:hover{color:#6366F1;}
.of-pill.active{background:linear-gradient(135deg,#6366F1,#8B5CF6);color:#fff;box-shadow:0 3px 8px rgba(99,102,241,.3);}
.refresh-btn{width:36px;height:36px;border-radius:10px;background:#fff;border:1.5px solid #E2E8F0;display:flex;align-items:center;justify-content:center;color:#94A3B8;cursor:pointer;transition:all .15s;}
.refresh-btn:hover{border-color:#6366F1;color:#6366F1;}

/* Stat cards */
.stats-row{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:18px;}
.stat-card{border-radius:16px;padding:18px 20px;background:#fff;border:1.5px solid #EEF0F6;}
.stat-num{font-size:30px;font-weight:900;letter-spacing:-.5px;}
.stat-label{font-size:10px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;margin-top:4px;}
.stat-pending{background:#FFFBEB;border-color:#FDE68A;} .stat-pending .stat-num{color:#D97706;} .stat-pending .stat-label{color:#F59E0B;}
.stat-proc{background:#EFF6FF;border-color:#BFDBFE;} .stat-proc .stat-num{color:#2563EB;} .stat-proc .stat-label{color:#3B82F6;}
.stat-ready{background:#ECFDF5;border-color:#A7F3D0;} .stat-ready .stat-num{color:#059669;} .stat-ready .stat-label{color:#10B981;}

/* Orders table */
.orders-table-wrap{background:#fff;border-radius:16px;border:1.5px solid #EEF0F6;overflow:hidden;box-shadow:0 1px 12px rgba(0,0,0,.04);}
.orders-table{width:100%;border-collapse:collapse;}
.orders-table thead{background:#F8F9FF;}
.orders-table th{text-align:left;font-size:10px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#94A3B8;padding:13px 18px;border-bottom:1.5px solid #EEF0F6;}
.orders-table tbody tr{transition:background .1s;border-bottom:1px solid #F1F5F9;}
.orders-table tbody tr:last-child{border-bottom:none;}
.orders-table tbody tr:hover{background:#F8F9FF;}
.orders-table td{padding:13px 18px;}
.o-num{font-size:13px;font-weight:800;color:#1E293B;}
.o-customer{display:flex;align-items:center;gap:8px;}
.o-avatar{width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#6366F1,#8B5CF6);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.o-name{font-size:13px;font-weight:600;color:#1E293B;}
.o-time{font-size:12px;color:#94A3B8;}
.o-total{font-size:13px;font-weight:800;color:#0F172A;}
.o-actions{display:flex;align-items:center;gap:6px;}
.o-badge{display:inline-flex;align-items:center;padding:3px 9px;border-radius:99px;font-size:11px;font-weight:700;}
.ob-pending{background:#FEF3C7;color:#B45309;}
.ob-proc{background:#DBEAFE;color:#1D4ED8;}
.ob-ready{background:#D1FAE5;color:#065F46;}
.ob-done{background:#F1F5F9;color:#64748B;}
.o-chip{font-size:11px;padding:3px 8px;border-radius:6px;font-weight:500;color:#475569;background:#F1F5F9;}
.act-btn{font-size:11px;font-weight:600;padding:5px 12px;border-radius:8px;border:none;cursor:pointer;transition:all .15s;}
.act-btn-start{background:#EFF6FF;color:#2563EB;} .act-btn-start:hover{background:#DBEAFE;}
.act-btn-ready{background:#ECFDF5;color:#059669;} .act-btn-ready:hover{background:#D1FAE5;}
.act-btn-done{background:#F1F5F9;color:#64748B;} .act-btn-done:hover{background:#E2E8F0;}
.act-print{width:28px;height:28px;border-radius:7px;background:#F8FAFF;border:none;color:#94A3B8;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .15s;}
.act-print:hover{background:#EEF2FF;color:#6366F1;}

/* ──── Modals ──── */
.modal-overlay{position:fixed;inset:0;background:rgba(15,23,42,.5);backdrop-filter:blur(6px);z-index:40;display:flex;align-items:center;justify-content:center;padding:20px;}
.modal-card{background:#fff;border-radius:22px;box-shadow:0 24px 64px rgba(0,0,0,.18);width:100%;position:relative;z-index:50;}
.modal-header{padding:22px 24px 16px;border-bottom:1px solid #F1F5F9;}
.modal-title{font-size:15px;font-weight:800;color:#0F172A;}
.modal-sub{font-size:12px;color:#94A3B8;margin-top:2px;}
.modal-body{padding:18px 24px;}
.modal-footer{padding:14px 24px 20px;border-top:1px solid #F1F5F9;display:flex;justify-content:flex-end;gap:10px;}
.m-label{display:block;font-size:10px;font-weight:700;letter-spacing:.07em;text-transform:uppercase;color:#94A3B8;margin-bottom:6px;}
.m-input{width:100%;font-size:13px;padding:10px 12px;border-radius:12px;border:1.5px solid #E2E8F0;color:#1E293B;background:#fff;outline:none;transition:border-color .15s;box-sizing:border-box;}
.m-input:focus{border-color:#6366F1;}
.btn-cancel{padding:9px 18px;border-radius:11px;border:1.5px solid #E2E8F0;background:#fff;font-size:13px;font-weight:600;color:#64748B;cursor:pointer;transition:all .15s;}
.btn-cancel:hover{background:#F8F9FD;}
.btn-ok{padding:9px 18px;border-radius:11px;border:none;background:linear-gradient(135deg,#6366F1,#8B5CF6);color:#fff;font-size:13px;font-weight:700;cursor:pointer;box-shadow:0 4px 12px rgba(99,102,241,.35);transition:all .15s;}
.btn-ok:hover{opacity:.9;}
.btn-ok:disabled{opacity:.35;cursor:not-allowed;}
.btn-ok-red{background:#EF4444;box-shadow:0 4px 12px rgba(239,68,68,.3);}
</style>
@endsection

@section('content')
<div x-data="pos()" class="pos-root">

  <!-- NAV RAIL -->
  <nav class="rail">
    <div class="rail-logo">
      <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
    </div>
    <button @click="tab='pos'" :class="tab==='pos'?'active':''" class="rail-btn" title="New Order">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
    </button>
    <button @click="tab='orders'" :class="tab==='orders'?'active':''" class="rail-btn" title="Orders">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
      <span x-show="pendingCount()>0" x-text="pendingCount()" class="rail-badge"></span>
    </button>
    <div class="rail-spacer"></div>
    <button @click="showSync=true" class="rail-btn" title="Offline Sync">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
      <span x-show="offlineQ.length" style="width:7px;height:7px;border-radius:50%;background:#F59E0B;position:absolute;top:6px;right:6px;border:2px solid #fff;"></span>
    </button>
    <button @click="toggleShift" :class="shift?'active':''" class="rail-btn" title="Shift">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
    </button>
  </nav>

  <!-- ════ NEW ORDER TAB ════ -->
  <template x-if="tab==='pos'">
    <div style="display:flex;flex:1;overflow:hidden;min-width:0;">
      <!-- Services area -->
      <div class="pos-main">
        <!-- Topbar -->
        <div class="pos-topbar">
          <div>
            <div class="pos-title">New Order</div>
            <div class="pos-subtitle" x-text="new Date().toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric'})"></div>
          </div>
          <div class="topbar-right">
            <div class="status-pill" :class="online?'sp-online':'sp-offline'">
              <span class="sp-dot" :class="online?'sp-dot-online':'sp-dot-offline'"></span>
              <span x-text="online?'Online':'Offline'"></span>
            </div>
            <div x-show="shift" class="status-pill sp-shift">● Shift Active</div>
            <button @click="cartOpen=!cartOpen" class="topbar-btn">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              <span x-text="cartOpen?'Hide Cart':'Cart'"></span>
              <span x-show="cart.length" x-text="cart.length" style="background:#6366F1;color:#fff;font-size:10px;font-weight:700;border-radius:99px;padding:1px 6px;"></span>
            </button>
          </div>
        </div>

        <!-- Category bar -->
        <div class="cat-bar">
          <template x-for="c in cats" :key="c.id">
            <button @click="cat=c.id" :class="cat===c.id?'active':''" class="cat-pill" x-text="c.name"></button>
          </template>
        </div>

        <!-- Service grid -->
        <div class="svc-grid">
          <template x-for="s in svcs" :key="s.id">
            <button @click="addItem(s)" class="svc-card">
              <div class="svc-icon-wrap" :class="'ic-'+s.cid">
                <!-- Wash icon -->
                <template x-if="s.cid==='wash'">
                  <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="cl-wash"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4h16v16H4zM4 9h16M9 9v11M6 4v2M18 4v2"/></svg>
                </template>
                <!-- Dry cleaning icon -->
                <template x-if="s.cid==='dry'">
                  <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="cl-dry"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4l-3 7h4l-1 9 10-12H13l3-9H7z"/></svg>
                </template>
                <!-- Iron icon -->
                <template x-if="s.cid==='iron'">
                  <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="cl-iron"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 17c0-1.1.9-2 2-2h14l2-4H5a2 2 0 00-2 2v4zm0 0v2h18v-2M8 15V9m4 6V9m4 6V9"/></svg>
                </template>
                <!-- All icon -->
                <template x-if="s.cid==='all'">
                  <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                </template>
              </div>
              <div class="svc-cat-label" :class="'cl-'+s.cid" x-text="s.cat"></div>
              <div class="svc-name" x-text="s.name"></div>
              <div class="svc-price" x-text="fmt(s.price)"></div>
            </button>
          </template>
        </div>
      </div>

      <!-- CART SIDEBAR -->
      <div x-show="cartOpen" class="cart-panel">
        <!-- Header -->
        <div class="cart-header">
          <div class="cart-title-row">
            <div style="display:flex;align-items:center;">
              <span class="cart-h1">Order Cart</span>
              <span class="cart-badge" x-text="cart.length"></span>
            </div>
            <div style="display:flex;gap:4px;">
              <button @click="clearCart" class="cart-icon-btn" title="Clear all">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
              <button @click="cartOpen=false" class="cart-icon-btn close" title="Close">
                <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
          </div>
          <!-- Customer section -->
          <div class="cust-section">
            <div class="cust-section-label">
              <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              Customer
            </div>
            <div class="cust-row">
              <select x-model="custId" class="cust-select">
                <option value="">Select customer…</option>
                <template x-for="c in custs" :key="c.id"><option :value="c.id" x-text="c.name+' · '+c.phone"></option></template>
              </select>
              <button @click="showCust=true" class="cust-add-btn" title="New customer">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Items -->
        <div class="cart-items">
          <template x-if="!cart.length">
            <div class="cart-empty">
              <div class="cart-empty-icon">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              </div>
              <div class="cart-empty-title">Cart is empty</div>
              <div class="cart-empty-sub">Tap a service to add it</div>
            </div>
          </template>
          <template x-for="item in cart" :key="item.cid">
            <div class="c-item">
              <div class="c-item-row">
                <div style="flex:1;min-width:0;">
                  <div class="c-item-name" x-text="item.name"></div>
                  <span class="c-item-price" x-text="fmt(item.price)+' each'"></span>
                </div>
                <div class="qty-row" style="margin-left:10px;">
                  <button class="qty-btn" @click="chQty(item.cid,-1)">−</button>
                  <span class="qty-val" x-text="item.qty"></span>
                  <button class="qty-btn" @click="chQty(item.cid,1)">+</button>
                </div>
              </div>
              <div x-show="item.qty>1" class="c-subtotal" x-text="item.qty+' × '+fmt(item.price)+' = '+fmt(item.price*item.qty)"></div>
              <div class="c-note-wrap">
                <input class="c-note" x-model="item.note" type="text" placeholder="+ Add note (e.g. heavy stain, no bleach)…">
              </div>
            </div>
          </template>
        </div>

        <!-- Footer -->
        <div class="cart-footer">
          <div class="cart-summary-box">
            <div class="cart-row">
              <span>Subtotal (<span x-text="cart.reduce((t,i)=>t+i.qty,0)"></span> items)</span>
              <span x-text="fmt(total())"></span>
            </div>
            <div class="cart-row">
              <span>Discount</span>
              <span style="color:#10B981;font-weight:600;">—$0.00</span>
            </div>
            <div class="cart-total-row">
              <span class="cart-total-label">Total</span>
              <span class="cart-total-val" x-text="fmt(total())"></span>
            </div>
          </div>
          <button @click="checkout" :disabled="!cart.length||!custId||!shift" class="btn-checkout">
            <span x-text="shift?'Process Order':'Register Closed'"></span>
            <div x-show="shift" class="btn-checkout-icon">
              <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </div>
          </button>
        </div>
      </div>
    </div>
  </template>

  <!-- ════ ORDERS TAB ════ -->
  <template x-if="tab==='orders'">
    <div class="orders-page">
      <div class="orders-topbar">
        <div>
          <div class="orders-title">Orders</div>
          <div class="orders-sub" x-text="new Date().toLocaleDateString('en-US',{weekday:'long',month:'long',day:'numeric'})"></div>
        </div>
        <div class="ofilter-wrap">
          <div class="ofilter-bar">
            <template x-for="f in [{id:'all',l:'All'},{id:'pending',l:'Pending'},{id:'processing',l:'Active'},{id:'ready',l:'Ready'},{id:'delivered',l:'Done'}]" :key="f.id">
              <button @click="oFilter=f.id" :class="oFilter===f.id?'active':''" class="of-pill" x-text="f.l"></button>
            </template>
          </div>
          <button @click="refresh" class="refresh-btn"><svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
        </div>
      </div>

      <div class="stats-row">
        <div class="stat-card"><div class="stat-num" style="color:#1E293B;" x-text="orders.length"></div><div class="stat-label" style="color:#94A3B8;">Total Today</div></div>
        <div class="stat-card stat-pending"><div class="stat-num" x-text="orders.filter(o=>o.status==='pending').length"></div><div class="stat-label">Pending</div></div>
        <div class="stat-card stat-proc"><div class="stat-num" x-text="orders.filter(o=>['processing','washing','drying'].includes(o.status)).length"></div><div class="stat-label">Processing</div></div>
        <div class="stat-card stat-ready"><div class="stat-num" x-text="orders.filter(o=>o.status==='ready').length"></div><div class="stat-label">Ready</div></div>
      </div>

      <div class="orders-table-wrap" x-show="filteredOrders.length">
        <table class="orders-table">
          <thead><tr><th>Order</th><th>Customer</th><th>Items</th><th>Time</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
            <template x-for="o in filteredOrders" :key="o.id">
              <tr>
                <td><span class="o-num" x-text="'#'+o.order_number"></span></td>
                <td>
                  <div class="o-customer">
                    <div class="o-avatar" x-text="o.customer_name.charAt(0)"></div>
                    <span class="o-name" x-text="o.customer_name"></span>
                  </div>
                </td>
                <td>
                  <div style="display:flex;flex-wrap:wrap;gap:4px;">
                    <template x-for="(it,i) in o.items.slice(0,2)" :key="i"><span class="o-chip" x-text="it.name+(it.quantity>1?' ×'+it.quantity:'')"></span></template>
                    <span x-show="o.items.length>2" class="o-chip" style="color:#94A3B8;" x-text="'+'+(o.items.length-2)+' more'"></span>
                  </div>
                </td>
                <td><span class="o-time" x-text="o.time"></span></td>
                <td><span class="o-total" x-text="fmt(o.total)"></span></td>
                <td>
                  <span class="o-badge" :class="{'ob-pending':o.status==='pending','ob-proc':o.status==='processing'||o.status==='washing'||o.status==='drying','ob-ready':o.status==='ready','ob-done':o.status==='delivered'}" x-text="o.status"></span>
                </td>
                <td>
                  <div class="o-actions">
                    <button x-show="o.status==='pending'" @click="setStatus(o.id,'processing')" class="act-btn act-btn-start">Start</button>
                    <button x-show="['processing','washing','drying'].includes(o.status)" @click="setStatus(o.id,'ready')" class="act-btn act-btn-ready">Ready ✓</button>
                    <button x-show="o.status==='ready'" @click="setStatus(o.id,'delivered')" class="act-btn act-btn-done">Delivered</button>
                    <span x-show="o.status==='delivered'" style="font-size:11px;color:#94A3B8;font-style:italic;">Done</span>
                    <button @click="print(o)" class="act-print"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg></button>
                  </div>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>

      <div x-show="!filteredOrders.length" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:80px 0;color:#94A3B8;">
        <div style="width:64px;height:64px;border-radius:20px;background:#fff;border:2px dashed #E2E8F0;display:flex;align-items:center;justify-content:center;margin-bottom:12px;"><svg width="28" height="28" fill="none" stroke="#CBD5E1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg></div>
        <div style="font-size:14px;font-weight:600;color:#64748B;">No orders found</div>
      </div>
    </div>
  </template>
</div>

{{-- MODALS --}}
<div x-show="showCust" class="modal-overlay" style="display:none;">
  <div @click.self="showCust=false" style="position:fixed;inset:0;"></div>
  <div class="modal-card" style="max-width:420px;">
    <div class="modal-header"><div class="modal-title">New Customer</div><div class="modal-sub">Add a customer for this order</div></div>
    <div class="modal-body" style="display:flex;flex-direction:column;gap:14px;">
      <div><label class="m-label">Full Name</label><input x-model="nc.name" type="text" class="m-input" placeholder="John Doe"></div>
      <div><label class="m-label">Phone</label><input x-model="nc.phone" type="text" class="m-input" placeholder="555-1234"></div>
    </div>
    <div class="modal-footer"><button @click="showCust=false" class="btn-cancel">Cancel</button><button @click="saveCust" class="btn-ok">Save Customer</button></div>
  </div>
</div>

<div x-show="showSync" class="modal-overlay" style="display:none;">
  <div @click.self="showSync=false" style="position:fixed;inset:0;"></div>
  <div class="modal-card" style="max-width:420px;">
    <div class="modal-header"><div class="modal-title">Offline Queue</div><div class="modal-sub" x-text="offlineQ.length+' pending order'+(offlineQ.length===1?'':'s')"></div></div>
    <div class="modal-body" style="max-height:220px;overflow-y:auto;">
      <template x-for="(o,i) in offlineQ" :key="i">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 0;border-bottom:1px solid #F1F5F9;">
          <div><div style="font-size:13px;font-weight:600;color:#1E293B;" x-text="fmt(o.paid_amount)"></div><div style="font-size:11px;color:#94A3B8;" x-text="o.items.length+' items'"></div></div>
          <span class="o-badge ob-pending">Pending</span>
        </div>
      </template>
      <template x-if="!offlineQ.length"><div style="text-align:center;padding:32px 0;font-size:13px;color:#94A3B8;">All synced ✓</div></template>
    </div>
    <div class="modal-footer" style="justify-content:space-between;">
      <div style="display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;" :style="online?'color:#059669':'color:#DC2626'">
        <span style="width:6px;height:6px;border-radius:50%;display:inline-block;" :style="online?'background:#10B981':'background:#EF4444'"></span>
        <span x-text="online?'Connected':'Offline'"></span>
      </div>
      <button @click="forceSync" :disabled="!online||!offlineQ.length" class="btn-ok">Force Sync</button>
    </div>
  </div>
</div>

<div x-show="showShiftM" class="modal-overlay" style="display:none;">
  <div @click.self="showShiftM=false" style="position:fixed;inset:0;"></div>
  <div class="modal-card" style="max-width:360px;">
    <div class="modal-header"><div class="modal-title" x-text="shift?'Close Register':'Open Register'"></div><div class="modal-sub">Enter the cash drawer amount</div></div>
    <div class="modal-body"><label class="m-label" x-text="shift?'Closing Amount':'Opening Amount'"></label><div style="position:relative;"><span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94A3B8;font-size:14px;font-weight:600;">$</span><input x-model="shiftAmt" type="number" step="0.01" placeholder="0.00" class="m-input" style="padding-left:28px;"></div></div>
    <div class="modal-footer"><button @click="showShiftM=false" class="btn-cancel">Cancel</button><button @click="confirmShift" :class="shift?'btn-ok btn-ok-red':'btn-ok'" x-text="shift?'Close Register':'Open Register'"></button></div>
  </div>
</div>

<script>
function pos(){
  return{
    online:navigator.onLine,tab:'pos',cartOpen:true,
    cats:[{id:'all',name:'All Services'},{id:'wash',name:'Wash & Fold'},{id:'dry',name:'Dry Cleaning'},{id:'iron',name:'Ironing'}],
    cat:'all',cart:[],custId:'',custs:[],nc:{name:'',phone:''},
    orders:[],oFilter:'all',
    showCust:false,showSync:false,showShiftM:false,
    shift:localStorage.getItem('pos_shift')==='true',shiftAmt:'',offlineQ:[],

    get svcs(){
      const all=[
        {id:1,cid:'wash',cat:'Wash & Fold', name:'Standard Wash',  price:15},
        {id:2,cid:'dry', cat:'Dry Cleaning',name:'Suit / Tuxedo',  price:25},
        {id:3,cid:'iron',cat:'Ironing',     name:'Shirt Ironing',  price:8},
        {id:4,cid:'wash',cat:'Wash & Fold', name:'Heavy Blankets', price:30},
        {id:5,cid:'dry', cat:'Dry Cleaning',name:'Dress / Skirt',  price:18},
        {id:6,cid:'iron',cat:'Ironing',     name:'Trousers',       price:10},
        {id:7,cid:'wash',cat:'Wash & Fold', name:'Shirt Pack ×5',  price:22},
        {id:8,cid:'dry', cat:'Dry Cleaning',name:'Coat / Jacket',  price:32},
        {id:9,cid:'iron',cat:'Ironing',     name:'Saree / Gown',   price:14},
      ];
      return this.cat==='all'?all:all.filter(s=>s.cid===this.cat);
    },
    get filteredOrders(){
      if(this.oFilter==='all') return this.orders;
      if(this.oFilter==='processing') return this.orders.filter(o=>['processing','washing','drying'].includes(o.status));
      return this.orders.filter(o=>o.status===this.oFilter);
    },
    pendingCount(){return this.orders.filter(o=>o.status!=='delivered').length;},

    init(){
      this.offlineQ=JSON.parse(localStorage.getItem('offline_orders')||'[]');
      this.custs=[{id:1,name:'John Doe',phone:'123-456-7890'},{id:2,name:'Jane Smith',phone:'098-765-4321'}];
      this.orders=[
        {id:1,order_number:'1001',customer_name:'John Doe',  status:'pending',   time:'9:15 AM', total:38,items:[{name:'Standard Wash',quantity:1},{name:'Shirt Ironing',quantity:2}]},
        {id:2,order_number:'1002',customer_name:'Jane Smith',status:'processing',time:'9:42 AM', total:25,items:[{name:'Suit / Tuxedo',quantity:1}]},
        {id:3,order_number:'1003',customer_name:'John Doe',  status:'ready',     time:'10:05 AM',total:30,items:[{name:'Heavy Blankets',quantity:1}]},
        {id:4,order_number:'1004',customer_name:'Jane Smith',status:'delivered', time:'10:30 AM',total:18,items:[{name:'Dress / Skirt',quantity:1}]},
      ];
      window.addEventListener('online',()=>this.online=true);
      window.addEventListener('offline',()=>this.online=false);
    },

    addItem(s){const id=Date.now()+Math.random().toString(36).substr(2,4);this.cart.push({cid:id,sid:s.id,name:s.name,price:s.price,qty:1,note:''});this.cartOpen=true;},
    chQty(cid,d){const i=this.cart.find(x=>x.cid===cid);if(!i)return;i.qty+=d;if(i.qty<=0)this.cart=this.cart.filter(x=>x.cid!==cid);},
    clearCart(){if(confirm('Clear order?'))this.cart=[];},
    total(){return this.cart.reduce((t,i)=>t+i.price*i.qty,0);},

    checkout(){
      if(!this.online){alert('Offline – saved.');this.cart=[];this.custId='';return;}
      const c=this.custs.find(x=>x.id==this.custId);
      this.orders.unshift({id:Date.now(),order_number:String(1000+this.orders.length+1),customer_name:c?c.name:'Guest',status:'pending',time:new Date().toLocaleTimeString('en-US',{hour:'numeric',minute:'2-digit'}),total:this.total(),items:this.cart.map(i=>({name:i.name,quantity:i.qty}))});
      this.cart=[];this.custId='';
    },
    setStatus(id,s){const o=this.orders.find(x=>x.id===id);if(o)o.status=s;},
    print(o){alert('Printing #'+o.order_number);},
    refresh(){console.log('Refresh…');},
    saveCust(){const id=this.custs.length+10;this.custs.push({id,name:this.nc.name,phone:this.nc.phone});this.custId=id;this.showCust=false;this.nc={name:'',phone:''};},
    toggleShift(){this.shiftAmt='';this.showShiftM=true;},
    confirmShift(){this.shift=!this.shift;localStorage.setItem('pos_shift',String(this.shift));this.showShiftM=false;},
    forceSync(){localStorage.setItem('offline_orders','[]');this.offlineQ=[];alert('Synced!');},
    fmt(v){return new Intl.NumberFormat('en-US',{style:'currency',currency:'USD'}).format(v);},
  };
}
</script>
@endsection
