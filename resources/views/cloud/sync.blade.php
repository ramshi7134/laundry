{{-- Sync Page --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
  <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:18px 20px;">
    <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;">Pending</div>
    <div style="font-size:28px;font-weight:800;color:#F59E0B;margin-top:6px;" x-text="syncQueue.filter(s=>s.status==='pending').length"></div>
    <div style="font-size:12px;color:#94A3B8;margin-top:2px;">Waiting to sync</div>
  </div>
  <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:18px 20px;">
    <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;">Synced</div>
    <div style="font-size:28px;font-weight:800;color:#10B981;margin-top:6px;" x-text="syncQueue.filter(s=>s.status==='synced').length"></div>
    <div style="font-size:12px;color:#94A3B8;margin-top:2px;">Successfully pushed</div>
  </div>
  <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:18px 20px;">
    <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;">Failed</div>
    <div style="font-size:28px;font-weight:800;color:#EF4444;margin-top:6px;" x-text="syncQueue.filter(s=>s.status==='failed').length"></div>
    <div style="font-size:12px;color:#94A3B8;margin-top:2px;">Need retry</div>
  </div>
  <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:18px 20px;">
    <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;">Last Sync</div>
    <div style="font-size:14px;font-weight:700;color:#6366F1;margin-top:6px;" x-text="lastSyncTime||'Never'"></div>
    <div style="font-size:12px;color:#94A3B8;margin-top:2px;"></div>
  </div>
</div>

<div class="card" style="margin-bottom:20px;">
  <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
    <div>
      <div class="card-title">Sync Controls</div>
      <div class="card-sub">Manage data synchronisation between POS and cloud</div>
    </div>
    <div style="display:flex;gap:8px;">
      <button @click="retrySync()" class="btn btn-secondary" :disabled="syncQueue.filter(s=>s.status==='failed').length===0">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:5px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        Retry Failed
      </button>
      <button @click="doSync()" class="btn btn-primary">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:5px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
        Sync Now
      </button>
    </div>
  </div>
  <div style="padding:16px 20px;background:#F8F9FD;border-bottom:1px solid #EEF0F6;">
    <div style="display:flex;align-items:center;gap:10px;">
      <div :style="isOnline?'width:10px;height:10px;border-radius:50%;background:#10B981;':'width:10px;height:10px;border-radius:50%;background:#EF4444;'"></div>
      <span style="font-size:13px;font-weight:600;" x-text="isOnline?'Online — Connected to cloud':'Offline — Local mode'"></span>
      <span style="font-size:12px;color:#94A3B8;margin-left:auto;">Auto-sync: every 5 minutes when online</span>
    </div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">Sync Queue</div>
      <div class="card-sub"><span x-text="syncQueue.length"></span> total records</div>
    </div>
    <div style="display:flex;gap:6px;">
      <button @click="syncStatusFilter='all'" :class="syncStatusFilter==='all'?'btn btn-primary btn-sm':'btn btn-secondary btn-sm'">All</button>
      <button @click="syncStatusFilter='pending'" :class="syncStatusFilter==='pending'?'btn btn-primary btn-sm':'btn btn-secondary btn-sm'">Pending</button>
      <button @click="syncStatusFilter='failed'" :class="syncStatusFilter==='failed'?'btn btn-primary btn-sm':'btn btn-secondary btn-sm'">Failed</button>
      <button @click="syncStatusFilter='synced'" :class="syncStatusFilter==='synced'?'btn btn-primary btn-sm':'btn btn-secondary btn-sm'">Synced</button>
    </div>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Model</th>
        <th>Action</th>
        <th>Status</th>
        <th>Attempts</th>
        <th>Error</th>
        <th>Created</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <template x-if="filteredSyncQueue.length===0">
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#94A3B8;">No records in queue</td></tr>
      </template>
      <template x-for="s in filteredSyncQueue" :key="s.id">
        <tr>
          <td style="font-size:12px;font-family:monospace;color:#94A3B8;" x-text="s.id"></td>
          <td>
            <span style="background:#EEF2FF;color:#6366F1;border-radius:6px;padding:2px 8px;font-size:11px;font-weight:700;font-family:monospace;" x-text="s.model"></span>
          </td>
          <td>
            <span style="background:#F8F9FD;color:#64748B;border-radius:6px;padding:2px 8px;font-size:11px;font-weight:700;" x-text="s.action"></span>
          </td>
          <td>
            <template x-if="s.status==='pending'">
              <span style="background:#FEF3C7;color:#D97706;border-radius:99px;padding:3px 10px;font-size:11px;font-weight:700;">Pending</span>
            </template>
            <template x-if="s.status==='synced'">
              <span style="background:#D1FAE5;color:#059669;border-radius:99px;padding:3px 10px;font-size:11px;font-weight:700;">Synced</span>
            </template>
            <template x-if="s.status==='failed'">
              <span style="background:#FEE2E2;color:#EF4444;border-radius:99px;padding:3px 10px;font-size:11px;font-weight:700;">Failed</span>
            </template>
          </td>
          <td style="font-size:13px;text-align:center;" x-text="s.attempts||0"></td>
          <td style="font-size:11px;color:#EF4444;max-width:160px;overflow:hidden;text-overflow:ellipsis;" x-text="s.error||'—'"></td>
          <td style="font-size:12px;color:#94A3B8;" x-text="s.created_at||'—'"></td>
          <td>
            <template x-if="s.status==='failed'">
              <button @click="retrySingle(s.id)" class="btn btn-secondary btn-sm">Retry</button>
            </template>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</div>
