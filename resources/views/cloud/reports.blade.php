{{-- Reports Page --}}
<div class="filter-bar">
  <div style="display:flex;gap:6px;">
    <button @click="reportPeriod='daily'" :class="reportPeriod==='daily'?'btn btn-primary btn-sm':'btn btn-secondary btn-sm'">Daily</button>
    <button @click="reportPeriod='monthly'" :class="reportPeriod==='monthly'?'btn btn-primary btn-sm':'btn btn-secondary btn-sm'">Monthly</button>
  </div>
  <div style="display:flex;gap:8px;align-items:center;margin-left:auto;">
    <input class="form-input" type="date" x-model="reportDate" style="height:34px;font-size:13px;" :max="new Date().toISOString().slice(0,10)">
    <button @click="loadReport()" class="btn btn-primary btn-sm">Load Report</button>
  </div>
</div>

{{-- KPI Summary --}}
<div class="kpi-grid">
  <div class="kpi">
    <div class="kpi-icon" style="background:#EEF2FF;">
      <svg width="20" height="20" fill="none" stroke="#6366F1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
    </div>
    <div class="kpi-val" x-text="dailyData.total_orders||0"></div>
    <div class="kpi-label">Total Orders</div>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="background:#F0FDF4;">
      <svg width="20" height="20" fill="none" stroke="#22C55E" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/></svg>
    </div>
    <div class="kpi-val" x-text="fmt(dailyData.gross_revenue||0)"></div>
    <div class="kpi-label">Gross Revenue</div>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="background:#FFF7ED;">
      <svg width="20" height="20" fill="none" stroke="#F97316" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
    </div>
    <div class="kpi-val" x-text="fmt(dailyData.collected||0)"></div>
    <div class="kpi-label">Collected</div>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="background:#FDF4FF;">
      <svg width="20" height="20" fill="none" stroke="#A855F7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
    </div>
    <div class="kpi-val" x-text="fmt(dailyData.total_discounts||0)"></div>
    <div class="kpi-label">Discounts</div>
  </div>
  <div class="kpi">
    <div class="kpi-icon" style="background:#FEF2F2;">
      <svg width="20" height="20" fill="none" stroke="#EF4444" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
    </div>
    <div class="kpi-val" x-text="fmt(dailyData.total_expenses||0)"></div>
    <div class="kpi-label">Expenses</div>
  </div>
  <div class="kpi" style="border:2px solid #6366F1;">
    <div class="kpi-icon" style="background:#EEF2FF;">
      <svg width="20" height="20" fill="none" stroke="#6366F1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
    </div>
    <div class="kpi-val" style="color:#6366F1;" x-text="fmt(dailyData.net_profit||0)"></div>
    <div class="kpi-label">Net Profit</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
  {{-- Orders by Status --}}
  <div class="card">
    <div class="card-header"><div class="card-title">Orders by Status</div></div>
    <div class="card-body">
      <template x-if="!dailyData.orders_by_status||Object.keys(dailyData.orders_by_status).length===0">
        <div style="text-align:center;color:#94A3B8;padding:20px;">No data for this period</div>
      </template>
      <template x-for="[status,count] in Object.entries(dailyData.orders_by_status||{})" :key="status">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <span :class="badge(status)" x-text="status"></span>
          <div style="flex:1;margin:0 12px;height:8px;background:#F1F5F9;border-radius:99px;overflow:hidden;">
            <div style="height:100%;background:linear-gradient(90deg,#6366F1,#8B5CF6);border-radius:99px;" :style="'width:'+Math.round(count/Math.max(dailyData.total_orders,1)*100)+'%'"></div>
          </div>
          <span style="font-size:13px;font-weight:700;color:#1E293B;min-width:28px;text-align:right;" x-text="count"></span>
        </div>
      </template>
    </div>
  </div>

  {{-- Payments by Method --}}
  <div class="card">
    <div class="card-header"><div class="card-title">Payments by Method</div></div>
    <div class="card-body">
      <template x-if="!dailyData.payments_by_method||Object.keys(dailyData.payments_by_method).length===0">
        <div style="text-align:center;color:#94A3B8;padding:20px;">No data for this period</div>
      </template>
      <template x-for="[method,amount] in Object.entries(dailyData.payments_by_method||{})" :key="method">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
          <div style="display:flex;align-items:center;gap:8px;">
            <span style="width:8px;height:8px;border-radius:50%;background:linear-gradient(135deg,#6366F1,#8B5CF6);flex-shrink:0;"></span>
            <span style="font-size:13px;font-weight:500;color:#0F172A;text-transform:capitalize;" x-text="method.replace(/_/g,' ')"></span>
          </div>
          <span style="font-size:14px;font-weight:700;color:#0F172A;" x-text="fmt(amount)"></span>
        </div>
      </template>
    </div>
  </div>
</div>

{{-- Revenue by Service --}}
<div class="card">
  <div class="card-header">
    <div class="card-title">Revenue by Service</div>
    <div class="card-sub">Performance breakdown per service type</div>
  </div>
  <table class="data-table">
    <thead>
      <tr><th>#</th><th>Service</th><th>Orders</th><th>Revenue</th><th>Share</th></tr>
    </thead>
    <tbody>
      <template x-if="topServices.length===0">
        <tr><td colspan="5" style="text-align:center;padding:40px;color:#94A3B8;">No service data</td></tr>
      </template>
      <template x-for="(s,i) in topServices" :key="i">
        <tr>
          <td style="color:#94A3B8;font-size:13px;" x-text="i+1"></td>
          <td style="font-size:13px;font-weight:600;" x-text="s.name"></td>
          <td><span style="background:#EEF2FF;color:#6366F1;border-radius:99px;padding:2px 10px;font-size:12px;font-weight:700;" x-text="s.qty"></span></td>
          <td style="font-size:14px;font-weight:700;" x-text="fmt(s.revenue)"></td>
          <td style="font-size:13px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <div style="flex:1;height:6px;background:#F1F5F9;border-radius:99px;overflow:hidden;min-width:80px;">
                <div style="height:100%;background:linear-gradient(90deg,#6366F1,#8B5CF6);border-radius:99px;" :style="'width:'+Math.round(s.revenue/Math.max(topServices.reduce((a,b)=>a+(b.revenue||0),0),1)*100)+'%'"></div>
              </div>
              <span style="color:#94A3B8;font-size:11px;" x-text="Math.round(s.revenue/Math.max(topServices.reduce((a,b)=>a+(b.revenue||0),0),1)*100)+'%'"></span>
            </div>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</div>
