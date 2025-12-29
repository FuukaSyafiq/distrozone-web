
@if ($images)

<h3 class="mb-4">Preview Foto</h3>
<div class="flex justify-around gap-5 max-h-4/5">
		@foreach ($images as $image)
			<img
				src="{{ Storage::disk('s3')->url($image->path) }}"
				alt="Preview"
				class="rounded-lg w-32 object-cover border"
			/>
		@endforeach
</div>
@endif
