{{-- Orders Page --}}
<div class="filter-bar">
    <div class="search-wrap">
        <svg width="15" height="15" fill="none" stroke="#94A3B8" viewBox="0 0 24 24">
            <circle cx="11" cy="11" r="8" />
            <path stroke-linecap="round" stroke-width="2" d="m21 21-4.35-4.35" />
        </svg>
        <input class="search-input" x-model="orderSearch" placeholder="Search order, customer, phone…" type="search">
    </div>
    <div style="display:flex;gap:6px;flex-wrap:wrap;">
        <template x-for="f in ['all','pending','processing','ready','out_for_delivery','delivered','cancelled']"
            :key="f">
            <button @click="orderFilter=f" :class="orderFilter === f ? 'btn btn-primary btn-sm' : 'btn btn-secondary btn-sm'"
                x-text="f==='all'?'All':f"></button>
        </template>
    </div>
    <div style="display:flex;gap:6px;margin-left:auto;">
        <select class="form-select" x-model="orderPayFilter" style="height:34px;min-width:130px;">
            <option value="all">All Payments</option>
            <option value="paid">Paid</option>
            <option value="partial">Partial</option>
            <option value="unpaid">Unpaid</option>
        </select>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div>
            <div class="card-title">Orders</div>
            <div class="card-sub"><span x-text="filteredOrders.length"></span> results</div>
        </div>
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Services</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Total</th>
                <th>Due</th>
                <th>Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <template x-if="filteredOrders.length===0">
                <tr>
                    <td colspan="9" style="text-align:center;padding:40px;color:#94A3B8;">No orders found</td>
                </tr>
            </template>
            <template x-for="o in filteredOrders" :key="o.id">
                <tr>
                    <td>
                        <span style="font-size:13px;font-weight:700;color:#1E293B;"
                            x-text="'#'+o.number.split('-').pop()"></span>
                        <div style="font-size:11px;color:#94A3B8;" x-text="o.number"></div>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:600;" x-text="o.customer"></div>
                        <div style="font-size:11px;color:#94A3B8;" x-text="o.phone||''"></div>
                    </td>
                    <td style="font-size:12px;color:#64748B;" x-text="o.services?.join(', ')"></td>
                    <td><span :class="badge(o.status)" x-text="o.status"></span></td>
                    <td><span :class="badge(o.payment_status)" x-text="o.payment_status"></span></td>
                    <td style="font-weight:700;" x-text="fmt(o.total)"></td>
                    <td style="color:#EF4444;font-weight:600;" x-text="o.due>0?fmt(o.due):'-'"></td>
                    <td style="color:#94A3B8;font-size:12px;" x-text="o.date"></td>
                    <td>
                        <button @click="openOrder(o)" class="btn btn-secondary btn-sm">View</button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
    <div
        style="padding:14px 20px;border-top:1px solid #EEF0F6;display:flex;justify-content:space-between;align-items:center;">
        <span style="font-size:13px;color:#64748B;"><span x-text="filteredOrders.length"></span> orders</span>
        <div style="display:flex;gap:6px;">
            <button class="btn btn-secondary btn-sm" @click="orderPage>1&&orderPage--" :disabled="orderPage <= 1">‹
                Prev</button>
            <span style="font-size:13px;color:#64748B;line-height:30px;padding:0 8px;">Page <span
                    x-text="orderPage"></span></span>
            <button class="btn btn-secondary btn-sm" @click="orderPage++">Next ›</button>
        </div>
    </div>
</div>

{{-- Order Detail Modal --}}
<div x-show="showOrderDetail" class="modal-bg" style="display:none;">
    <div @click.self="showOrderDetail=false" style="position:fixed;inset:0;"></div>
    <div class="modal" style="max-width:520px;">
        <div class="modal-hdr" style="display:flex;justify-content:space-between;align-items:start;">
            <div>
                <div class="modal-title" x-text="'Order '+selectedOrder?.number"></div>
                <div class="modal-sub" x-text="selectedOrder?.date"></div>
            </div>
            <button @click="showOrderDetail=false" style="background:none;border:none;cursor:pointer;color:#94A3B8;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-bdy" x-show="selectedOrder">
            <div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap;">
                <span :class="badge(selectedOrder?.status)" x-text="selectedOrder?.status"></span>
                <span :class="badge(selectedOrder?.payment_status)" x-text="selectedOrder?.payment_status"></span>
            </div>
            <div style="background:#F8F9FD;border-radius:12px;padding:16px;margin-bottom:16px;">
                <div
                    style="font-size:12px;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.07em;margin-bottom:8px;">
                    Customer</div>
                <div style="font-size:15px;font-weight:600;color:#0F172A;" x-text="selectedOrder?.customer"></div>
                <div style="font-size:13px;color:#64748B;" x-text="selectedOrder?.phone||''"></div>
            </div>
            <div style="background:#F8F9FD;border-radius:12px;padding:16px;margin-bottom:16px;">
                <div
                    style="font-size:12px;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.07em;margin-bottom:8px;">
                    Summary</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;">
                    <div>
                        <div style="font-size:11px;color:#94A3B8;">Total</div>
                        <div style="font-size:18px;font-weight:800;color:#0F172A;"
                            x-text="fmt(selectedOrder?.total||0)"></div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:#94A3B8;">Paid</div>
                        <div style="font-size:18px;font-weight:800;color:#10B981;"
                            x-text="fmt(selectedOrder?.paid||0)"></div>
                    </div>
                    <div>
                        <div style="font-size:11px;color:#94A3B8;">Due</div>
                        <div style="font-size:18px;font-weight:800;color:#EF4444;"
                            x-text="fmt(selectedOrder?.due||0)"></div>
                    </div>
                </div>
            </div>
            <div>
                <div
                    style="font-size:12px;font-weight:700;color:#64748B;text-transform:uppercase;letter-spacing:.07em;margin-bottom:10px;">
                    Update Status</div>
                <div style="display:flex;flex-wrap:wrap;gap:6px;">
                    <template x-for="s in ['pending','processing','ready','out_for_delivery','delivered','cancelled']"
                        :key="s">
                        <button @click="setOrderStatus(selectedOrder?.id,s)"
                            :class="selectedOrder?.status === s ? 'btn btn-primary btn-sm' : 'btn btn-secondary btn-sm'"
                            x-text="s"></button>
                    </template>
                </div>
            </div>
        </div>
        <div class="modal-ftr"><button @click="showOrderDetail=false" class="btn btn-secondary">Close</button></div>
    </div>
</div>
