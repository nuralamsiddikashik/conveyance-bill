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
    <title>Edit Conveyance - Conveyance Bill</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Source+Sans+3:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      *,
      *::before,
      *::after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: "Source Sans 3", sans-serif;
        background: #eef0f4;
        min-height: 100vh;
        padding: 16px 10px 60px;
        color: #1a1a1a;
      }

      .panel {
        background: #fff;
        border-radius: 4px;
        box-shadow:
          0 1px 4px rgba(0, 0, 0, 0.12),
          0 4px 16px rgba(0, 0, 0, 0.06);
        padding: 18px 14px;
        margin-bottom: 16px;
        max-width: 900px;
        margin-left: auto;
        margin-right: auto;
      }

      h2.panel-title {
        font-family: "Merriweather", serif;
        font-size: 16px;
        font-weight: 700;
        color: #111;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 2.5px solid #1a1a2e;
      }

      label.field-label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #666;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.7px;
      }

      .field-input {
        width: 100%;
        border: 1px solid #c8ccd4;
        border-radius: 3px;
        padding: 10px 11px;
        font-family: "Source Sans 3", sans-serif;
        font-size: 15px;
        color: #1a1a1a;
        outline: none;
        transition:
          border-color 0.15s,
          box-shadow 0.15s;
        background: #fafafa;
      }

      .field-input:focus {
        border-color: #1a1a2e;
        box-shadow: 0 0 0 3px rgba(26, 26, 46, 0.08);
        background: #fff;
      }

      .table-wrap {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
      }

      .entry-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 520px;
      }

      .entry-table th {
        background: #1a1a2e;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        padding: 9px 10px;
        text-align: center;
        border: 1px solid #1a1a2e;
      }

      .entry-table td {
        border: 1px solid #d0d3da;
        padding: 5px 6px;
        vertical-align: middle;
        background: #fff;
      }

      .entry-table input {
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 2px;
        padding: 8px 9px;
        font-size: 14px;
        outline: none;
      }

      .mobile-cards {
        display: none;
      }

      .mobile-card {
        background: #fff;
        border: 1px solid #d0d3da;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 10px;
      }

      .mobile-grid {
        display: flex;
        flex-direction: column;
        gap: 8px;
      }

      .mobile-grid input {
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 3px;
        padding: 8px;
        font-size: 14px;
      }

      .btn-add-row {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        margin-top: 12px;
        width: 100%;
        background: #fff;
        border: 2px solid #1a1a2e;
        color: #1a1a2e;
        font-weight: 600;
        padding: 11px 18px;
        border-radius: 4px;
        cursor: pointer;
      }

      .btn-remove {
        background: #fff0f0;
        border: 1.5px solid #f87171;
        color: #dc2626;
        border-radius: 4px;
        width: 32px;
        height: 32px;
        cursor: pointer;
      }

      .totals-bar {
        background: #f0f2f7;
        border: 1px solid #d0d3da;
        border-radius: 3px;
        padding: 11px 14px;
        margin-top: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      @media (max-width: 639px) {
        .table-wrap {
          display: none !important;
        }

        .mobile-cards {
          display: block;
        }
      }
    </style>
  </head>
  <body>
    <div style="max-width: 900px; margin: 0 auto">
      <div class="no-print" style="max-width: 900px; margin: 0 auto 12px">
        @if (session('status'))
          <div class="mb-3 rounded border border-green-500 bg-green-50 px-3 py-2 text-sm text-green-800">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div class="mb-3 rounded border border-red-500 bg-red-50 px-3 py-2 text-sm text-red-800">
            <ul class="list-disc pl-4">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>

      <div class="panel no-print">
        <div class="flex items-center justify-between gap-3">
          <h2 class="panel-title mb-0 border-b-0 pb-0">Edit Conveyance</h2>
          <a
            href="{{ route('conveyances.show', $conveyance) }}"
            class="text-xs font-semibold uppercase tracking-wide text-blue-700 underline"
          >
            Back to View
          </a>
        </div>

        <form id="conveyanceForm" method="POST" action="{{ route('conveyances.update', $conveyance) }}">
          @csrf
          @method('PUT')

          <div style="width: min(220px, 100%); margin: 14px 0 18px">
            <label class="field-label">Date</label>
            <input
              class="field-input"
              type="date"
              id="billDate"
              name="date"
              value="{{ old('date', $date ?? '') }}"
            />
          </div>

          <div class="table-wrap">
            <table class="entry-table" id="entryTable">
              <thead>
                <tr>
                  <th style="width: 40px">SL</th>
                  <th>From</th>
                  <th>To</th>
                  <th style="width: 120px">Amount (৳)</th>
                  <th style="width: 140px">Remarks</th>
                  <th style="width: 44px">Del</th>
                </tr>
              </thead>
              <tbody id="entryBody"></tbody>
            </table>
          </div>

          <div class="mobile-cards" id="mobileCards"></div>

          <button class="btn-add-row" type="button" onclick="addRow()">Add Row</button>

          <div class="totals-bar">
            <span class="tlabel">Total Amount</span>
            <span class="tvalue" id="formTotal">৳ 0.00</span>
          </div>

          <input type="hidden" name="rows" id="rowsInput" />

          <div class="mt-4 flex flex-wrap gap-3">
            <button
              type="submit"
              class="rounded bg-emerald-600 px-4 py-2 text-sm font-semibold uppercase tracking-wide text-white shadow hover:bg-emerald-700"
            >
              Update Conveyance
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
        return String(value ?? "")
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#39;");
      }

      function makeRow(row = {}) {
        return {
          id: nextRowId++,
          from: row.from || "",
          to: row.to || "",
          amount: row.amount ?? "",
          remarks: row.remarks || "",
        };
      }

      function addRow() {
        rows.push(makeRow());
        renderAll();
      }

      function removeRow(id) {
        rows = rows.filter((row) => row.id !== id);
        renderAll();
      }

      function setField(id, field, value) {
        const row = rows.find((item) => item.id === id);
        if (!row) return;
        row[field] = value;
        updateTotal();
      }

      function renderAll() {
        renderTable();
        renderMobile();
        updateTotal();
      }

      function renderTable() {
        const tbody = document.getElementById("entryBody");
        if (!tbody) return;

        tbody.innerHTML = rows
          .map((row, index) => `
            <tr>
              <td style="text-align:center;">${index + 1}</td>
              <td><input value="${escapeHtml(row.from)}" oninput="setField(${row.id}, 'from', this.value)" /></td>
              <td><input value="${escapeHtml(row.to)}" oninput="setField(${row.id}, 'to', this.value)" /></td>
              <td><input type="number" value="${escapeHtml(row.amount)}" style="text-align:right;" oninput="setField(${row.id}, 'amount', this.value)" /></td>
              <td><input value="${escapeHtml(row.remarks)}" oninput="setField(${row.id}, 'remarks', this.value)" /></td>
              <td><button class="btn-remove" type="button" onclick="removeRow(${row.id})">x</button></td>
            </tr>
          `)
          .join("");
      }

      function renderMobile() {
        const cards = document.getElementById("mobileCards");
        if (!cards) return;

        cards.innerHTML = rows
          .map((row, index) => `
            <div class="mobile-card">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                <strong style="font-size:14px;">Entry ${index + 1}</strong>
                <button class="btn-remove" type="button" onclick="removeRow(${row.id})">x</button>
              </div>
              <div class="mobile-grid">
                <input placeholder="From" value="${escapeHtml(row.from)}" oninput="setField(${row.id}, 'from', this.value)" />
                <input placeholder="To" value="${escapeHtml(row.to)}" oninput="setField(${row.id}, 'to', this.value)" />
                <input placeholder="Amount" type="number" value="${escapeHtml(row.amount)}" oninput="setField(${row.id}, 'amount', this.value)" />
                <input placeholder="Remarks" value="${escapeHtml(row.remarks)}" oninput="setField(${row.id}, 'remarks', this.value)" />
              </div>
            </div>
          `)
          .join("");
      }

      function updateTotal() {
        const total = rows.reduce((sum, row) => sum + (parseFloat(row.amount) || 0), 0);
        document.getElementById("formTotal").textContent = `৳ ${total.toFixed(2)}`;
      }

      (function initConveyanceForm() {
        rows = initialRows.length ? initialRows.map(makeRow) : [makeRow()];
        renderAll();

        document.getElementById("conveyanceForm").addEventListener("submit", function (event) {
          if (!rows.length) {
            event.preventDefault();
            alert("Please add at least one row.");
            return;
          }

          document.getElementById("rowsInput").value = JSON.stringify(rows.map((row) => ({
            from: row.from || "",
            to: row.to || "",
            amount: row.amount || "",
            remarks: row.remarks || "",
          })));
        });
      })();
    </script>
  </body>
</html>
