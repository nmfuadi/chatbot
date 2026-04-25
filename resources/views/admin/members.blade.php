<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Member') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 overflow-x-auto">

                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-100 border-b">
                                <th class="p-3">Nama / Email</th>
                                <th class="p-3">Status User</th>
                                <th class="p-3">Bukti Bayar</th>
                                <th class="p-3">Setup API Wablas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($members as $member)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">
                                    <div class="font-bold">{{ $member->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $member->email }}</div>
                                    <div class="text-sm text-gray-500">{{ $member->phone }}</div>
                                </td>
                                
                                <td class="p-3">
                                    <span class="px-2 py-1 text-xs rounded {{ $member->status == 'active' ? 'bg-green-200 text-green-800' : 'bg-yellow-200 text-yellow-800' }}">
                                        {{ strtoupper($member->status) }}
                                    </span>
                                </td>

                                <td class="p-3 text-sm">
                                    @php $lastPayment = $member->payments->last(); @endphp
                                    @if($lastPayment)
                                        <a href="{{ asset('storage/' . $lastPayment->proof_image) }}" target="_blank" class="text-blue-600 underline">Lihat Bukti</a>
                                        <br>
                                        @if($lastPayment->status == 'pending')
                                            <form action="{{ route('admin.payment.approve', $lastPayment->id) }}" method="POST" class="mt-2">
                                                @csrf
                                                <button type="submit" class="bg-indigo-600 text-white px-2 py-1 rounded text-xs hover:bg-indigo-700">Approve</button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="text-gray-400">Belum ada</span>
                                    @endif
                                </td>

                                <td class="p-3">
                                    <form action="{{ route('admin.members.wablas', $member->id) }}" method="POST" class="flex flex-col gap-2 max-w-xs">
                                        @csrf
                                        <input type="text" name="wablas_api_key" value="{{ $member->wablas_api_key }}" placeholder="API Key" class="text-xs border-gray-300 rounded">
                                        <input type="text" name="wablas_secret_key" value="{{ $member->wablas_secret_key }}" placeholder="Secret Key" class="text-xs border-gray-300 rounded">
                                        <input type="text" name="wablas_device_id" value="{{ $member->wablas_device_id }}" placeholder="Device ID" class="text-xs border-gray-300 rounded">
                                        <button type="submit" class="bg-gray-800 text-white px-2 py-1 rounded text-xs hover:bg-gray-700">Save Wablas</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>