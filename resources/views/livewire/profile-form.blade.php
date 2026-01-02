<div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 w-full">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Personal Information</h2>
        <button wire:click="edit" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 font-medium">
            Edit
        </button>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
            <input type="text" wire:model="nama" {{ $editing ? '' : 'disabled' }} value="{{ $nama }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="flex items-center justify-between text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                <span>Email Address</span>
            
                @if($email_verified_at)
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                        bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400">
                        ✔ Verified
                    </span>
                @else
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                        bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400">
                        ⚠ Unverified
                    </span>
                @endif
            </label>
            
            <input type="email" wire:model="email" {{ $editing ? '' : 'disabled' }} value="{{ $email }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
            <input type="tel" wire:model="no_telepon" {{ $editing ? '' : 'disabled' }} value="{{ $no_telepon }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Alamat</label>
            <input type="text" wire:model="alamat" {{ $editing ? '' : 'disabled' }} value="{{ $alamat }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">NIK</label>
            <input type="text" wire:model="nik" {{ $editing ? '' : 'disabled' }} value="{{ $nik }}" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
        </div>
    </div>
    @if($editing)
    <div class="w-full justify-end flex">
    <button wire:click="save" class="px-6 py-2 mt-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow-md transition">
        Save
        </button>
        @endif
    </div>
    @if (session()->has('success'))
    <div
        x-data="{ show: true }"
        x-init="setTimeout(() => show = false, 3000)"
        x-show="show"
        class="fixed bottom-6 right-6 z-50 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg"
    >
        {{ session('success') }}
    </div>
@endif

</div>