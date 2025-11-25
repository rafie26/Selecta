@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Profil Saya</h1>
        <p class="text-gray-600">Kelola informasi akun dan pengaturan privasi Anda</p>
    </div>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg relative" role="alert">
            <div class="flex items-start">
                <div class="flex-1">
                    <p class="font-semibold text-red-800">Terjadi kesalahan!</p>
                    <ul class="mt-2 list-disc list-inside text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" class="text-red-500 hover:text-red-700 ml-4" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-lg relative" role="alert">
            <div class="flex items-start justify-between">
                <p class="text-green-800">{{ session('success') }}</p>
                <button type="button" class="text-green-500 hover:text-green-700 ml-4" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-6 overflow-hidden">
        <div class="p-8">
            <!-- Avatar Section -->
            <div class="text-center mb-8 pb-8 border-b border-gray-200">
                <div class="mb-4">
                    @if ($user->avatar && filter_var($user->avatar, FILTER_VALIDATE_URL))
                        <!-- Google Avatar -->
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" 
                             class="w-32 h-32 rounded-full object-cover mx-auto ring-4 ring-blue-100">
                    @elseif ($user->avatar)
                        <!-- Uploaded Avatar -->
                        <img src="{{ Storage::url($user->avatar) }}" alt="{{ $user->name }}" 
                             class="w-32 h-32 rounded-full object-cover mx-auto ring-4 ring-blue-100">
                    @else
                        <!-- Default Avatar -->
                        <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mx-auto ring-4 ring-blue-100">
                            <span class="text-white font-bold text-5xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-1">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->email }}</p>
            </div>

            <!-- Edit Profile Form -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap
                    </label>
                    <input type="text" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('name') border-red-500 @enderror" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $user->name) }}" 
                           required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('email') border-red-500 @enderror" 
                           id="email" 
                           name="email" 
                           value="{{ old('email', $user->email) }}" 
                           required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Field -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor Telepon
                    </label>
                    <input type="tel" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('phone') border-red-500 @enderror" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone', $user->phone) }}" 
                           placeholder="Contoh: 081234567890">
                    @error('phone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Avatar Upload -->
                <div>
                    <label for="avatar" class="block text-sm font-semibold text-gray-700 mb-2">
                        Foto Profil
                    </label>
                    <div class="flex items-center space-x-4">
                        <label for="avatar" class="flex-1 cursor-pointer">
                            <div class="flex items-center justify-center px-4 py-3 border-2 border-dashed border-gray-300 rounded-lg hover:border-blue-500 transition">
                                <i class="fas fa-upload text-gray-400 mr-2"></i>
                                <span class="text-gray-600">Pilih Foto</span>
                            </div>
                            <input type="file" 
                                   class="hidden @error('avatar') border-red-500 @enderror" 
                                   id="avatar" 
                                   name="avatar" 
                                   accept="image/*" 
                                   onchange="previewImage(event)">
                        </label>
                    </div>
                    @error('avatar')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-sm text-gray-500">
                        Format: JPG, PNG, GIF (Maksimal 2MB)
                    </p>
                    <div id="preview-container" class="mt-4 hidden">
                        <img id="preview-image" 
                             src="" 
                             alt="Preview" 
                             class="rounded-lg max-w-xs max-h-48 object-cover border-2 border-gray-200">
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-xl shadow-lg border-2 border-red-500 overflow-hidden">
        <div class="bg-red-50 border-b-2 border-red-500 px-6 py-4">
            <h3 class="text-lg font-bold text-red-600 flex items-center">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Zona Berbahaya
            </h3>
        </div>
        <div class="p-6">
            <p class="text-gray-700 mb-4">
                Menghapus akun Anda akan menghapus semua data secara permanen. Tindakan ini tidak dapat dibatalkan.
            </p>
            <button type="button" 
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition duration-200 flex items-center"
                    onclick="document.getElementById('deleteAccountModal').classList.remove('hidden')">
                <i class="fas fa-trash mr-2"></i>
                Hapus Akun
            </button>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div id="deleteAccountModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full overflow-hidden">
        <!-- Modal Header -->
        <div class="bg-red-50 border-b-2 border-red-500 px-6 py-4 flex items-center justify-between">
            <h3 class="text-xl font-bold text-red-600">Hapus Akun</h3>
            <button type="button" 
                    class="text-gray-500 hover:text-gray-700"
                    onclick="document.getElementById('deleteAccountModal').classList.add('hidden')">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <p class="text-gray-700 mb-4">
                Anda yakin ingin menghapus akun? Semua data Anda akan dihapus secara permanen.
            </p>
            
            <form id="deleteAccountForm" action="{{ route('profile.destroy') }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Masukkan Password untuk Konfirmasi
                    </label>
                    <input type="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition" 
                           id="password" 
                           name="password" 
                           required>
                    <p class="mt-2 text-sm text-gray-500">
                        Kami memerlukan password Anda untuk mengonfirmasi penghapusan akun.
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="flex space-x-3">
                    <button type="button" 
                            class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg transition"
                            onclick="document.getElementById('deleteAccountModal').classList.add('hidden')">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center">
                        <i class="fas fa-trash mr-2"></i>
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').src = e.target.result;
            document.getElementById('preview-container').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

// Close modal when clicking outside
document.getElementById('deleteAccountModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
</script>
@endsection