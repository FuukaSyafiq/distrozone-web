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
    public $kota;
    public $kotaSelected;
    public $provinsi;
    public $email;
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
        $this->kotaSelected = $user->kota_id ? $user->kota_id : null;
        $this->provinsi = $user->kota?->provinsi?->provinsi ?? "";

        $this->email = $user->email;
        $this->no_telepon = $user->no_telepon;
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

        $user->update($data);

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
