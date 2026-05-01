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
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden flex flex-col hover:shadow-xl transition-shadow duration-300 relative">
                
                @if($plan->price == 0)
                    <div class="absolute top-0 right-0 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">Terpopuler</div>
                @endif

                <div class="p-8 text-center border-b border-gray-100">
                    <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                    <div class="mt-4 flex items-baseline justify-center text-4xl font-extrabold text-blue-600">
                        @if($plan->price == 0)
                            Gratis
                        @else
                            <span class="text-xl font-medium text-gray-500 mr-1">Rp</span>
                            {{ number_format($plan->price, 0, ',', '.') }}
                        @endif
                    </div>
                    <p class="mt-2 text-sm text-gray-500">
                        @if($plan->price == 0)
                            Akses 7 Hari
                        @else
                            Per Bulan
                        @endif
                    </p>
                </div>
                
                <div class="p-8 flex-1 bg-gray-50">
                    <ul class="space-y-4">
                        @if(is_array($plan->features))
                            @foreach($plan->features as $feature)
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span class="ml-3 text-sm text-gray-700">{{ $feature }}</span>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </div>

                <div class="p-8 bg-gray-50 pt-0 mt-auto">
                    <form action="{{ route('user.plans.subscribe', $plan->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full block text-center rounded-lg px-6 py-3 font-semibold text-white transition-colors duration-200 {{ $plan->price == 0 ? 'bg-green-600 hover:bg-green-700' : 'bg-blue-600 hover:bg-blue-700' }}">
                            {{ $plan->price == 0 ? 'Mulai Uji Coba' : 'Pilih Paket' }}
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</body>
</html>