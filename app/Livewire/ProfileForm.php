<?php

namespace App\Livewire;

use App\Helpers\NikVerified;
use App\Models\Kota;
use App\Models\User;
use Livewire\Component;

class ProfileForm extends Component
{
    public $nama;
    public $alamat_lengkap;
    public $editing = false;
    public $userId;
    public $no_telepon;
    public $nik;
    public $kota;
    public $kotaSelected;
    public $provinsi;
    public $email;
    public $nik_verified;
    public $email_verified_at;


    public function mount()
    {
        $user = auth()->user();
        $this->userId = auth()->id();
        $this->nama = $user->nama;
        $this->alamat_lengkap = $user->alamat_lengkap;
        $this->email_verified_at = $user->email_verified_at;
        $this->userId = auth()->id();
        $this->kota = Kota::all();
        $this->kotaSelected = $user->kota_id;
        $this->provinsi = $user->kota->provinsi->provinsi;
        $this->email = $user->email;
        $this->no_telepon = $user->no_telepon;
        $this->nik_verified = $user->nik_verified;
        $this->nik = $user->nik;
    }

    public function updatedKotaSelected($id)
    {
        $dataKota = Kota::with('provinsi')->find($id);
        if ($dataKota) {
            $this->provinsi = $dataKota->provinsi->provinsi;
        }
    }

    public function save()
    {
        $user = auth()->user();
        $emailChanged = $user->email !== $this->email;
        $nikChanged   = $user->nik !== $this->nik;
        $data = [
            'nama'        => $this->nama,
            'email'       => $this->email,
            'alamat_lengkap'      => $this->alamat_lengkap,
            'kota_id' => $this->kotaSelected,
            'no_telepon'  => $this->no_telepon,
        ];

        /**
         * NIK RULE
         */
        $canEditNik = in_array(
            $user->nik_verified,
            [NikVerified::EMPTY, NikVerified::REJECTED]
        );

        if ($nikChanged) {
            if (! $canEditNik) {
                $this->dispatch('toast', message: 'NIK tidak dapat diubah saat sedang diverifikasi atau sudah disetujui');
                return;
            }

            $data['nik'] = $this->nik;
            $data['nik_verified'] = NikVerified::PENDING;
        }

        $user->update($data);

        if ($user->nik_verified !== NikVerified::APPROVED) {
            match ($user->nik_verified) {
                NikVerified::EMPTY    => $this->dispatch('toast', message: 'Silakan isi NIK'),
                NikVerified::PENDING  => $this->dispatch('toast', message: 'NIK sedang diverifikasi'),
                NikVerified::REJECTED => $this->dispatch('toast', message: 'NIK ditolak, silakan perbaiki'),
                default => null,
            };
        }

        $this->editing = false;
        if ($emailChanged) {
            $user->forceFill([
                'email_verified_at' => null,
            ])->save();

            $user->sendEmailVerificationNotification();
            $this->dispatch('toast', message: 'Please read your email for new email verification');
            return;
        }


        $this->dispatch('toast', message: 'Profile updated');
    }

    public function edit()
    {
        $this->editing = true;
    }

    public function render()
    {
        return view('livewire.profile-form');
    }
}
