{{-- Inventory Page --}}
<div class="filter-bar">
  <div class="search-wrap">
    <svg width="15" height="15" fill="none" stroke="#94A3B8" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-width="2" d="m21 21-4.35-4.35"/></svg>
    <input class="search-input" x-model="inventorySearch" placeholder="Search item name, SKU…" type="search">
  </div>
  <div style="display:flex;gap:6px;">
    <button @click="inventoryFilter='all'" :class="inventoryFilter==='all'?'btn btn-primary btn-sm':'btn btn-secondary btn-sm'">All Items</button>
    <button @click="inventoryFilter='low'" :class="inventoryFilter==='low'?'btn btn-primary btn-sm':'btn btn-secondary btn-sm'">
      ⚠ Low Stock
      <span style="background:#FEF3C7;color:#D97706;border-radius:99px;padding:0 6px;margin-left:4px;font-size:11px;" x-text="allInventory.filter(i=>isLow(i)).length"></span>
    </button>
  </div>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;">
  <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:16px 18px;">
    <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;">Total Items</div>
    <div style="font-size:26px;font-weight:800;color:#6366F1;margin-top:4px;" x-text="allInventory.length"></div>
  </div>
  <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:16px 18px;">
    <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;">Low Stock Alerts</div>
    <div style="font-size:26px;font-weight:800;color:#F59E0B;margin-top:4px;" x-text="allInventory.filter(i=>isLow(i)).length"></div>
  </div>
  <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:16px 18px;">
    <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;">Out of Stock</div>
    <div style="font-size:26px;font-weight:800;color:#EF4444;margin-top:4px;" x-text="allInventory.filter(i=>i.quantity<=0).length"></div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">Inventory Items</div>
      <div class="card-sub"><span x-text="filteredInventory.length"></span> items</div>
    </div>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Item</th>
        <th>SKU</th>
        <th>Category</th>
        <th>Quantity</th>
        <th>Min Qty</th>
        <th>Unit</th>
        <th>Unit Cost</th>
        <th>Status</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <template x-if="filteredInventory.length===0">
        <tr><td colspan="9" style="text-align:center;padding:40px;color:#94A3B8;">No items found</td></tr>
      </template>
      <template x-for="item in filteredInventory" :key="item.id">
        <tr :style="isLow(item)?'background:#FFFBEB;':''">
          <td>
            <div style="font-size:13px;font-weight:600;color:#0F172A;" x-text="item.name"></div>
            <div style="font-size:11px;color:#94A3B8;" x-text="item.description||''"></div>
          </td>
          <td style="font-size:12px;font-family:monospace;color:#64748B;" x-text="item.sku||'—'"></td>
          <td style="font-size:12px;color:#64748B;" x-text="item.category||'—'"></td>
          <td>
            <span :style="isLow(item)?'color:#D97706;font-weight:800;font-size:15px;':'font-weight:700;font-size:15px;color:#0F172A;'" x-text="item.quantity"></span>
          </td>
          <td style="font-size:13px;color:#94A3B8;" x-text="item.min_quantity||0"></td>
          <td style="font-size:12px;color:#64748B;" x-text="item.unit||'pcs'"></td>
          <td style="font-size:13px;" x-text="item.unit_cost?fmt(item.unit_cost):'—'"></td>
          <td>
            <template x-if="item.quantity<=0">
              <span style="background:#FEE2E2;color:#EF4444;border-radius:99px;padding:3px 10px;font-size:11px;font-weight:700;">Out of Stock</span>
            </template>
            <template x-if="isLow(item)&&item.quantity>0">
              <span style="background:#FEF3C7;color:#D97706;border-radius:99px;padding:3px 10px;font-size:11px;font-weight:700;">Low Stock</span>
            </template>
            <template x-if="!isLow(item)&&item.quantity>0">
              <span style="background:#D1FAE5;color:#059669;border-radius:99px;padding:3px 10px;font-size:11px;font-weight:700;">In Stock</span>
            </template>
          </td>
          <td>
            <button @click="openAdjust(item)" class="btn btn-secondary btn-sm">Adjust</button>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</div>

{{-- Adjust Stock Modal --}}
<div x-show="showAdjustStock" class="modal-bg" style="display:none;">
  <div @click.self="showAdjustStock=false" style="position:fixed;inset:0;"></div>
  <div class="modal" style="max-width:420px;">
    <div class="modal-hdr">
      <div class="modal-title" x-text="'Adjust: '+(selectedItem?.name||'')"></div>
      <div class="modal-sub">
        Current quantity: <strong x-text="selectedItem?.quantity"></strong> <span x-text="selectedItem?.unit||'pcs'"></span>
      </div>
    </div>
    <div class="modal-bdy">
      <div class="form-group">
        <label class="form-label">Adjustment Type</label>
        <select class="form-select" x-model="adjustType">
          <option value="add">Add Stock</option>
          <option value="remove">Remove Stock</option>
          <option value="adjust">Set Exact Quantity</option>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label" x-text="adjustType==='adjust'?'New Quantity':'Quantity'"></label>
        <input class="form-input" type="number" min="0" step="1" x-model="adjustQty" placeholder="0">
      </div>
      <div class="form-group">
        <label class="form-label">Reason</label>
        <input class="form-input" type="text" x-model="adjustReason" placeholder="e.g. Restocked, Damaged, Used in service…">
      </div>
      <div style="background:#F8F9FD;border-radius:10px;padding:12px;font-size:13px;color:#64748B;">
        <template x-if="adjustType==='add'">
          New quantity: <strong style="color:#059669;" x-text="(selectedItem?.quantity||0)+(+adjustQty||0)"></strong>
        </template>
        <template x-if="adjustType==='remove'">
          New quantity: <strong style="color:#EF4444;" x-text="Math.max(0,(selectedItem?.quantity||0)-(+adjustQty||0))"></strong>
        </template>
        <template x-if="adjustType==='adjust'">
          New quantity: <strong style="color:#6366F1;" x-text="+adjustQty||0"></strong>
        </template>
      </div>
    </div>
    <div class="modal-ftr">
      <button @click="showAdjustStock=false" class="btn btn-secondary">Cancel</button>
      <button @click="confirmAdjust()" class="btn btn-primary">Confirm Adjustment</button>
    </div>
  </div>
</div>
