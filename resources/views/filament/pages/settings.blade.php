<x-filament-panels::page>
<form wire:submit="save">
		{{ $this->form }}
	
		<div class="mt-6">
			{{-- Ini untuk memunculkan tombol Save yang kamu buat di getFormActions --}}
			@foreach($this->getFormActions() as $action)
			{{ $action }}
			@endforeach
		</div>
	</form>
</x-filament-panels::page>
