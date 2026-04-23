@php
  $billRows = collect($rows ?? []);
  $billTotal = (float) ($conveyance?->total_amount ?? $billRows->sum(fn ($row) => (float) ($row['amount'] ?? 0)));
@endphp

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conveyance Bill - Conveyance Bill</title>
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

      #pdf-area {
        background: #fff;
        padding: 36px 40px;
        max-width: 900px;
        margin: 0 auto;
        border-radius: 4px;
      }

      @page {
        size: A4;
        margin: 2mm 4mm 4mm 4mm;
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
        text-align: center;
      }

      .preview-table td {
        border: 1px solid #666;
        padding: 10px 12px;
        font-size: 12px;
        text-align: left;
      }

      .preview-table tfoot tr {
        background: #f3f4f7;
        font-weight: 700;
      }

      .preview-table tfoot td {
        padding: 10px 12px;
      }

      .tl {
        text-align: left;
      }

      .tr {
        text-align: right;
      }

      #prev-total-cell {
        text-align: right;
        font-weight: 800;
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

      .pdf-compact .sig-label,
      .pdf-compact #prev-words {
        font-size: 11px !important;
      }

      .pdf-compact hr {
        margin: 5px 0 !important;
      }

      .pdf-compact #header-container {
        margin-bottom: 5px !important;
      }

      @media (max-width: 639px) {
        #pdf-area {
          padding: 20px 15px;
        }
      }

      @media print {
        html,
        body {
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

        .preview-table th {
          padding: 6px 8px;
          font-size: 12px;
        }

        .preview-table td {
          padding: 6px 8px;
          font-size: 11px;
        }

        .sig-line {
          width: 140px;
        }

        .preview-table th,
        .preview-table td {
          -webkit-print-color-adjust: exact;
        }

        .preview-table tr {
          page-break-inside: avoid;
        }

        .sig-row {
          margin-top: 80px;
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
          <div style="font-size: 12px; color: #444; margin-top: 4px; line-height: 1.4">
            Address: Madani Avenue, Beraid, Badda, Dhaka 1212.<br />
            Phone: 01712287659, 01678-094899
          </div>
        </div>

        <hr style="border: none; border-top: 2.5px solid #1a1a2e; margin: 10px 0" />

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px">
          <div style="font-size: 13px">
            Date:
            <strong>
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
            <tbody>
              @forelse ($billRows as $row)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td class="tl">{{ $row['from'] ?? '' }}</td>
                  <td class="tl">{{ $row['to'] ?? '' }}</td>
                  <td class="tr">{{ number_format((float) ($row['amount'] ?? 0), 2) }}</td>
                  <td class="tl">{{ $row['remarks'] ?? '' }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="tl">No conveyance rows found.</td>
                </tr>
              @endforelse
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" style="text-align: right; border: 1px solid #666">Total</td>
                <td class="tr" id="prev-total-cell" style="border: 1px solid #666">৳ {{ number_format($billTotal, 2) }}</td>
                <td style="border: 1px solid #666"></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <div style="font-size: 13px; font-weight: 700; margin-top: 15px" id="prev-words">
          Amount In Words: {{ $amountWords ?? 'Zero' }} Taka Only
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
      function downloadPDF() {
        const btn = document.getElementById("dlBtn");
        btn.innerHTML = "Generating...";
        btn.disabled = true;
        document.body.classList.add("pdf-compact");

        const element = document.getElementById("pdf-area");
        const filenameDate = "{{ $date ?? now()->toDateString() }}";
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
        const btn = document.getElementById("printBtn");
        btn.disabled = true;
        document.body.classList.add("pdf-compact");

        function cleanup() {
          document.body.classList.remove("pdf-compact");
          btn.disabled = false;
          window.removeEventListener("afterprint", cleanup);
        }

        window.addEventListener("afterprint", cleanup);
        window.print();
      }
    </script>
  </body>
</html>
