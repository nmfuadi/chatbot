<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📡 Status Mesin WhatsApp (Evolution API)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm rounded-r-md" role="alert">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 shadow-sm rounded-r-md" role="alert">
                    <p class="font-bold">Perhatian</p>
                    <p>{{ session('warning') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Info Mesin
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Status Koneksi
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Aksi
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @forelse($instances as $item)
                                                @php
                                                    // 1. Ambil Nama Instance
                                                    $name = $item['name'] ?? 'Unknown';

                                                    // 2. Ambil Status Koneksi
                                                    $rawStatus = $item['connectionStatus'] ?? 'close';
                                                    $status = strtolower($rawStatus);

                                                    // 3. Ambil Foto Profil (Jika kosong, buat avatar dari inisial nama)
                                                    $profilePic = $item['profilePicUrl'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random';
                                                    
                                                    // 4. Ekstrak Nomor HP dari ownerJid
                                                    $ownerJid = $item['ownerJid'] ?? '';
                                                    $phone = $ownerJid ? explode('@', $ownerJid)[0] : 'Belum Login';
                                                @endphp
                                                
                                                <tr class="hover:bg-gray-50 transition duration-150">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center">
                                                            <div class="flex-shrink-0 h-12 w-12">
                                                                <img class="h-12 w-12 rounded-full object-cover border border-gray-200 shadow-sm" src="{{ $profilePic }}" alt="Profile {{ $name }}">
                                                            </div>
                                                            <div class="ml-4">
                                                                <div class="text-sm font-bold text-gray-900">
                                                                    {{ $name }}
                                                                </div>
                                                                <div class="text-xs text-gray-500 font-mono mt-0.5">
                                                                    📞 {{ $phone }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($status === 'open')
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 border border-green-200">
                                                                <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full animate-pulse"></span> Terhubung
                                                            </span>
                                                        @elseif($status === 'connecting')
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800 border border-yellow-200">
                                                                <span class="w-2 h-2 mr-1.5 bg-yellow-500 rounded-full"></span> Menunggu...
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
                                                                <span class="w-2 h-2 mr-1.5 bg-red-500 rounded-full"></span> Terputus
                                                            </span>
                                                        @endif
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                        @if($status !== 'open')
                                                            <button onclick="showQr('{{ $name }}')" class="inline-flex items-center px-3 py-1.5 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                                                📷 Scan QR
                                                            </button>
                                                        @endif

                                                        <form action="{{ route('wa.restart', $name) }}" method="POST" class="inline">
                                                            @csrf @method('PUT')
                                                            <button type="submit" onclick="return confirm('Restart mesin ini? Ini akan memutuskan koneksi sementara.')" class="inline-flex items-center px-3 py-1.5 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                                                🔄 Restart
                                                            </button>
                                                        </form>

                                                        @if($status === 'open')
                                                            <form action="{{ route('wa.logout', $name) }}" method="POST" class="inline">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" onclick="return confirm('Yakin ingin logout paksa dari WhatsApp? Anda harus scan ulang QR nanti.')" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm">
                                                                    🚪 Logout
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="px-6 py-10 text-center text-sm text-gray-500">
                                                        <div class="flex flex-col items-center justify-center">
                                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                            <span class="italic text-gray-400">Tidak ada mesin WhatsApp yang ditemukan.</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-xl text-left shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 rounded-t-xl">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 border-b pb-3" id="modal-title">
                                Scan QR Code - <span id="modalInstanceName" class="text-indigo-600"></span>
                            </h3>
                            <div class="mt-5 flex flex-col items-center justify-center min-h-[250px]">
                                <div id="qrLoading" class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                                
                                <img id="qrImage" src="" alt="QR Code" class="hidden shadow-sm rounded-lg border-2 border-gray-100 p-2" style="max-width: 250px;">
                                
                                <p id="qrError" class="hidden text-red-600 text-sm mt-3 font-semibold bg-red-50 p-2 rounded w-full text-center">Gagal memuat QR Code. Mesin mungkin sedang restart.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse rounded-b-xl">
                    <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showQr(instanceName) {
            const modal = document.getElementById('qrModal');
            document.getElementById('modalInstanceName').innerText = instanceName;
            
            // Reset Tampilan Modal
            document.getElementById('qrImage').classList.add('hidden');
            document.getElementById('qrError').classList.add('hidden');
            document.getElementById('qrLoading').classList.remove('hidden');
            
            // Tampilkan Modal
            modal.classList.remove('hidden');

            // Ambil QR dari Controller
            fetch(`/admin/whatsapp/qr/${instanceName}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('qrLoading').classList.add('hidden');
                    if(data.base64) {
                        document.getElementById('qrImage').src = data.base64;
                        document.getElementById('qrImage').classList.remove('hidden');
                    } else {
                        document.getElementById('qrError').classList.remove('hidden');
                    }
                })
                .catch(() => {
                    document.getElementById('qrLoading').classList.add('hidden');
                    document.getElementById('qrError').classList.remove('hidden');
                });
        }

        function closeModal() {
            document.getElementById('qrModal').classList.add('hidden');
        }
    </script>
</x-app-layout>