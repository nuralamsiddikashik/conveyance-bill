@php
  $mode = $mode ?? 'create';
@endphp

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conveyance Bill System</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
        position: relative;
      }

      /* FIXED: RESPONSIVE MOBILE GRID */
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

      .btn-pdf {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        background: #1a1a2e;
        color: #fff;
        font-size: 15px;
        font-weight: 600;
        padding: 14px 32px;
        border-radius: 4px;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(26, 26, 46, 0.3);
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

      #pdf-area {
        background: #fff;
        padding: 36px 40px;
        max-width: 900px;
        margin: 0 auto;
        border-radius: 4px;
      }

      /* Print page size and margins */
      @page {
        size: A4;
        margin: 2mm 4mm 4mm 4mm; /* top, right, bottom, left */
      }

      .preview-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 8px;
      }
      .preview-table th {
        border: 1px solid #1a1a2e;
        padding: 12px 14px;
        background: #1a1a2e;
        color: #fff;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
      }
      .preview-table td {
        border: 1px solid #666;
        padding: 10px 12px;
        font-size: 12px;
        text-align: left;
      }
      .preview-table td.tr {
        text-align: right;
      }
      .preview-table thead th { text-align: center; }

      /* Totals row styling */
      .preview-table tfoot tr { background: #f3f4f7; font-weight: 700; }
      .preview-table tfoot td { padding: 10px 12px; }
      #prev-total-cell { text-align: right; font-weight: 800; }
      .preview-table .tl {
        text-align: left;
      }
      .preview-table .tr {
        text-align: right;
      }

      .sig-row {
        display: flex;
        justify-content: space-between;
        margin-top: 60px;
        gap: 15px;
      }
      .sig-item {
        flex: 1;
        text-align: center;
      }
      .sig-line {
        width: 190px;
        margin: 0 auto 8px;
        border-top: 1.5px solid #222;
        height: 1px;
      }
      .sig-label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
      }

      .pdf-compact #pdf-area {
        padding: 5px 15px !important;
      }
      .pdf-compact .preview-table th,
      .pdf-compact .preview-table td {
        font-size: 11px !important;
        padding: 4px 6px !important;
      }
      .pdf-compact .sig-row {
        margin-top: 25px !important;
      }
      .pdf-compact .sig-label {
        font-size: 9px !important;
      }
      .pdf-compact #prev-words {
        font-size: 11px !important;
        margin-top: 8px !important;
      }
      .pdf-compact hr {
        margin: 5px 0 !important;
      }
      .pdf-compact #header-container {
        margin-bottom: 5px !important;
      }

      @media (max-width: 639px) {
        .table-wrap {
          display: none !important;
        }
        .mobile-cards {
          display: block;
        }
        #pdf-area {
          padding: 20px 15px;
        }
      }

      @media print {
        html, body {
          background: #fff !important;
          margin: 2mm !important;
          color: #111 !important;
        }
        .no-print {
          display: none !important;
        }
        #pdf-area {
          box-shadow: none;
          padding: 2mm 3mm 3mm 3mm;
          max-width: 100% !important;
          margin: 0 !important;
          border-radius: 0;
        }
        /* Tighter table and signature spacing for print */
        .preview-table th { padding: 6px 8px; font-size: 12px; }
        .preview-table td { padding: 6px 8px; font-size: 11px; }
        .preview-table td.tr { text-align: right; }
        .sig-line { width: 140px; }
        .preview-table th, .preview-table td {
          -webkit-print-color-adjust: exact;
        }
        .preview-table tr { page-break-inside: avoid; }
        .preview-table { page-break-inside: auto; }
        .sig-row { margin-top: 80px; }
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

      @if ($mode === 'create' || $mode === 'edit')
        <div class="panel no-print">
          <div class="flex items-center justify-between gap-3">
            <h2 class="panel-title mb-0 border-b-0 pb-0">
              {{ $mode === 'edit' ? 'Edit Conveyance' : 'Conveyance Entry' }}
            </h2>
            @if ($mode === 'edit' && isset($conveyance))
              <a
                href="{{ route('conveyances.show', $conveyance) }}"
                class="text-xs font-semibold uppercase tracking-wide text-blue-700 underline"
              >
                Back to View
              </a>
            @else
              <a
                href="{{ route('conveyances.index') }}"
                class="text-xs font-semibold uppercase tracking-wide text-blue-700 underline"
              >
                View Previous Dates
              </a>
            @endif
          </div>

          <form
            id="conveyanceForm"
            method="POST"
            action="{{ $mode === 'edit' && isset($conveyance) ? route('conveyances.update', $conveyance) : route('conveyances.store') }}"
          >
            @csrf
            @if ($mode === 'edit')
              @method('PUT')
            @endif
            <div style="width: min(220px, 100%); margin: 14px 0 18px">
              <label class="field-label">Date</label>
              <input
                class="field-input"
                type="date"
                id="billDate"
                name="date"
                value="{{ old('date', $date ?? '') }}"
                oninput="updatePreview()"
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

            <button
              class="btn-add-row"
              type="button"
              onclick="addRow()"
            >
              Add Row
            </button>

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
                {{ $mode === 'edit' ? 'Update Conveyance' : 'Save Conveyance' }}
              </button>
            </div>
          </form>
        </div>
      @endif

      @if ($mode === 'show')
        <div class="no-print mb-3 flex max-w-[900px] items-center justify-between gap-3">
          <a
            href="{{ route('conveyances.index') }}"
            class="text-xs font-semibold uppercase tracking-wide text-blue-700 underline"
          >
            Back to History
          </a>

          @if(isset($conveyance))
            <div class="flex items-center gap-2">
              <a
                href="{{ route('conveyances.edit', $conveyance) }}"
                class="rounded border border-slate-300 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-slate-700 hover:bg-slate-50"
              >
                Edit
              </a>
              <form
                method="POST"
                action="{{ route('conveyances.destroy', $conveyance) }}"
                onsubmit="return confirm('{{ auth()->user() && auth()->user()->is_admin ? 'Are you sure you want to delete this conveyance?' : 'Send deletion request to admin?' }}');"
              >
                @csrf
                @method('DELETE')
                <button
                  type="submit"
                  class="rounded border border-red-500 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-red-600 hover:bg-red-50"
                >
                  {{ auth()->user() && auth()->user()->is_admin ? 'Delete This Conveyance' : 'Request Deletion' }}
                </button>
              </form>
            </div>
          @endif
        </div>
      @endif

      <div id="pdf-area">
        <div id="header-container" style="margin-bottom: 6px">
          <div
            style="
              font-family: &quot;Merriweather&quot;, serif;
              font-size: 22px;
              font-weight: 900;
              color: #1a1a2e;
              line-height: 1.2;
            "
          >
            ASHIS AUTO SOLUTION
          </div>
          <div
            style="
              font-size: 12px;
              color: #444;
              margin-top: 4px;
              line-height: 1.4;
            "
          >
            Address: Madani Avenue, Beraid, Badda, Dhaka 1212.<br />
            Phone: 01712287659, 01678-094899
          </div>
        </div>

        <hr
          style="border: none; border-top: 2.5px solid #1a1a2e; margin: 10px 0"
        />

        <div
          style="
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
          "
        >
          <div style="font-size: 13px">
            Date:
            <strong id="prev-date">
              @if (!empty($date))
                {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
              @else
                —
              @endif
            </strong>
          </div>
          <div
            style="
              font-family: &quot;Merriweather&quot;, serif;
              font-size: 16px;
              font-weight: 900;
              text-decoration: underline;
            "
          >
            CONVEYANCE BILL
          </div>
          <div style="width: 50px"></div>
        </div>

        <div class="preview-table-wrap">
          <table class="preview-table">
            <thead>
              <tr>
                <th style="width: 40px">SL</th>
                <th>From</th>
                <th>To</th>
                <th style="width: 110px">Amount (৳)</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody id="previewBody"></tbody>
            <tfoot>
              <tr style="font-weight: bold; background: #f5f6fa">
                <td
                  colspan="3"
                  style="text-align: right; border: 1px solid #666"
                >
                  Total
                </td>
                <td
                  class="tr"
                  id="prev-total-cell"
                  style="border: 1px solid #666"
                >
                  ৳ 0.00
                </td>
                <td style="border: 1px solid #666"></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div
          style="font-size: 13px; font-weight: 700; margin-top: 15px"
          id="prev-words"
        >
          Amount In Words: Zero Taka Only
        </div>

        <div class="sig-row">
          <div class="sig-item">
            <div class="sig-line"></div>
            <div class="sig-label">Accounts</div>
          </div>
          <div class="sig-item">
            <div class="sig-line"></div>
            <div class="sig-label">Managing Director / Director</div>
          </div>
          <div class="sig-item">
            <div class="sig-line"></div>
            <div class="sig-label">Store Keeper</div>
          </div>
          <div class="sig-item">
            <div class="sig-line"></div>
            <div class="sig-label">Received By</div>
          </div>
        </div>
      </div>

      <div style="margin-top: 20px" class="no-print">
        <div style="display:flex; gap:8px;">
          <button id="printBtn" class="rounded border border-slate-300 bg-white px-3 py-2 text-sm font-semibold" onclick="printThis()">Print</button>
          <button class="btn-pdf" onclick="downloadPDF()" id="dlBtn">
            Download PDF
          </button>
        </div>
      </div>
    </div>

    <script>
      window.appMode = "{{ $mode }}";
      window.initialRows = @json($rows ?? []);
    </script>

    <script>
      let rows = [];
      const mode = window.appMode || "create";
      const initialRows = Array.isArray(window.initialRows)
        ? window.initialRows
        : [];

      function escapeHtml(value) {
        return String(value ?? "")
          .replace(/&/g, "&amp;")
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;")
          .replace(/'/g, "&#39;");
      }

      function addRow() {
        const id = Date.now() + Math.random();
        rows.push({ id, from: "", to: "", amount: "", remarks: "" });
        renderAll();
      }

      function removeRow(id) {
        rows = rows.filter((r) => r.id !== id);
        renderAll();
      }

      function setField(id, field, value) {
        const row = rows.find((r) => r.id === id);
        if (row) {
          row[field] = value;
          updatePreview();
        }
      }

      function renderAll() {
        renderTable();
        renderMobile();
        updatePreview();
      }

      function renderTable() {
        const tbody = document.getElementById("entryBody");
        if (!tbody) return;

        tbody.innerHTML = rows
          .map((row, idx) => {
            const fromVal = escapeHtml(row.from);
            const toVal = escapeHtml(row.to);
            const amountVal = escapeHtml(row.amount);
            const remarksVal = escapeHtml(row.remarks);
            return `
          <tr>
            <td style="text-align:center;">${idx + 1}</td>
            <td><input value="${fromVal}" oninput="setField(${row.id},'from',this.value)"/></td>
            <td><input value="${toVal}" oninput="setField(${row.id},'to',this.value)"/></td>
            <td><input type="number" value="${amountVal}" style="text-align:right;" oninput="setField(${row.id},'amount',this.value)"/></td>
            <td><input value="${remarksVal}" oninput="setField(${row.id},'remarks',this.value)"/></td>
            <td><button class="btn-remove" onclick="removeRow(${row.id})">✕</button></td>
          </tr>
        `;
          })
          .join("");
      }

      function renderMobile() {
        const mc = document.getElementById("mobileCards");
        if (!mc) return;

        mc.innerHTML = rows
          .map((row, idx) => {
            const fromVal = escapeHtml(row.from);
            const toVal = escapeHtml(row.to);
            const amountVal = escapeHtml(row.amount);
            const remarksVal = escapeHtml(row.remarks);
            return `
          <div class="mobile-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
              <strong style="font-size:14px;">Entry ${idx + 1}</strong>
              <button class="btn-remove" onclick="removeRow(${row.id})">✕</button>
            </div>
            <div class="mobile-grid">
              <input placeholder="From" value="${fromVal}" oninput="setField(${row.id},'from',this.value)"/>
              <input placeholder="To" value="${toVal}" oninput="setField(${row.id},'to',this.value)"/>
              <input placeholder="Amount" type="number" value="${amountVal}" oninput="setField(${row.id},'amount',this.value)"/>
              <input placeholder="Remarks" value="${remarksVal}" oninput="setField(${row.id},'remarks',this.value)"/>
            </div>
          </div>
        `;
          })
          .join("");
      }

      function updatePreview() {
        const billDateInput = document.getElementById("billDate");
        const dateSpan = document.getElementById("prev-date");

        if (billDateInput && dateSpan) {
          const dateVal = billDateInput.value || "";
          let formatted = "—";
          if (dateVal && dateVal.includes("-")) {
            const [y, m, d] = dateVal.split("-");
            formatted = `${d}/${m}/${y}`;
          }
          dateSpan.textContent = formatted;
        }

        let total = 0;
        const previewBody = document.getElementById("previewBody");
        previewBody.innerHTML = rows
          .map((row, idx) => {
            const amt = parseFloat(row.amount) || 0;
            total += amt;
            const fromVal = escapeHtml(row.from);
            const toVal = escapeHtml(row.to);
            const remarksVal = escapeHtml(row.remarks);
            return `
            <tr>
              <td>${idx + 1}</td>
              <td class="tl">${fromVal}</td>
              <td class="tl">${toVal}</td>
              <td class="tr">${amt.toFixed(2)}</td>
              <td class="tl">${remarksVal}</td>
            </tr>
          `;
          })
          .join("");

        const formTotalEl = document.getElementById("formTotal");
        if (formTotalEl) {
          formTotalEl.textContent = `৳ ${total.toFixed(2)}`;
        }
        document.getElementById("prev-total-cell").textContent =
          `৳ ${total.toFixed(2)}`;
        document.getElementById("prev-words").textContent =
          `Amount In Words: ${numberToWords(total)} Taka Only`;
      }

      function downloadPDF() {
        const btn = document.getElementById("dlBtn");
        btn.innerHTML = "Generating...";
        btn.disabled = true;
        document.body.classList.add("pdf-compact");
        const element = document.getElementById("pdf-area");
        const billDateInput = document.getElementById("billDate");
        const filenameDate = billDateInput && billDateInput.value
          ? billDateInput.value
          : new Date().toISOString().split("T")[0];
        const opt = {
          margin: [2, 10, 5, 10],
          filename: `Conveyance_Bill_${filenameDate}.pdf`,
          image: { type: "jpeg", quality: 0.98 },
          html2canvas: { scale: 3, useCORS: true, scrollY: 0 },
          jsPDF: { unit: "mm", format: "a4", orientation: "portrait" },
        };
        html2pdf()
          .set(opt)
          .from(element)
          .save()
          .then(() => {
            document.body.classList.remove("pdf-compact");
            btn.innerHTML = "Download PDF";
            btn.disabled = false;
          });
      }

      function printThis() {
        const btn = document.getElementById('printBtn');
        btn.disabled = true;
        document.body.classList.add('pdf-compact');
        function cleanup() {
          document.body.classList.remove('pdf-compact');
          btn.disabled = false;
          window.removeEventListener('afterprint', cleanup);
        }
        window.addEventListener('afterprint', cleanup);
        window.print();
      }

      function numberToWords(num) {
        if (num === 0) return "Zero";
        const ones = [
          "",
          "One",
          "Two",
          "Three",
          "Four",
          "Five",
          "Six",
          "Seven",
          "Eight",
          "Nine",
          "Ten",
          "Eleven",
          "Twelve",
          "Thirteen",
          "Fourteen",
          "Fifteen",
          "Sixteen",
          "Seventeen",
          "Eighteen",
          "Nineteen",
        ];
        const tens = [
          "",
          "",
          "Twenty",
          "Thirty",
          "Forty",
          "Fifty",
          "Sixty",
          "Seventy",
          "Eighty",
          "Ninety",
        ];
        function convert(n) {
          if (n < 20) return ones[n];
          if (n < 100)
            return (
              tens[Math.floor(n / 10)] + (n % 10 ? " " + ones[n % 10] : "")
            );
          return (
            ones[Math.floor(n / 100)] +
            " Hundred" +
            (n % 100 ? " " + convert(n % 100) : "")
          );
        }
        let str = "";
        let integerPart = Math.floor(num);
        if (integerPart >= 100000) {
          str += convert(Math.floor(integerPart / 100000)) + " Lakh ";
          integerPart %= 100000;
        }
        if (integerPart >= 1000) {
          str += convert(Math.floor(integerPart / 1000)) + " Thousand ";
          integerPart %= 1000;
        }
        if (integerPart > 0) {
          str += convert(integerPart);
        }
        return str.trim();
      }

      // Bootstrap initial state
      (function init() {
        const billDateInput = document.getElementById("billDate");
        if (billDateInput && !billDateInput.value) {
          billDateInput.value = new Date().toISOString().split("T")[0];
        }

        if (initialRows.length > 0) {
          rows = initialRows.map((row) => ({
            id: Date.now() + Math.random(),
            from: row.from || "",
            to: row.to || "",
            amount: row.amount ?? "",
            remarks: row.remarks || "",
          }));
        } else {
          addRow();
        }

        const form = document.getElementById("conveyanceForm");
        if (form && (mode === "create" || mode === "edit")) {
          form.addEventListener("submit", function (e) {
            if (!rows.length) {
              e.preventDefault();
              alert("Please add at least one row.");
              return;
            }
            const payload = rows.map((row) => ({
              from: row.from || "",
              to: row.to || "",
              amount: row.amount || "",
              remarks: row.remarks || "",
            }));
            document.getElementById("rowsInput").value =
              JSON.stringify(payload);
          });
        }

        renderAll();
      })();
    </script>
 
  </body>
</html>
