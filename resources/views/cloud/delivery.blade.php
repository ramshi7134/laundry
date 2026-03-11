{{-- Delivery Page --}}
<div class="filter-bar">
  <div class="search-wrap">
    <svg width="15" height="15" fill="none" stroke="#94A3B8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-width="2" d="m21 21-4.35-4.35"/></svg>
    <input class="search-input" x-model="deliverySearch" placeholder="Search order, customer, staff…" type="search">
  </div>
  <div style="display:flex;gap:6px;flex-wrap:wrap;">
    <template x-for="f in ['all','assigned','picked_up','delivered','failed']" :key="f">
      <button @click="deliveryFilter=f" :class="deliveryFilter===f?'btn btn-primary btn-sm':'btn btn-secondary btn-sm'" x-text="f==='all'?'All':f.replace('_',' ')"></button>
    </template>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:20px;">
  <template x-for="[label,color,key] in [['Assigned','#6366F1','assigned'],['Picked Up','#F97316','picked_up'],['Delivered','#10B981','delivered'],['Failed','#EF4444','failed']]" :key="key">
    <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:16px 18px;">
      <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;" x-text="label"></div>
      <div style="font-size:26px;font-weight:800;margin-top:4px;" :style="'color:'+color" x-text="allDeliveries.filter(d=>d.status===key).length"></div>
    </div>
  </template>
</div>

<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">Delivery Assignments</div>
      <div class="card-sub"><span x-text="filteredDeliveries.length"></span> assignments</div>
    </div>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Order #</th>
        <th>Customer</th>
        <th>Staff</th>
        <th>Status</th>
        <th>Address</th>
        <th>Scheduled</th>
        <th>Delivered At</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <template x-if="filteredDeliveries.length===0">
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#94A3B8;">No deliveries found</td></tr>
      </template>
      <template x-for="d in filteredDeliveries" :key="d.id">
        <tr>
          <td style="font-size:13px;font-weight:700;color:#1E293B;" x-text="'#'+(d.order_number||d.order_id)"></td>
          <td>
            <div style="font-size:13px;font-weight:600;" x-text="d.customer_name||d.customer||'—'"></div>
            <div style="font-size:11px;color:#94A3B8;" x-text="d.customer_phone||''"></div>
          </td>
          <td style="font-size:13px;" x-text="d.staff_name||'—'"></td>
          <td>
            <span :class="badge(d.status)" x-text="d.status.replace(/_/g,' ')"></span>
          </td>
          <td style="font-size:12px;color:#64748B;max-width:160px;" x-text="d.address||'—'"></td>
          <td style="font-size:12px;color:#64748B;" x-text="d.scheduled_at||'—'"></td>
          <td style="font-size:12px;color:#10B981;" x-text="d.delivered_at||'—'"></td>
          <td>
            <div style="display:flex;gap:4px;">
              <template x-if="d.status==='assigned'">
                <button @click="updateDelivery(d.id,'picked_up')" class="btn btn-secondary btn-sm">Pick Up</button>
              </template>
              <template x-if="d.status==='picked_up'">
                <button @click="updateDelivery(d.id,'delivered')" class="btn btn-primary btn-sm">Deliver</button>
              </template>
              <template x-if="d.status==='delivered'">
                <span style="font-size:12px;color:#10B981;font-weight:600;">✓ Done</span>
              </template>
            </div>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</div>
