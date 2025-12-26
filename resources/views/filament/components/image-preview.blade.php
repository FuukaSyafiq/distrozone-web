
@if ($image)
    <div class="flex justify-center flex-col">
		<h3 class="mb-4">Preview Foto</h3>
		<div class="flex justify-center flex-col max-w-3/5">
			<img
				src="{{ Storage::disk('s3')->url($image->path) }}"
				alt="Preview"
				class="rounded-xl max-h-48 object-cover border"
			/>
		</div>
    </div>
@endif
