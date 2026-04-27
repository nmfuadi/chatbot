<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                History Chat: <span class="text-indigo-600">{{ $customerName }}</span> ({{ $phone }})
            </h2>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('customers.index') }}" class="px-4 py-2 bg-slate-200 text-slate-700 text-sm font-bold rounded-lg hover:bg-slate-300 transition">
                    &larr; Kembali
                </a>

                @if($histories->count() > 0)
                    <form action="{{ route('customers.history.clear', $phone) }}" method="POST" class="m-0" onsubmit="return confirm('Yakin ingin menghapus SEMUA history chat dengan customer ini? Tindakan ini tidak bisa dibatalkan.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 text-red-600 text-sm font-bold rounded-lg hover:bg-red-200 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Hapus History
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative">
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-[#e5ddd5] overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 relative" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png');">
                <div class="p-6 h-[600px] overflow-y-auto flex flex-col gap-4">
                    
                    @forelse ($histories as $history)
                        <div class="flex flex-col items-start w-full">
                            <div class="bg-white text-slate-800 px-4 py-3 rounded-2xl rounded-tl-none shadow-sm max-w-[80%]">
                                <p class="text-sm whitespace-pre-wrap">{{ $history->user_message }}</p>
                            </div>
                            <span class="text-xs text-slate-500 mt-1 ml-1">{{ $history->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        @if($history->ai_response)
                            <div class="flex flex-col items-end w-full">
                                <div class="bg-indigo-100 text-indigo-900 px-4 py-3 rounded-2xl rounded-tr-none shadow-sm max-w-[80%] border border-indigo-200">
                                    <p class="text-sm whitespace-pre-wrap">{{ $history->ai_response }}</p>
                                </div>
                                <span class="text-xs text-slate-500 mt-1 mr-1">AI • {{ $history->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        @endif
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-center opacity-60">
                            <svg class="w-16 h-16 mb-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <p class="font-medium text-slate-700">Belum ada riwayat percakapan.</p>
                        </div>
                    @endforelse

                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>