<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Paket Langganan - Smart AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen pb-12">
    
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Pilih Paket Langganan Anda</h1>
            <p class="mt-2 text-lg text-gray-500">Pilih paket yang paling sesuai dengan kebutuhan bisnis Anda.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="max-w-7xl mx-auto mt-6 px-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            
        @foreach($plans as $plan)
    <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 flex flex-col">
        <h3 class="text-xl font-bold text-slate-900">{{ $plan->name }}</h3>
        <p class="text-3xl font-black text-slate-900 mt-4">
            Rp {{ number_format($plan->price, 0, ',', '.') }}
        </p>
        
        <ul class="mt-6 space-y-4 mb-8 flex-1">
            
            <li class="flex items-start text-sm text-slate-700">
                <svg class="w-5 h-5 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>
                    @if($plan->max_messages > 0)
                        Kuota <strong>{{ number_format($plan->max_messages, 0, ',', '.') }}</strong> Pesan AI
                    @else
                        Kuota <strong>Unlimited</strong> Pesan AI
                    @endif
                </span>
            </li>

            <li class="flex items-start text-sm text-slate-700">
                <svg class="w-5 h-5 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Integrasi WhatsApp Webhook</span>
            </li>

            <li class="flex items-start text-sm text-slate-700">
                <svg class="w-5 h-5 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Katalog Produk & Kirim Gambar</span>
            </li>

            <li class="flex items-start text-sm text-slate-700">
                <svg class="w-5 h-5 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Custom SOP & Knowledge Base</span>
            </li>

            <li class="flex items-start text-sm text-slate-700">
                <svg class="w-5 h-5 text-green-500 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>Manajemen Database Pelanggan</span>
            </li>

        </ul>

        @if($plan->price == 0)
            @if($hasUsedTrial)
                <div class="space-y-2">
                    <button disabled class="w-full py-4 bg-slate-100 text-slate-400 rounded-2xl font-bold cursor-not-allowed flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Sudah Digunakan
                    </button>
                    <p class="text-[10px] text-center text-rose-500 font-bold uppercase tracking-wider">
                        Paket uji coba hanya bisa diklaim 1x
                    </p>
                </div>
            @else
                <form action="{{ route('user.plans.subscribe', $plan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 transition-colors shadow-lg shadow-blue-200">
                        Ambil Uji Coba Gratis
                    </button>
                </form>
            @endif
        @else
            <form action="{{ route('user.plans.subscribe', $plan->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-black transition-colors shadow-lg shadow-slate-200">
                    Pilih Paket Premium
                </button>
            </form>
        @endif
    </div>
@endforeach

        </div>
    </div>
</body>
</html>