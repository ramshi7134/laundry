{{-- Expenses Page --}}
<div class="filter-bar">
  <div style="display:flex;gap:6px;flex-wrap:wrap;">
    <template x-for="cat in ['all','rent','utilities','salaries','supplies','maintenance','marketing','other']" :key="cat">
      <button @click="expenseFilter=cat" :class="expenseFilter===cat?'btn btn-primary btn-sm':'btn btn-secondary btn-sm'" x-text="cat.charAt(0).toUpperCase()+cat.slice(1)"></button>
    </template>
  </div>
  <button @click="openAddExpense()" class="btn btn-primary btn-sm" style="margin-left:auto;">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Add Expense
  </button>
</div>

<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px;">
  <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:16px 18px;">
    <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;">This Month</div>
    <div style="font-size:26px;font-weight:800;color:#EF4444;margin-top:4px;" x-text="fmt(allExpenses.reduce((s,e)=>s+(+e.amount||0),0))"></div>
  </div>
  <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:16px 18px;">
    <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;">Today</div>
    <div style="font-size:26px;font-weight:800;color:#F97316;margin-top:4px;" x-text="fmt(allExpenses.filter(e=>e.date===new Date().toISOString().slice(0,10)).reduce((s,e)=>s+(+e.amount||0),0))"></div>
  </div>
  <div style="background:#fff;border-radius:14px;border:1px solid #EEF0F6;padding:16px 18px;">
    <div style="font-size:11px;font-weight:700;color:#94A3B8;text-transform:uppercase;letter-spacing:.07em;">Records</div>
    <div style="font-size:26px;font-weight:800;color:#6366F1;margin-top:4px;" x-text="filteredExpenses.length"></div>
  </div>
</div>

<div class="card">
  <div class="card-header">
    <div>
      <div class="card-title">Expenses</div>
      <div class="card-sub"><span x-text="filteredExpenses.length"></span> records</div>
    </div>
  </div>
  <table class="data-table">
    <thead>
      <tr>
        <th>Date</th>
        <th>Category</th>
        <th>Description</th>
        <th>Amount</th>
        <th>Recorded By</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <template x-if="filteredExpenses.length===0">
        <tr><td colspan="6" style="text-align:center;padding:40px;color:#94A3B8;">No expenses found</td></tr>
      </template>
      <template x-for="e in filteredExpenses" :key="e.id">
        <tr>
          <td style="font-size:13px;color:#64748B;" x-text="e.date"></td>
          <td>
            <span style="border-radius:99px;padding:3px 10px;font-size:11px;font-weight:700;background:#EEF2FF;color:#6366F1;" x-text="e.category"></span>
          </td>
          <td style="font-size:13px;color:#0F172A;" x-text="e.description||'—'"></td>
          <td style="font-size:14px;font-weight:700;color:#EF4444;" x-text="fmt(e.amount)"></td>
          <td style="font-size:12px;color:#94A3B8;" x-text="e.user||'—'"></td>
          <td>
            <button @click="deleteExpense(e.id)" class="btn btn-danger btn-sm">Delete</button>
          </td>
        </tr>
      </template>
    </tbody>
  </table>
</div>

{{-- Add Expense Modal --}}
<div x-show="showAddExpense" class="modal-bg" style="display:none;">
  <div @click.self="showAddExpense=false" style="position:fixed;inset:0;"></div>
  <div class="modal" style="max-width:420px;">
    <div class="modal-hdr">
      <div class="modal-title">Add Expense</div>
      <div class="modal-sub">Record a new expense entry</div>
    </div>
    <div class="modal-bdy">
      <div class="form-group">
        <label class="form-label">Date</label>
        <input class="form-input" type="date" x-model="newExpense.date">
      </div>
      <div class="form-group">
        <label class="form-label">Category</label>
        <select class="form-select" x-model="newExpense.category">
          <option value="">Select category…</option>
          <template x-for="cat in ['rent','utilities','salaries','supplies','maintenance','marketing','other']" :key="cat">
            <option :value="cat" x-text="cat.charAt(0).toUpperCase()+cat.slice(1)"></option>
          </template>
        </select>
      </div>
      <div class="form-group">
        <label class="form-label">Amount</label>
        <input class="form-input" type="number" min="0" step="0.01" x-model="newExpense.amount" placeholder="0.00">
      </div>
      <div class="form-group">
        <label class="form-label">Description</label>
        <textarea class="form-input" rows="2" x-model="newExpense.description" placeholder="Optional notes…" style="resize:vertical;"></textarea>
      </div>
    </div>
    <div class="modal-ftr">
      <button @click="showAddExpense=false" class="btn btn-secondary">Cancel</button>
      <button @click="saveExpense()" class="btn btn-primary">Save Expense</button>
    </div>
  </div>
</div>
