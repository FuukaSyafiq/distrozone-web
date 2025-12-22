

@php
    $user = filament()->auth()->user();
    $roleId = auth()->user()->role_id;
    use App\Models\RentedRoom;
    use App\Models\Room;
    use App\Models\Role;
    use App\Models\Tagihan;
    use Carbon\Carbon;
    use Filament\Notifications\Notification;

    // $penyewa = auth()->user();
    if (auth()->user()->role_id === Role::getidByRole('PENYEWA')) {
        $rentedRooms = RentedRoom::where('user_id', auth()->user()->id)->get();
        if (isset($rentedRooms)) {
            foreach ($rentedRooms as $rentedRoom) {
                // dd($rentedRoom);
                $room = Room::where('id', $rentedRoom->room_id)->first();
                $tagihanYangBelumDibayars = Tagihan::where('rented_room_id', $rentedRoom->id)
                    ->where('is_settled', false)
                    ->get();

                if (isset($tagihanYangBelumDibayars)) {
                    foreach ($tagihanYangBelumDibayars as $tagihanYangBelumDibayar) {
                        Notification::make()
                            ->title('Ada tagihan yang belum dibayar')
                            ->body(
                                "Harap segera melunasi tagihan $room->name sebelum tenggat waktu " .
                                    Carbon::parse($tagihanYangBelumDibayar->due_date)->translatedFormat('d F Y'),
                            )
                            ->warning()
                            ->send();
                    }
                }
            }
        }
    }

@endphp

<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3 w-full">
            <x-filament-panels::avatar.user size="lg" :user="$user" />

            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ __('filament-panels::widgets/account-widget.welcome', ['app' => config('app.name')]) }}
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ filament()->getUserName($user) }}
                </p>
            </div>

            <a href="/">
                <x-filament::button color="gray" labeled-from="sm" tag="button" type="button">
                    kembali ke beranda
                </x-filament::button>
            </a>
    </x-filament::section>
</x-filament-widgets::widget>
