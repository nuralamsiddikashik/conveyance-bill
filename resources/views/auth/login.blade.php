<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login - Conveyance Ledger</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "error": "#ba1a1a",
                        "surface-dim": "#d9dadb",
                        "on-primary-fixed": "#00105b",
                        "surface-bright": "#f8f9fa",
                        "outline-variant": "#c5c5d4",
                        "secondary": "#4c56af",
                        "primary-fixed": "#dee0ff",
                        "inverse-surface": "#2e3132",
                        "surface-container": "#edeeef",
                        "surface-variant": "#e1e3e4",
                        "secondary-fixed": "#e0e0ff",
                        "tertiary-fixed": "#ebdcff",
                        "on-tertiary": "#ffffff",
                        "on-tertiary-container": "#dec9ff",
                        "on-error": "#ffffff",
                        "primary-container": "#4453a7",
                        "on-primary-fixed-variant": "#2f3f92",
                        "on-surface-variant": "#454652",
                        "surface": "#f8f9fa",
                        "surface-tint": "#4858ab",
                        "on-primary-container": "#c9cfff",
                        "surface-container-high": "#e7e8e9",
                        "tertiary-container": "#6b44ae",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary": "#ffffff",
                        "surface-container-low": "#f3f4f5",
                        "tertiary": "#532a95",
                        "on-tertiary-fixed": "#260058",
                        "background": "#f8f9fa",
                        "inverse-primary": "#bac3ff",
                        "on-tertiary-fixed-variant": "#572e99",
                        "on-secondary-fixed": "#000767",
                        "on-primary": "#ffffff",
                        "primary": "#2b3b8e",
                        "on-background": "#191c1d",
                        "on-secondary-container": "#27308a",
                        "on-secondary-fixed-variant": "#343d96",
                        "tertiary-fixed-dim": "#d4bbff",
                        "outline": "#757684",
                        "on-surface": "#191c1d",
                        "error-container": "#ffdad6",
                        "secondary-container": "#959efd",
                        "secondary-fixed-dim": "#bdc2ff",
                        "inverse-on-surface": "#f0f1f2",
                        "primary-fixed-dim": "#bac3ff",
                        "on-error-container": "#93000a",
                        "surface-container-highest": "#e1e3e4"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["Manrope"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .bg-editorial-gradient {
            background: linear-gradient(135deg, #2b3b8e 0%, #4453a7 100%);
        }
    </style>
</head>
<body class="bg-background font-body text-on-background min-h-screen flex items-center justify-center p-6">
    <div class="fixed inset-0 z-0 overflow-hidden pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[40%] h-[40%] bg-primary-container/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[30%] h-[30%] bg-tertiary-container/5 rounded-full blur-[100px]"></div>
    </div>

    <main class="relative z-10 w-full max-w-[480px]">
        <div class="text-center mb-10">
            <h1 class="font-headline font-black text-primary text-3xl uppercase tracking-wider mb-2">Conveyance Ledger</h1>
            <p class="font-body text-on-surface-variant text-sm tracking-wide">Enter your credentials to access the ledger portal</p>
        </div>

        <div class="bg-surface-container-lowest rounded-xl shadow-[0_32px_48px_-12px_rgba(25,28,29,0.04)] overflow-hidden">
            <div class="h-2 bg-editorial-gradient"></div>
            <div class="p-10 md:p-12">
                
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-lg bg-green-50 text-green-800 text-sm border border-green-200">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg bg-red-50 text-red-800 text-sm border border-red-200">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-2">
                        <label class="font-label text-xs font-semibold uppercase tracking-widest text-on-surface-variant ml-1" for="email">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-[20px]">mail</span>
                            </div>
                            <input class="w-full pl-12 pr-4 py-4 bg-surface-container-highest text-on-surface font-body text-sm rounded-lg border-none focus:ring-0 focus:bg-surface-container-lowest transition-colors border-b-2 border-transparent focus:border-primary duration-200" 
                                id="email" name="email" type="email" placeholder="name@company.com" value="{{ old('email') }}" required autofocus />
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex justify-between items-end ml-1">
                            <label class="font-label text-xs font-semibold uppercase tracking-widest text-on-surface-variant" for="password">Password</label>
                            <a class="font-label text-xs font-medium text-primary hover:underline transition-all" href="">Forgot Password?</a>
                        </div>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-outline">
                                <span class="material-symbols-outlined text-[20px]">lock</span>
                            </div>
                            <input class="w-full pl-12 pr-4 py-4 bg-surface-container-highest text-on-surface font-body text-sm rounded-lg border-none focus:ring-0 focus:bg-surface-container-lowest transition-colors border-b-2 border-transparent focus:border-primary duration-200" 
                                id="password" name="password" type="password" placeholder="••••••••" required />
                        </div>
                    </div>

                    <div class="flex items-center px-1">
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <div class="relative flex items-center">
                                <input name="remember" class="peer h-5 w-5 rounded border-outline-variant bg-surface-container-highest text-primary focus:ring-0 focus:ring-offset-0 transition-all" type="checkbox"/>
                            </div>
                            <span class="font-body text-sm text-on-surface-variant group-hover:text-on-surface transition-colors">Remember this device for 30 days</span>
                        </label>
                    </div>

                    <div class="pt-4">
                        <button class="w-full py-4 bg-editorial-gradient text-on-primary font-headline font-bold text-base rounded-lg shadow-lg shadow-primary/20 hover:shadow-xl hover:shadow-primary/30 active:scale-[0.98] transition-all flex items-center justify-center gap-2" type="submit">
                            <span>Sign into Ledger</span>
                            <span class="material-symbols-outlined text-[20px]">login</span>
                        </button>
                    </div>
                </form>

                <div class="mt-10 pt-8 text-center border-t border-outline-variant/15">
                    <p class="font-body text-sm text-on-surface-variant">
                        Don't have an account? 
                        <a class="font-semibold text-primary hover:text-primary-container transition-colors ml-1" href="{{ route('register.show') }}">Register</a>
                    </p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>