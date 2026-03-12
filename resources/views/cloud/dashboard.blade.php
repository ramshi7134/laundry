{{-- Dashboard Page --}}
<div class="kpi-grid">
    <div class="kpi">
        <div class="kpi-icon" style="background:#EEF2FF;">
            <svg width="20" height="20" fill="none" stroke="#6366F1" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="kpi-val" x-text="fmt(kpi.revenue)"></div>
        <div class="kpi-label">Today's Revenue</div>
        <div class="kpi-trend trend-up">↑ <span x-text="kpi.revenueChange"></span> vs yesterday</div>
    </div>
    <div class="kpi">
        <div class="kpi-icon" style="background:#FFF7ED;">
            <svg width="20" height="20" fill="none" stroke="#F97316" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
        </div>
        <div class="kpi-val" x-text="kpi.orders"></div>
        <div class="kpi-label">Orders Today</div>
        <div class="kpi-trend trend-up">↑ <span x-text="kpi.ordersChange"></span> vs yesterday</div>
    </div>
    <div class="kpi">
        <div class="kpi-icon" style="background:#F0FDF4;">
            <svg width="20" height="20" fill="none" stroke="#22C55E" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        <div class="kpi-val" x-text="kpi.customers"></div>
        <div class="kpi-label">Total Customers</div>
        <div class="kpi-trend trend-up">↑ <span x-text="kpi.customersChange"></span> this month</div>
    </div>
    <div class="kpi">
        <div class="kpi-icon" style="background:#FDF4FF;">
            <svg width="20" height="20" fill="none" stroke="#A855F7" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
            </svg>
        </div>
        <div class="kpi-val" x-text="fmt(kpi.collected)"></div>
        <div class="kpi-label">Collected Today</div>
        <div class="kpi-trend trend-down">↓ <span x-text="kpi.collectedChange"></span> vs yesterday</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 340px;gap:20px;">
    {{-- Recent Orders --}}
    <div class="card">
        <div class="card-header">
            <div>
                <div class="card-title">Recent Orders</div>
                <div class="card-sub">Today's order activity</div>
            </div>
            <button @click="page='orders'" class="btn btn-secondary btn-sm">View All</button>
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Time</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="o in recentOrders" :key="o.id">
                    <tr>
                        <td><span style="font-size:13px;font-weight:700;color:#1E293B;"
                                x-text="'#'+o.number.split('-').pop()"></span></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#6366F1,#8B5CF6);color:#fff;font-size:11px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;"
                                    x-text="o.customer.charAt(0)"></div>
                                <span style="font-size:13px;font-weight:500;" x-text="o.customer"></span>
                            </div>
                        </td>
                        <td><span :class="badge(o.status)" x-text="o.status"></span></td>
                        <td style="font-weight:700;" x-text="fmt(o.total)"></td>
                        <td style="color:#94A3B8;font-size:12px;" x-text="o.time"></td>
                        <td>
                            <button @click="openOrder(o)" class="btn btn-secondary btn-sm">View</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Right column --}}
    <div style="display:flex;flex-direction:column;gap:20px;">
        {{-- Order status summary --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Order Status</div>
            </div>
            <div class="card-body">
                <template x-for="[status,count] in Object.entries(dailyData.orders_by_status)" :key="status">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span :class="badge(status)" x-text="status"></span>
                        </div>
                        <div style="display:flex;align-items:center;gap:10px;">
                            <div style="width:100px;height:6px;background:#F1F5F9;border-radius:99px;overflow:hidden;">
                                <div style="height:100%;background:linear-gradient(90deg,#6366F1,#8B5CF6);border-radius:99px;"
                                    :style="'width:' + Math.round(count / dailyData.total_orders * 100) + '%'"></div>
                            </div>
                            <span style="font-size:13px;font-weight:700;color:#1E293B;width:20px;text-align:right;"
                                x-text="count"></span>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Top Services --}}
        <div class="card">
            <div class="card-header">
                <div class="card-title">Top Services</div>
            </div>
            <div class="card-body">
                <template x-for="(s,i) in topServices" :key="i">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                        <div>
                            <div style="font-size:13px;font-weight:600;color:#1E293B;" x-text="s.name"></div>
                            <div style="font-size:11px;color:#94A3B8;" x-text="s.qty+' orders'"></div>
                        </div>
                        <div style="font-size:13px;font-weight:700;color:#6366F1;" x-text="fmt(s.revenue)"></div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

{{-- Order Detail Modal --}}
<div x-show="showOrderDetail" class="modal-bg" style="display:none;">
    <div @click.self="showOrderDetail=false" style="position:fixed;inset:0;"></div>
    <div class="modal" style="max-width:480px;">
        <div class="modal-hdr" style="display:flex;justify-content:space-between;align-items:start;">
            <div>
                <div class="modal-title" x-text="'Order #'+(selectedOrder?.number?.split('-')?.pop() || '')"></div>
                <div class="modal-sub" x-text="selectedOrder?.customer"></div>
            </div>
            <button @click="showOrderDetail=false" style="background:none;border:none;cursor:pointer;color:#94A3B8;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-bdy" x-show="selectedOrder">
            <div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap;">
                <span :class="badge(selectedOrder?.status)" x-text="selectedOrder?.status"></span>
                <span :class="badge(selectedOrder?.payment_status)" x-text="selectedOrder?.payment_status"></span>
            </div>
            <div class="divider"></div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                <div>
                    <div
                        style="font-size:11px;color:#94A3B8;font-weight:600;text-transform:uppercase;letter-spacing:.07em;">
                        Total</div>
                    <div style="font-size:20px;font-weight:800;color:#0F172A;" x-text="fmt(selectedOrder?.total||0)">
                    </div>
                </div>
                <div>
                    <div
                        style="font-size:11px;color:#94A3B8;font-weight:600;text-transform:uppercase;letter-spacing:.07em;">
                        Paid</div>
                    <div style="font-size:20px;font-weight:800;color:#10B981;" x-text="fmt(selectedOrder?.paid||0)">
                    </div>
                </div>
            </div>
            <div class="divider"></div>
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
        <div class="modal-ftr"><button @click="showOrderDetail=false" class="btn btn-secondary">Close</button></div>
    </div>
</div>
