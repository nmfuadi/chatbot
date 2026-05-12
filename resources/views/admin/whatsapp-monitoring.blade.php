<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('📡 Status Mesin WhatsApp (Evolution API)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 shadow-sm" role="alert">
                    <p class="font-bold">Berhasil</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-4 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 shadow-sm" role="alert">
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
                                                    Nama Instance
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
                                                    $name = $item['instance']['instanceName'] ?? 'Unknown';
                                                    $status = $item['instance']['status'] ?? 'close';
                                                @endphp
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                        {{ $name }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($status === 'open')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                <span class="w-2 h-2 mr-1 bg-green-500 rounded-full animate-pulse"></span> Terhubung
                                                            </span>
                                                        @elseif($status === 'connecting')
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                <span class="w-2 h-2 mr-1 bg-yellow-500 rounded-full"></span> Menunggu...
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                <span class="w-2 h-2 mr-1 bg-red-500 rounded-full"></span> Terputus
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                                        @if($status !== 'open')
                                                            <button onclick="showQr('{{ $name }}')" class="inline-flex items-center px-3 py-1 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                                📷 Scan QR
                                                            </button>
                                                        @endif

                                                        <form action="{{ route('wa.restart', $name) }}" method="POST" class="inline">
                                                            @csrf @method('PUT')
                                                            <button type="submit" onclick="return confirm('Restart mesin ini?')" class="inline-flex items-center px-3 py-1 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none transition ease-in-out duration-150">
                                                                🔄 Restart
                                                            </button>
                                                        </form>

                                                        @if($status === 'open')
                                                            <form action="{{ route('wa.logout', $name) }}" method="POST" class="inline">
                                                                @csrf @method('DELETE')
                                                                <button type="submit" onclick="return confirm('Yakin ingin logout paksa?')" class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none transition ease-in-out duration-150">
                                                                    🚪 Logout
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 italic">
                                                        Tidak ada mesin yang ditemukan.
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
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Scan QR Code - <span id="modalInstanceName" class="text-indigo-600"></span>
                            </h3>
                            <div class="mt-4 flex flex-col items-center justify-center min-h-[300px]">
                                <div id="qrLoading" class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                                
                                <img id="qrImage" src="" alt="QR Code" class="hidden shadow-md rounded-lg border p-2" style="max-width: 280px;">
                                
                                <p id="qrError" class="hidden text-red-600 text-sm mt-3 font-semibold">Gagal memuat QR Code. Pastikan instance dalam kondisi siap.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
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
            
            // Reset state
            document.getElementById('qrImage').classList.add('hidden');
            document.getElementById('qrError').classList.add('hidden');
            document.getElementById('qrLoading').classList.remove('hidden');
            
            // Show modal
            modal.classList.remove('hidden');

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