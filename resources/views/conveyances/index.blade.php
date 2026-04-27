<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Conveyance History | Ashik Auto Solution</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      body {
        font-family: 'Inter', sans-serif;
        background-color: #f8fafc;
        color: #1e293b;
      }
      .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(226, 232, 240, 0.7);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      }
      .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        border-color: #3b82f6;
      }
      .status-badge {
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
      }
      .amount-gradient {
        background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
      }
    </style>
  </head>
  <body class="antialiased">
    <div class="mx-auto max-w-6xl px-4 py-12">
      
      <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
          <nav class="mb-3 flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-400">
            <span>Portal</span>
            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-indigo-600">History</span>
          </nav>
          <h1 class="text-4xl font-extrabold tracking-tight text-slate-900">Billing Timeline</h1>
          <p class="mt-2 text-slate-500">Track and manage your submitted conveyance vouchers.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
          @auth
            <div class="flex items-center gap-3 pr-4 border-r border-slate-200">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-400 uppercase mt-1">{{ auth()->user()->is_admin ? 'Administrator' : 'Team Member' }}</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold shadow-lg">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            </div>

            @if (auth()->user()->is_admin)
              <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all active:scale-95">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Admin
              </a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="inline">
              @csrf
              <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-600 hover:bg-rose-100 transition-all active:scale-95">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
              </button>
            </form>
          @endauth

          <a href="{{ route('conveyances.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-5 py-3 text-sm font-bold text-white shadow-xl shadow-slate-200 hover:bg-black transition-all active:scale-95">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
            New Entry
          </a>
        </div>
      </header>

      <div class="mb-8 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase">Total Records</p>
            <p class="text-2xl font-black text-slate-900">{{ $conveyances->total() }}</p>
        </div>
        <div class="bg-white p-4 rounded-2xl border border-slate-200">
            <p class="text-xs font-bold text-slate-400 uppercase">This Page</p>
            <p class="text-2xl font-black text-indigo-600">৳ {{ number_format($conveyances->sum('total_amount'), 0) }}</p>
        </div>
      </div>

      <form method="GET" action="{{ route('conveyances.index') }}" class="mb-8 rounded-2xl border border-slate-200 bg-white p-4">
        <div class="grid gap-4 md:grid-cols-5">
          <div>
            <label class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-400">From Date</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 outline-none focus:border-indigo-500" />
          </div>
          <div>
            <label class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-400">To Date</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 outline-none focus:border-indigo-500" />
          </div>
          <div>
            <label class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-400">Min Amount</label>
            <input type="number" min="0" step="0.01" name="min_amount" value="{{ request('min_amount') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 outline-none focus:border-indigo-500" />
          </div>
          <div>
            <label class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-400">Max Amount</label>
            <input type="number" min="0" step="0.01" name="max_amount" value="{{ request('max_amount') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 outline-none focus:border-indigo-500" />
          </div>
          @if (auth()->user()->is_admin)
            <div>
              <label class="mb-1 block text-[10px] font-black uppercase tracking-widest text-slate-400">User</label>
              <input type="text" name="user" value="{{ request('user') }}" placeholder="Name or email" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 outline-none focus:border-indigo-500" />
            </div>
          @endif
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
          <p class="text-xs font-semibold text-slate-400">
            Showing {{ $conveyances->firstItem() ?? 0 }} to {{ $conveyances->lastItem() ?? 0 }} of {{ $conveyances->total() }} records
          </p>
          <div class="flex gap-2">
            <a href="{{ route('conveyances.index') }}" class="rounded-xl border border-slate-200 px-4 py-2 text-xs font-bold text-slate-500 hover:bg-slate-50">Clear</a>
            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-bold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700">Filter</button>
          </div>
        </div>
      </form>

      @if (session('status'))
        <div class="mb-6 flex items-center gap-3 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
          <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
          {{ session('status') }}
        </div>
      @endif

      @if ($conveyances->count() === 0)
        <div class="flex flex-col items-center justify-center rounded-3xl border-2 border-dashed border-slate-200 bg-white py-20 text-center">
          <div class="mb-4 rounded-full bg-slate-50 p-4">
            <svg class="h-10 w-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <h3 class="text-lg font-bold text-slate-900">No records found</h3>
          <p class="text-sm text-slate-500">You haven't submitted any conveyance bills yet.</p>
        </div>
      @else
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($conveyances as $conveyance)
            <article class="glass-card relative flex flex-col rounded-3xl p-6">
              <div class="mb-4 flex items-center justify-between">
                <div class="rounded-xl bg-indigo-50 p-2 text-indigo-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <span class="status-badge bg-slate-100 text-slate-600">{{ $conveyance->created_at->diffForHumans(null, true) }} ago</span>
              </div>
              <div class="mb-6">
                <h2 class="text-xl font-extrabold text-slate-900">{{ $conveyance->date->format('D, d M Y') }}</h2>
                @if (auth()->user()->is_admin)
                  <p class="text-[11px] font-bold text-indigo-500 uppercase mt-1 tracking-wider">User: {{ $conveyance->user?->name ?? 'N/A' }}</p>
                @endif
              </div>
              <div class="mt-auto flex items-end justify-between border-t border-slate-100 pt-6">
                <div>
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Amount</p>
                  <p class="amount-gradient text-2xl font-black">৳ {{ number_format($conveyance->total_amount, 0) }}</p>
                </div>
                <div class="text-right">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Items</p>
                  <p class="text-sm font-bold text-slate-700">{{ $conveyance->items_count ?? $conveyance->items->count() }} Entries</p>
                </div>
              </div>
              <div class="mt-6 flex gap-2">
                <a href="{{ route('conveyances.show', $conveyance) }}" class="flex-1 rounded-xl bg-slate-100 py-2.5 text-center text-xs font-bold text-slate-700 hover:bg-slate-200 transition-colors">Details</a>
                <a href="{{ route('conveyances.edit', $conveyance) }}" class="flex-1 rounded-xl bg-slate-100 py-2.5 text-center text-xs font-bold text-slate-700 hover:bg-slate-200 transition-colors">Edit</a>
                <a href="{{ route('conveyances.show', $conveyance) }}" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-center text-xs font-bold text-white hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition-all">PDF</a>
              </div>
            </article>
          @endforeach
        </div>
        <div class="mt-8">
          {{ $conveyances->links() }}
        </div>
      @endif

    </div>
  </body>
</html>