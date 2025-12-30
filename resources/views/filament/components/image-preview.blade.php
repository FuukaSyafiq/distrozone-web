{{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
@if ($images && $images->count() > 0)
<div class="mb-6">
    <h3 class="mb-4 text-lg font-semibold text-gray-800">Preview Foto</h3>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
        @foreach ($images as $image)
            <div class="relative group">
                <!-- Image Container -->
                <div class="relative overflow-hidden rounded-lg border-2 border-gray-200 hover:border-gray-300 transition-colors">
                    <img
                        src="{{ Storage::disk('s3')->url($image->path) }}"
                        alt="Preview {{ $loop->iteration }}"
                        class="w-full h-32 object-cover"
                        loading="lazy"
                    />
                    <button
                    type="button"
                    onclick="confirmDelete({{ $image->id }})"
                    class="-top-2 -right-2 bg-red-500 text-white w-7 h-7 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-600 shadow-lg hover:shadow-xl transform hover:scale-110"
                    aria-label="Hapus foto"
                >
                hapus
                    {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg> --}}
                </button>
                    <!-- Overlay on hover -->
                    {{-- <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-200"></div> --}}
                </div>

                <!-- Delete Button -->
              

                <!-- Hidden Form -->
                <form
                    id="delete-form-{{ $image->id }}"
                    action="{{ route('images.destroy', $image->id) }}"
                    method="POST"
                    class="hidden"
                >
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @endforeach
    </div>
</div>

<!-- Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            
            <!-- Content -->
            <h3 class="text-lg leading-6 font-medium text-gray-900 mt-5">Hapus Foto</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">
                    Apakah Anda yakin ingin menghapus foto ini? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>
            
            <!-- Buttons -->
            <div class="flex gap-3 px-4 py-3">
                <button
                    onclick="closeModal()"
                    type="button"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-300"
                >
                    Batal
                </button>
                <button
                    onclick="submitDelete()"
                    type="button"
                    class="flex-1 px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500"
                >
                    Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentDeleteId = null;

function confirmDelete(imageId) {
    currentDeleteId = imageId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    currentDeleteId = null;
}

function submitDelete() {
    if (currentDeleteId) {
        document.getElementById('delete-form-' + currentDeleteId).submit();
    }
}

// Close modal when clicking outside
document.getElementById('deleteModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endif