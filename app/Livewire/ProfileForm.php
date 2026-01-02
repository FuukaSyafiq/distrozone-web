<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;

class ProfileForm extends Component
{
    public $nama;
    public $alamat;
    public $no_telepon;
    public $email;
    public $nik;
    public $editing = false;
    public $userId;
    public $email_verified_at;

    public function mount()
    {
        $user = auth()->user();
        $this->email_verified_at = $user->email_verified_at;
        $this->userId = auth()->id();
        $this->nama = $user->nama;
        $this->alamat = $user->alamat;
        $this->email = $user->email;
        $this->nik = $user->nik;
        $this->no_telepon = $user->no_telepon;
    }

    public function save()
    {
        $user = auth()->user();
        $emailChanged = $user->email !== $this->email;
        auth()->user()->update([
            'nama' => $this->nama,
            'email' => $this->email,
            'alamat' => $this->alamat,
            'no_telepon' => $this->no_telepon,
            'nik' => $this->nik
        ]);

        $this->editing = false;
        if ($emailChanged) {
            $user->forceFill([
                'email_verified_at' => null,
            ])->save();

            $user->sendEmailVerificationNotification();
            $this->dispatch('toast', message: 'Please read your email for verification new email');
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
