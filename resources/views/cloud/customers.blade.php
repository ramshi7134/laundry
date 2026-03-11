{{-- Customers Page --}}
<div class="filter-bar">
  <div class="search-wrap">
    <svg width="15" height="15" fill="none" stroke="#94A3B8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-width="2" d="m21 21-4.35-4.35"/></svg>
    <input class="search-input" x-model="customerSearch" placeholder="Search name, phone, email…" type="search">
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">Customers</div>
      <div class="card-sub"><span x-text="filteredCustomers.length"></span> customers</div>
    </div>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Customer</th>
        <th>Phone</th>
        <th>Email</th>
        <th>Orders</th>
        <th>Wallet</th>
        <th>Loyalty Pts</th>
        <th>Total Spent</th>
        <th>Joined</th>
      </tr>
    </thead>
    <tbody>
      <template x-if="filteredCustomers.length===0">
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#94A3B8;">No customers found</td></tr>
      </template>
      <template x-for="c in filteredCustomers" :key="c.id">
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px;">
              <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#6366F1,#8B5CF6);color:#fff;font-size:13px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;" x-text="c.name.charAt(0)"></div>
              <div>
                <div style="font-size:13px;font-weight:600;color:#0F172A;" x-text="c.name"></div>
                <div style="font-size:11px;color:#94A3B8;" x-text="'ID: '+c.id"></div>
              </div>
            </div>
          </td>
          <td style="font-size:13px;" x-text="c.phone||'—'"></td>
          <td style="font-size:12px;color:#64748B;" x-text="c.email||'—'"></td>
          <td>
            <span style="background:#EEF2FF;color:#6366F1;border-radius:99px;padding:2px 10px;font-size:12px;font-weight:700;" x-text="c.orders_count||0"></span>
          </td>
          <td>
            <span style="font-size:13px;font-weight:700;color:#10B981;" x-text="fmt(c.wallet_balance||0)"></span>
          </td>
          <td>
            <span style="font-size:13px;font-weight:700;color:#F59E0B;" x-text="fmtNum(c.loyalty_balance||0)+' pts'"></span>
          </td>
          <td style="font-weight:700;" x-text="fmt(c.total_spent||0)"></td>
          <td style="font-size:12px;color:#94A3B8;" x-text="c.joined||c.created_at||'—'"></td>
        </tr>
      </template>
    </tbody>
  </table>
</div>

{{-- Customer stats row --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;">
  <div class="card">
    <div class="card-header"><div class="card-title">Wallet Summary</div></div>
    <div class="card-body">
      <div style="font-size:28px;font-weight:800;color:#10B981;" x-text="fmt(allCustomers.reduce((s,c)=>s+(c.wallet_balance||0),0))"></div>
      <div style="font-size:13px;color:#64748B;margin-top:4px;">Total wallet balance across all customers</div>
    </div>
  </div>
  <div class="card">
    <div class="card-header"><div class="card-title">Loyalty Summary</div></div>
    <div class="card-body">
      <div style="font-size:28px;font-weight:800;color:#F59E0B;" x-text="fmtNum(allCustomers.reduce((s,c)=>s+(c.loyalty_balance||0),0))+' pts'"></div>
      <div style="font-size:13px;color:#64748B;margin-top:4px;">Total loyalty points outstanding</div>
    </div>
  </div>
  <div class="card">
    <div class="card-header"><div class="card-title">Active Customers</div></div>
    <div class="card-body">
      <div style="font-size:28px;font-weight:800;color:#6366F1;" x-text="allCustomers.filter(c=>c.orders_count>0).length"></div>
      <div style="font-size:13px;color:#64748B;margin-top:4px;">Customers with at least one order</div>
    </div>
  </div>
</div>
