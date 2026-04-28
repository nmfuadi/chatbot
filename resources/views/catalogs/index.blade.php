<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Katalog Produk & Layanan') }}
            </h2>
            <a href="{{ route('catalogs.create') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm font-bold rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Katalog
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-slate-100">
                <div class="p-6 text-slate-900 overflow-x-auto">
                    
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="p-4 font-bold text-slate-600">Foto</th>
                                <th class="p-4 font-bold text-slate-600">Nama Produk</th>
                                <th class="p-4 font-bold text-slate-600">Harga</th>
                                <th class="p-4 font-bold text-slate-600">Stok/Status</th>
                                <th class="p-4 font-bold text-slate-600 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($catalogs as $item)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="p-4">
                                        <div class="relative w-16 h-16">
                                            @if($item->images->count() > 0)
                                                <img src="{{ asset('storage/' . $item->images->first()->image_path) }}" 
                                                     class="w-16 h-16 object-cover rounded-lg border border-slate-200">
                                                @if($item->images->count() > 1)
                                                    <span class="absolute -top-2 -right-2 bg-indigo-600 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full shadow-sm">
                                                        +{{ $item->images->count() - 1 }}
                                                    </span>
                                                @endif
                                            @else
                                                <div class="w-16 h-16 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-slate-800">{{ $item->item_name }}</div>
                                        <div class="text-xs text-slate-500 truncate max-w-[200px]">{{ $item->description }}</div>
                                    </td>
                                    <td class="p-4 font-medium text-slate-700">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="p-4">
                                        @if($item->stock > 0)
                                            <span class="px-2.5 py-1 bg-green-100 text-green-700 text-xs rounded-md font-bold">
                                                Tersedia: {{ $item->stock }}
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-red-100 text-red-700 text-xs rounded-md font-bold">
                                                Habis / Kosong
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('catalogs.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-indigo-600 transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('catalogs.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');">
                                                @csrf @method('DELETE')
                                                <button class="p-2 text-slate-400 hover:text-red-600 transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-slate-400">Belum ada data katalog.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-6">
                        {{ $catalogs->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>