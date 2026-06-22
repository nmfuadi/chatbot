<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    {{ __('Blacklist Nomor WhatsApp') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">Daftar nomor yang diblokir agar bot AI tidak memberikan balasan/merespons.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-100 text-green-700 px-4 py-3 rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-gray-900/5 overflow-hidden p-8">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2.5 bg-red-50 text-red-600 rounded-xl">
                        <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-gray-900 tracking-tight">Tambahkan Nomor Baru</h3>
                        <p class="text-xs text-gray-500">Nomor dengan awalan 08 atau +62 akan otomatis dinormalisasi oleh sistem.</p>
                    </div>
                </div>

                <form action="{{ route('member.blacklist.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                    @csrf
                    <input type="text" name="phone_number" 
                        class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-inner focus:ring-2 focus:ring-red-500 text-sm p-3.5" 
                        placeholder="Contoh: 0852873983478 atau +62852..." required>
                    
                    <button type="submit" class="flex-shrink-0 flex justify-center items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-black py-3 px-6 rounded-xl shadow-md transition-all active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Blokir Nomor
                    </button>
                </form>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-sm ring-1 ring-gray-900/5 overflow-hidden">
                <div class="p-8 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-900 tracking-tight">Daftar Nomor Terblokir</h3>
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-bold rounded-full border border-gray-200">
                        Total: {{ $blacklists->count() }} Nomor
                    </span>
                </div>

                <div class="overflow-x-auto">
                    @if($blacklists->isEmpty())
                        <div class="p-10 text-center flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mb-4">
                                <svg class="w-8 h-8 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h4 class="text-gray-500 font-bold mb-1">Daftar Masih Kosong</h4>
                            <p class="text-sm text-gray-400">Belum ada nomor WhatsApp yang Anda blokir.</p>
                        </div>
                    @else
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr>
                                    <th class="py-4 px-6 bg-gray-50 text-xs font-black text-gray-500 uppercase tracking-wider border-b border-gray-200">No</th>
                                    <th class="py-4 px-6 bg-gray-50 text-xs font-black text-gray-500 uppercase tracking-wider border-b border-gray-200">Nomor WhatsApp</th>
                                    <th class="py-4 px-6 bg-gray-50 text-xs font-black text-gray-500 uppercase tracking-wider border-b border-gray-200">Diblokir Sejak</th>
                                    <th class="py-4 px-6 bg-gray-50 text-xs font-black text-gray-500 uppercase tracking-wider border-b border-gray-200 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($blacklists as $index => $item)
                                    <tr class="hover:bg-red-50/30 transition-colors">
                                        <td class="py-4 px-6 text-sm text-gray-500 font-medium">{{ $index + 1 }}</td>
                                        <td class="py-4 px-6 text-sm font-bold text-gray-800">{{ $item->phone_number }}</td>
                                        <td class="py-4 px-6 text-sm text-gray-500">{{ $item->created_at->format('d M Y - H:i') }}</td>
                                        <td class="py-4 px-6 text-sm text-right space-x-2">
                                            
                                            <button onclick="openEditModal({{ $item->id }}, '{{ $item->phone_number }}')" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 font-bold rounded-lg transition-colors text-xs">
                                                Edit
                                            </button>

                                            <form action="{{ route('member.blacklist.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membuka blokir nomor ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 font-bold rounded-lg transition-colors text-xs">
                                                    Hapus
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <div id="editModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center">
        <div class="relative mx-auto p-8 border w-full max-w-md shadow-2xl rounded-[2rem] bg-white">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-black text-gray-900 mb-2">Edit Nomor Blacklist</h3>
                <p class="text-xs text-gray-500 mb-4">Pastikan nomor yang diperbarui sudah benar.</p>
                
                <form id="editForm" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="text" id="edit_phone_number" name="phone_number" class="w-full border-gray-200 bg-gray-50 rounded-xl shadow-inner focus:ring-2 focus:ring-indigo-500 text-sm p-3.5" required>
                    
                    <div class="flex justify-end gap-3 mt-5">
                        <button type="button" onclick="closeEditModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-200 transition-colors">Batal</button>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl text-sm hover:bg-indigo-700 transition-colors shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(id, currentPhone) {
            // Isi input teks dengan nomor yang mau diedit
            document.getElementById('edit_phone_number').value = currentPhone;
            
            // Ubah tujuan URL action form-nya sesuai ID data yang dipilih
            document.getElementById('editForm').action = '/member/blacklist/' + id;
            
            // Munculkan Modal-nya
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            // Sembunyikan kembali Modal-nya
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</x-app-layout>