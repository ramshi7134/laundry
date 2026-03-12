{{-- Settings Page --}}
<form @submit.prevent="saveSettings()">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

        {{-- General Settings --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">General</div>
                    <div class="card-sub">Basic shop configuration</div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Shop Name</label>
                    <input class="form-input" type="text" x-model="appSettings.shop_name"
                        placeholder="My Laundry Shop">
                </div>
                <div class="form-group">
                    <label class="form-label">Currency Symbol</label>
                    <input class="form-input" type="text" x-model="appSettings.currency" placeholder="$"
                        style="max-width:120px;">
                </div>
                <div class="form-group">
                    <label class="form-label">Tax Rate (%)</label>
                    <input class="form-input" type="number" min="0" max="100" step="0.01"
                        x-model="appSettings.tax_rate" placeholder="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Default Delivery Fee</label>
                    <input class="form-input" type="number" min="0" step="0.01"
                        x-model="appSettings.delivery_fee" placeholder="0.00">
                </div>
                <div class="form-group">
                    <label class="form-label">Shop Address</label>
                    <textarea class="form-input" rows="2" x-model="appSettings.address" placeholder="Full address…"
                        style="resize:vertical;"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Shop Phone</label>
                    <input class="form-input" type="text" x-model="appSettings.shop_phone"
                        placeholder="+1 234 567 8900">
                </div>
            </div>
        </div>

        {{-- Loyalty & Wallet Settings --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Loyalty & Wallet</div>
                    <div class="card-sub">Points and wallet configuration</div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Loyalty Earn Rate</label>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <input class="form-input" type="number" min="0" step="0.1"
                            x-model="appSettings.loyalty_rate" placeholder="10">
                        <span style="font-size:13px;color:#64748B;white-space:nowrap;">pts per <span
                                x-text="appSettings.currency||'$'"></span>1</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Loyalty Redeem Rate</label>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <input class="form-input" type="number" min="0" step="0.01"
                            x-model="appSettings.loyalty_redeem_rate" placeholder="100">
                        <span style="font-size:13px;color:#64748B;white-space:nowrap;">pts = <span
                                x-text="appSettings.currency||'$'"></span>1</span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:13px;font-weight:700;color:#0F172A;">Enable
                        Wallet</label>
                    <div style="display:flex;align-items:center;gap:12px;margin-top:6px;">
                        <div @click="appSettings.wallet_enabled=!appSettings.wallet_enabled"
                            :style="appSettings.wallet_enabled ?
                                'width:44px;height:24px;background:#6366F1;border-radius:99px;cursor:pointer;position:relative;transition:.2s;' :
                                'width:44px;height:24px;background:#CBD5E1;border-radius:99px;cursor:pointer;position:relative;transition:.2s;'">
                            <div
                                :style="appSettings.wallet_enabled ?
                                    'position:absolute;top:2px;right:2px;width:20px;height:20px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.2);' :
                                    'position:absolute;top:2px;left:2px;width:20px;height:20px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.2);'">
                            </div>
                        </div>
                        <span style="font-size:13px;color:#64748B;"
                            x-text="appSettings.wallet_enabled?'Enabled':'Disabled'"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:13px;font-weight:700;color:#0F172A;">Enable Loyalty
                        Points</label>
                    <div style="display:flex;align-items:center;gap:12px;margin-top:6px;">
                        <div @click="appSettings.loyalty_enabled=!appSettings.loyalty_enabled"
                            :style="appSettings.loyalty_enabled ?
                                'width:44px;height:24px;background:#6366F1;border-radius:99px;cursor:pointer;position:relative;transition:.2s;' :
                                'width:44px;height:24px;background:#CBD5E1;border-radius:99px;cursor:pointer;position:relative;transition:.2s;'">
                            <div
                                :style="appSettings.loyalty_enabled ?
                                    'position:absolute;top:2px;right:2px;width:20px;height:20px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.2);' :
                                    'position:absolute;top:2px;left:2px;width:20px;height:20px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.2);'">
                            </div>
                        </div>
                        <span style="font-size:13px;color:#64748B;"
                            x-text="appSettings.loyalty_enabled?'Enabled':'Disabled'"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sync & Notifications Settings --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Sync & Notifications</div>
                    <div class="card-sub">Connectivity and alerts</div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label" style="font-size:13px;font-weight:700;color:#0F172A;">Auto Sync</label>
                    <div style="display:flex;align-items:center;gap:12px;margin-top:6px;">
                        <div @click="appSettings.auto_sync=!appSettings.auto_sync"
                            :style="appSettings.auto_sync ?
                                'width:44px;height:24px;background:#6366F1;border-radius:99px;cursor:pointer;position:relative;transition:.2s;' :
                                'width:44px;height:24px;background:#CBD5E1;border-radius:99px;cursor:pointer;position:relative;transition:.2s;'">
                            <div
                                :style="appSettings.auto_sync ?
                                    'position:absolute;top:2px;right:2px;width:20px;height:20px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.2);' :
                                    'position:absolute;top:2px;left:2px;width:20px;height:20px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.2);'">
                            </div>
                        </div>
                        <span style="font-size:13px;color:#64748B;"
                            x-text="appSettings.auto_sync?'Enabled — syncs every 5 minutes':'Disabled — manual sync only'"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label" style="font-size:13px;font-weight:700;color:#0F172A;">SMS
                        Notifications</label>
                    <div style="display:flex;align-items:center;gap:12px;margin-top:6px;">
                        <div @click="appSettings.sms_notifications=!appSettings.sms_notifications"
                            :style="appSettings.sms_notifications ?
                                'width:44px;height:24px;background:#6366F1;border-radius:99px;cursor:pointer;position:relative;transition:.2s;' :
                                'width:44px;height:24px;background:#CBD5E1;border-radius:99px;cursor:pointer;position:relative;transition:.2s;'">
                            <div
                                :style="appSettings.sms_notifications ?
                                    'position:absolute;top:2px;right:2px;width:20px;height:20px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.2);' :
                                    'position:absolute;top:2px;left:2px;width:20px;height:20px;background:#fff;border-radius:50%;transition:.2s;box-shadow:0 1px 3px rgba(0,0,0,.2);'">
                            </div>
                        </div>
                        <span style="font-size:13px;color:#64748B;"
                            x-text="appSettings.sms_notifications?'Enabled':'Disabled'"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Sync Interval (minutes)</label>
                    <input class="form-input" type="number" min="1" max="60"
                        x-model="appSettings.sync_interval" placeholder="5" style="max-width:120px;">
                </div>
            </div>
        </div>

        {{-- Appearance --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <div class="card-title">Appearance</div>
                    <div class="card-sub">Branding and display</div>
                </div>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Receipt Footer</label>
                    <textarea class="form-input" rows="3" x-model="appSettings.receipt_footer"
                        placeholder="Thank you for choosing us!" style="resize:vertical;"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Items per page (tables)</label>
                    <select class="form-select" x-model="appSettings.per_page">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date Format</label>
                    <select class="form-select" x-model="appSettings.date_format">
                        <option value="YYYY-MM-DD">YYYY-MM-DD</option>
                        <option value="DD/MM/YYYY">DD/MM/YYYY</option>
                        <option value="MM/DD/YYYY">MM/DD/YYYY</option>
                    </select>
                </div>
            </div>
        </div>

    </div>

    {{-- Save button --}}
    <div style="display:flex;justify-content:flex-end;gap:12px;">
        <button type="button" @click="loadSettings()" class="btn btn-secondary">Reset</button>
        <button type="submit" class="btn btn-primary" style="min-width:140px;">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                style="margin-right:5px;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Save Settings
        </button>
    </div>
</form>
