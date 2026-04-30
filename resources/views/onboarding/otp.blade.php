<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - Smart AI Assistant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">
    <div class="max-w-md w-full bg-white p-8 rounded-xl shadow-lg border border-gray-100 text-center">
        <div class="mb-6">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 3m0 0a10.003 10.003 0 0111.412 14.286l.054.09m-4.27 1.121L17 18m8-6H3m14-6v12M7 11V7a5 5 0 0110 0v4"></path>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Verifikasi WhatsApp</h1>
            <p class="text-gray-500 mt-2">Masukkan 6 digit kode OTP yang kami kirimkan ke nomor WhatsApp Anda.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('onboarding.otp.verify') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <input type="text" name="otp_code" maxlength="6" required 
                       class="w-full text-center text-3xl font-bold tracking-widest px-4 py-3 border-2 border-gray-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none" 
                       placeholder="000000">
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 px-4 rounded-md hover:bg-blue-700 transition duration-200">
                Verifikasi Sekarang
            </button>
        </form>

        <p class="mt-8 text-sm text-gray-500">
            Tidak menerima kode? <a href="{{ route('onboarding.profile.form') }}" class="text-blue-600 font-semibold hover:underline">Kirim ulang</a>
        </p>
    </div>
</body>
</html>