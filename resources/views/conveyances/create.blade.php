@php
  $oldRows = old('rows');
  $formRows = $oldRows ? json_decode($oldRows, true) : ($rows ?? []);
  $formRows = is_array($formRows) ? $formRows : [];
@endphp

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conveyance Bill — Create Entry</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <style>
      *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

      :root {
        --navy: #0f1c2e;
        --navy-mid: #1a2e48;
        --navy-light: #243d5c;
        --accent: #e8a020;
        --accent-light: #fdf3e0;
        --surface: #ffffff;
        --surface-off: #f6f7fa;
        --border: #e1e5ee;
        --border-strong: #c5cdd8;
        --text: #0f1c2e;
        --text-muted: #5c6b80;
        --text-faint: #9aaabb;
        --green: #1e7e4a;
        --green-light: #edfaf3;
        --red: #c0392b;
        --red-light: #fdf2f2;
      }

      body {
        font-family: 'DM Sans', sans-serif;
        background: #eef1f7;
        min-height: 100vh;
        padding: 24px 16px 80px;
        color: var(--text);
      }

      /* ── Page wrapper ── */
      .page-wrap { max-width: 860px; margin: 0 auto; }

      /* ── Page header ── */
      .page-header {
        background: var(--navy);
        border-radius: 10px 10px 0 0;
        padding: 22px 28px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
      }
      .page-header-left { display: flex; align-items: center; gap: 14px; }
      .header-icon {
        width: 40px; height: 40px;
        background: var(--accent);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
      }
      .header-icon svg { width: 20px; height: 20px; fill: var(--navy); }
      .page-header h1 {
        font-family: 'DM Serif Display', serif;
        font-size: 19px;
        font-weight: 400;
        color: #fff;
        line-height: 1.2;
      }
      .page-header h1 span { display: block; font-family: 'DM Sans', sans-serif; font-size: 12px; font-weight: 400; color: rgba(255,255,255,0.5); margin-top: 2px; letter-spacing: 0.5px; text-transform: uppercase; }
      .view-link {
        font-size: 12px;
        font-weight: 500;
        color: var(--accent);
        text-decoration: none;
        letter-spacing: 0.4px;
        text-transform: uppercase;
        border: 1px solid rgba(232,160,32,0.35);
        padding: 7px 14px;
        border-radius: 6px;
        white-space: nowrap;
        transition: background 0.15s;
      }
      .view-link:hover { background: rgba(232,160,32,0.12); }

      /* ── Main card ── */
      .card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-top: none;
        border-radius: 0 0 10px 10px;
        padding: 28px 28px 32px;
        box-shadow: 0 4px 24px rgba(15,28,46,0.07);
      }

      /* ── Alert bars ── */
      .alert {
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 13.5px;
        margin-bottom: 18px;
      }
      .alert-success { background: var(--green-light); border: 1px solid #a5dfc1; color: var(--green); }
      .alert-error   { background: var(--red-light);   border: 1px solid #f5b8b4; color: var(--red); }
      .alert ul { padding-left: 18px; margin-top: 4px; }

      /* ── Date field ── */
      .date-row { margin-bottom: 26px; display: flex; align-items: flex-end; gap: 20px; }
      .field-group { display: flex; flex-direction: column; gap: 6px; }
      label.flabel {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
      }
      input.finput, select.finput {
        border: 1.5px solid var(--border-strong);
        border-radius: 6px;
        padding: 9px 12px;
        font-family: 'DM Sans', sans-serif;
        font-size: 14.5px;
        color: var(--text);
        background: var(--surface-off);
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
      }
      input.finput:focus, select.finput:focus {
        border-color: var(--navy-light);
        box-shadow: 0 0 0 3px rgba(15,28,46,0.09);
        background: #fff;
      }

      /* ── Section label ── */
      .section-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: var(--text-faint);
        margin-bottom: 10px;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--border);
      }

      /* ── Desktop table ── */
      .table-wrap { overflow-x: auto; border-radius: 8px; border: 1px solid var(--border); }

      .entry-table { width: 100%; border-collapse: collapse; min-width: 520px; }
      .entry-table thead tr { background: var(--navy-mid); }
      .entry-table th {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.7px;
        color: rgba(255,255,255,0.75);
        padding: 11px 12px;
        text-align: center;
        border: none;
      }
      .entry-table th:first-child { text-align: center; }
      .entry-table th.left { text-align: left; }

      .entry-table tbody tr { border-bottom: 1px solid var(--border); transition: background 0.1s; }
      .entry-table tbody tr:last-child { border-bottom: none; }
      .entry-table tbody tr:hover { background: #f9fafb; }

      .entry-table td { padding: 5px 6px; vertical-align: middle; }
      .entry-table td.sl { text-align: center; font-size: 12px; font-weight: 600; color: var(--text-faint); width: 46px; }

      .entry-table input[type="text"],
      .entry-table input[type="number"] {
        width: 100%;
        border: 1.5px solid transparent;
        border-radius: 5px;
        padding: 8px 10px;
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        color: var(--text);
        background: transparent;
        outline: none;
        transition: border-color 0.15s, background 0.15s;
      }
      .entry-table input:focus {
        border-color: var(--navy-light);
        background: #fff;
        box-shadow: 0 0 0 3px rgba(15,28,46,0.07);
      }
      .entry-table input[type="number"] { text-align: right; }

      /* ── Mobile cards ── */
      .mobile-cards { display: none; }

      .mobile-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 14px;
        margin-bottom: 10px;
      }
      .mobile-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
      }
      .mobile-card-num {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-faint);
      }
      .mobile-grid { display: flex; flex-direction: column; gap: 8px; }
      .mobile-grid input {
        width: 100%;
        border: 1.5px solid var(--border-strong);
        border-radius: 6px;
        padding: 9px 11px;
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        color: var(--text);
        background: var(--surface-off);
        outline: none;
      }
      .mobile-grid input:focus { border-color: var(--navy-light); background: #fff; }

      /* ── Action buttons ── */
      .btn-add-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        margin-top: 12px;
        width: 100%;
        background: transparent;
        border: 1.5px dashed var(--border-strong);
        color: var(--text-muted);
        font-family: 'DM Sans', sans-serif;
        font-size: 13.5px;
        font-weight: 500;
        padding: 11px 18px;
        border-radius: 7px;
        cursor: pointer;
        transition: border-color 0.15s, color 0.15s, background 0.15s;
      }
      .btn-add-row:hover {
        border-color: var(--navy-light);
        color: var(--navy);
        background: var(--surface-off);
      }
      .btn-add-row svg { width: 15px; height: 15px; stroke: currentColor; fill: none; stroke-width: 2.5; }

      .btn-remove {
        display: flex; align-items: center; justify-content: center;
        background: transparent;
        border: 1px solid var(--border-strong);
        border-radius: 5px;
        width: 30px; height: 30px;
        cursor: pointer;
        transition: background 0.15s, border-color 0.15s;
        color: var(--text-faint);
      }
      .btn-remove:hover { background: var(--red-light); border-color: #f5b8b4; color: var(--red); }
      .btn-remove svg { width: 13px; height: 13px; stroke: currentColor; fill: none; stroke-width: 2.5; }

      /* ── Totals bar ── */
      .totals-bar {
        margin-top: 16px;
        background: var(--navy);
        border-radius: 8px;
        padding: 14px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }
      .totals-bar .tlabel {
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: rgba(255,255,255,0.5);
      }
      .totals-bar .tvalue {
        font-size: 20px;
        font-weight: 600;
        color: var(--accent);
        letter-spacing: 0.3px;
      }

      /* ── Footer actions ── */
      .form-actions { margin-top: 24px; display: flex; flex-wrap: wrap; gap: 10px; }

      .btn-submit {
        background: var(--green);
        color: #fff;
        border: none;
        border-radius: 7px;
        padding: 11px 24px;
        font-family: 'DM Sans', sans-serif;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.3px;
        cursor: pointer;
        display: flex; align-items: center; gap: 8px;
        transition: background 0.15s, transform 0.1s;
      }
      .btn-submit:hover { background: #176a3e; }
      .btn-submit:active { transform: scale(0.98); }
      .btn-submit svg { width: 15px; height: 15px; stroke: #fff; fill: none; stroke-width: 2.5; }

      @media (max-width: 600px) {
        .card { padding: 20px 16px 28px; }
        .table-wrap { display: none !important; }
        .mobile-cards { display: block; }
        .page-header { padding: 18px 16px 16px; border-radius: 8px 8px 0 0; }
        .page-header h1 { font-size: 17px; }
      }
    </style>
  </head>
  <body>
    <div class="page-wrap">

      @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert alert-error">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Page header -->
      <div class="page-header">
        <div class="page-header-left">
          <div class="header-icon">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path d="M19 4H5a2 2 0 0 0-2 2v13a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2ZM8 7h8M8 11h8M8 15h5"/>
            </svg>
          </div>
          <h1>Conveyance Bill
            <span>New Entry Form</span>
          </h1>
        </div>
        <a href="{{ route('conveyances.index') }}" class="view-link">
          ← Previous Dates
        </a>
      </div>

      <!-- Main card -->
      <div class="card">
        <form id="conveyanceForm" method="POST" action="{{ route('conveyances.store') }}">
          @csrf

          <!-- Date -->
          <div class="date-row">
            <div class="field-group">
              <label class="flabel" for="billDate">Bill Date</label>
              <input
                class="finput"
                type="date"
                id="billDate"
                name="date"
                style="width: 200px;"
                value="{{ old('date', $date ?? now()->toDateString()) }}"
              />
            </div>
          </div>

          <!-- Desktop table -->
          <div class="section-label">Journey Details</div>

          <div class="table-wrap">
            <table class="entry-table" id="entryTable">
              <thead>
                <tr>
                  <th>#</th>
                  <th class="left">From</th>
                  <th class="left">To</th>
                  <th>Amount (৳)</th>
                  <th>Remarks</th>
                  <th></th>
                </tr>
              </thead>
              <tbody id="entryBody"></tbody>
            </table>
          </div>

          <!-- Mobile cards -->
          <div class="mobile-cards" id="mobileCards"></div>

          <!-- Add row -->
          <button class="btn-add-row" type="button" onclick="addRow()">
            <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add Row
          </button>

          <!-- Totals -->
          <div class="totals-bar">
            <span class="tlabel">Total Amount</span>
            <span class="tvalue" id="formTotal">৳ 0.00</span>
          </div>

          <input type="hidden" name="rows" id="rowsInput" />

          <!-- Submit -->
          <div class="form-actions">
            <button type="submit" class="btn-submit">
              <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
              Save Conveyance
            </button>
          </div>
        </form>
      </div>
    </div>

    <script>
      let rows = [];
      let nextRowId = 1;
      const initialRows = @json($formRows);

      function escapeHtml(value) {
        return String(value ?? '')
          .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
          .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
      }

      function makeRow(row = {}) {
        return { id: nextRowId++, from: row.from || '', to: row.to || '', amount: row.amount ?? '', remarks: row.remarks || '' };
      }

      function addRow() { rows.push(makeRow()); renderAll(); }

      function removeRow(id) { rows = rows.filter(r => r.id !== id); renderAll(); }

      function setField(id, field, value) {
        const row = rows.find(r => r.id === id);
        if (row) { row[field] = value; updateTotal(); }
      }

      function renderAll() { renderTable(); renderMobile(); updateTotal(); }

      function renderTable() {
        const tbody = document.getElementById('entryBody');
        if (!tbody) return;
        tbody.innerHTML = rows.map((row, i) => `
          <tr>
            <td class="sl">${i + 1}</td>
            <td><input type="text" value="${escapeHtml(row.from)}" placeholder="Origin" oninput="setField(${row.id},'from',this.value)" /></td>
            <td><input type="text" value="${escapeHtml(row.to)}" placeholder="Destination" oninput="setField(${row.id},'to',this.value)" /></td>
            <td><input type="number" value="${escapeHtml(row.amount)}" placeholder="0.00" oninput="setField(${row.id},'amount',this.value)" /></td>
            <td><input type="text" value="${escapeHtml(row.remarks)}" placeholder="Optional note" oninput="setField(${row.id},'remarks',this.value)" /></td>
            <td style="text-align:center;width:48px;">
              <button class="btn-remove" type="button" onclick="removeRow(${row.id})" title="Remove">
                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </td>
          </tr>`).join('');
      }

      function renderMobile() {
        const cards = document.getElementById('mobileCards');
        if (!cards) return;
        cards.innerHTML = rows.map((row, i) => `
          <div class="mobile-card">
            <div class="mobile-card-head">
              <span class="mobile-card-num">Entry ${i + 1}</span>
              <button class="btn-remove" type="button" onclick="removeRow(${row.id})">
                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>
            <div class="mobile-grid">
              <input type="text" placeholder="From (Origin)" value="${escapeHtml(row.from)}" oninput="setField(${row.id},'from',this.value)" />
              <input type="text" placeholder="To (Destination)" value="${escapeHtml(row.to)}" oninput="setField(${row.id},'to',this.value)" />
              <input type="number" placeholder="Amount (৳)" value="${escapeHtml(row.amount)}" oninput="setField(${row.id},'amount',this.value)" />
              <input type="text" placeholder="Remarks (optional)" value="${escapeHtml(row.remarks)}" oninput="setField(${row.id},'remarks',this.value)" />
            </div>
          </div>`).join('');
      }

      function updateTotal() {
        const total = rows.reduce((sum, row) => sum + (parseFloat(row.amount) || 0), 0);
        document.getElementById('formTotal').textContent = '৳ ' + total.toFixed(2);
      }

      (function init() {
        const d = document.getElementById('billDate');
        if (d && !d.value) d.value = new Date().toISOString().split('T')[0];

        rows = initialRows.length ? initialRows.map(makeRow) : [makeRow()];
        renderAll();

        document.getElementById('conveyanceForm').addEventListener('submit', function (e) {
          if (!rows.length) { e.preventDefault(); alert('Please add at least one row.'); return; }
          document.getElementById('rowsInput').value = JSON.stringify(
            rows.map(r => ({ from: r.from || '', to: r.to || '', amount: r.amount || '', remarks: r.remarks || '' }))
          );
        });
      })();
    </script>
  </body>
</html>