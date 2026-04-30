<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Bisnis - Smart AI Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Lengkapi Profil Bisnis</h1>
            <p class="text-gray-500 mt-2">Bantu AI mengenal bisnis Anda lebih baik.</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('onboarding.profile.submit') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Usaha</label>
                <input type="text" name="business_name" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Griya Permata Kost">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Kategori Bisnis</label>
                <select name="business_category" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Pilih Kategori</option>
                    <option value="Properti">Properti / Kost</option>
                    <option value="Retail">Retail / Toko</option>
                    <option value="Jasa">Jasa / Konsultasi</option>
                    <option value="Kuliner">Kuliner</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Alamat Bisnis</label>
                <input type="text" name="business_address" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Jl. Raya No. 123...">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Nomor WhatsApp (Untuk OTP & Bot)</label>
                <input type="number" name="whatsapp_number" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="628123456789">
                <p class="text-xs text-gray-400 mt-1">*Gunakan format 62 (Tanpa + atau 0)</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Deskripsi Singkat (SOP Awal AI)</label>
                <textarea name="business_description" rows="3" required class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan apa yang Anda tawarkan..."></textarea>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                Simpan & Kirim Kode OTP
            </button>
        </form>
    </div>
</body>
</html>