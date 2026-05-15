@php
  $billRows = collect($rows ?? []);
  $billTotal = (float) ($conveyance?->total_amount ?? $billRows->sum(fn ($row) => (float) ($row['amount'] ?? 0)));
@endphp

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conveyance Bill</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link
      href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Source+Sans+3:wght@400;500;600;700&display=swap"
      rel="stylesheet"
    />
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

      .tl { text-align: left; }
      .tr { text-align: right; }

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

      /* PDF compact mode */
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
    <div style="max-width: 900px; margin: 0 auto;">

      {{-- FLASH MESSAGES --}}
      <div class="no-print" style="margin-bottom: 12px;">
        @if (session('status'))
          <div style="margin-bottom: 10px; border-radius: 4px; border: 1px solid #22c55e; background: #f0fdf4; padding: 8px 12px; font-size: 14px; color: #166534;">
            {{ session('status') }}
          </div>
        @endif

        @if ($errors->any())
          <div style="margin-bottom: 10px; border-radius: 4px; border: 1px solid #ef4444; background: #fef2f2; padding: 8px 12px; font-size: 14px; color: #991b1b;">
            <ul style="padding-left: 16px; list-style: disc;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>

      {{-- TOP NAV BUTTONS --}}
      <div class="no-print" style="display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px;">
        
          <a href="{{ route('conveyances.index') }}"
          style="font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #1d4ed8; text-decoration: underline;"
        >
          ← Back to History
        </a>

        @if(isset($conveyance))
          <div style="display: flex; align-items: center; gap: 8px;">
            
              <a href="{{ route('conveyances.edit', $conveyance) }}"
              style="border: 1px solid #cbd5e1; border-radius: 4px; padding: 4px 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #334155; text-decoration: none; background: #fff;"
            >
              Edit
            </a>
            <form
              method="POST"
              action="{{ route('conveyances.destroy', $conveyance) }}"
              onsubmit="return confirm('{{ auth()->user() && auth()->user()->is_admin ? 'Are you sure you want to delete this conveyance?' : 'Send deletion request to admin?' }}');"
              style="margin: 0;"
            >
              @csrf
              @method('DELETE')
              <button
                type="submit"
                style="border: 1px solid #ef4444; border-radius: 4px; padding: 4px 12px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #dc2626; background: transparent; cursor: pointer;"
              >
                {{ auth()->user() && auth()->user()->is_admin ? 'Delete This Conveyance' : 'Request Deletion' }}
              </button>
            </form>
          </div>
        @endif
      </div>

      {{-- PDF AREA --}}
      <div id="pdf-area">

        {{-- HEADER --}}
        <div id="header-container" style="margin-bottom: 6px; display: flex; justify-content: space-between; align-items: flex-start;">
          <div>
            <div style="font-family: 'Merriweather', serif; font-size: 22px; font-weight: 900; color: #1a1a2e; line-height: 1.2;">
              ASHIS AUTO SOLUTION
            </div>
            <div style="font-size: 12px; color: #444; margin-top: 4px; line-height: 1.4;">
              Address: Madani Avenue, Beraid, Badda, Dhaka 1212.<br />
              Phone: 01712287659, 01678-094899
            </div>
          </div>
          <div style="text-align: right;">
            <div style="font-family: 'Merriweather', serif; font-size: 16px; font-weight: 900; text-decoration: underline; color: #1a1a2e;">
              CONVEYANCE BILL
            </div>
            <div style="font-size: 13px; margin-top: 6px;">
              Date: <strong>
                @if (!empty($date))
                  {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}
                @else
                  —
                @endif
              </strong>
            </div>
          </div>
        </div>

        <hr style="border: none; border-top: 2.5px solid #1a1a2e; margin: 10px 0;" />

        {{-- TABLE --}}
        <table class="preview-table">
          <thead>
            <tr>
              <th style="width: 40px;">SL</th>
              <th>From</th>
              <th>To</th>
              <th style="width: 110px;">Amount (৳)</th>
              <th>Remarks</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($billRows as $row)
              <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
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
              <td colspan="3" style="border: 1px solid #666; padding: 10px 12px; font-size: 12px; font-weight: 600; background: #f3f4f7;">
                Amount In Words: <span style="font-weight: 700;">{{ $amountWords ?? 'Zero' }} Taka Only</span>
              </td>
              <td style="border: 1px solid #666; padding: 10px 12px; text-align: right; font-size: 13px; font-weight: 800; white-space: nowrap; background: #f3f4f7;">
                ৳ {{ number_format($billTotal, 2) }}
              </td>
              <td style="border: 1px solid #666; background: #f3f4f7;"></td>
            </tr>
          </tfoot>
        </table>

        @if (!empty($note))
          <div style="margin-top: 14px; border: 1px solid #d4d9e2; border-radius: 6px; background: #f8fafc; padding: 12px 14px;">
            <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #64748b; margin-bottom: 6px;">
              Note
            </div>
            <div style="font-size: 13px; line-height: 1.6; color: #1e293b; white-space: pre-wrap;">{{ $note }}</div>
          </div>
        @endif

        {{-- SIGNATURES --}}
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
            <div class="sig-label">Received By</div>
          </div>
        </div>

      </div>
      {{-- END PDF AREA --}}

      {{-- BOTTOM BUTTONS --}}
      <div class="no-print" style="margin-top: 20px; display: flex; gap: 8px;">
        <button
          id="printBtn"
          onclick="printThis()"
          style="border: 1px solid #cbd5e1; background: #fff; border-radius: 4px; padding: 10px 20px; font-size: 14px; font-weight: 600; cursor: pointer;"
        >
          Print
        </button>
        <button
          id="dlBtn"
          onclick="downloadPDF()"
          style="flex: 1; background: #1a1a2e; color: #fff; border: none; border-radius: 4px; padding: 10px 32px; font-size: 15px; font-weight: 600; cursor: pointer; box-shadow: 0 2px 8px rgba(26,26,46,0.3);"
        >
          Download PDF
        </button>
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
