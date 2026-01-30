<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationLabel = 'Pengaturan Akun';
    protected static ?string $title = 'Pengaturan Akun';

    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'nama' => auth()->user()->nama,
            'email' => auth()->user()->email,
            'no_telepon' => auth()->user()->no_telepon,
            'foto_user' => auth()->user()->foto_user

        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Keamanan Akun')
                    ->description('Pastikan gunakan password yang kuat.')
                    ->schema([
                        TextInput::make('nama')->label('Nama'),
                        TextInput::make('email')->label('Email'),
                        TextInput::make('no_telepon')->label('No telepon'),
                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->required()
                            ->currentPassword(), // Validasi otomatis password lama

                        TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->minLength(8)
                            ->same('new_password_confirmation'),

                        TextInput::make('new_password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password(),
                        FileUpload::make('foto_user')->label('Foto')->disk('s3')->image()
                    ->imageEditor()

            ])->columns(1),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Update')->color('primary')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        // Mengambil data dari form
        $data = $this->form->getState();
        $user = auth()->user();

        // 1. Siapkan data yang pasti diupdate (Profil)
        $updateData = [
            'nama'       => $data['nama'],
            'email'      => $data['email'],
            'no_telepon' => $data['no_telepon'],
            'foto_user'  => $data['foto_user'],
        ];

        // 2. Logika Update Password (Hanya jika password baru diisi)
        if (filled($data['new_password'])) {
            $updateData['password'] = Hash::make($data['new_password']);
        }

        // 3. Eksekusi Update ke Database
        $user->update($updateData);

        // 4. Refresh form agar data terbaru tetap muncul (terutama foto)
        $this->form->fill($data);

        // Khusus password, kita kosongkan lagi fieldnya di UI setelah sukses
        $this->data['current_password'] = null;
        $this->data['new_password'] = null;
        $this->data['new_password_confirmation'] = null;

        Notification::make()
            ->title('Profil berhasil diperbarui!')
            ->success()
            ->send();
    }
}
