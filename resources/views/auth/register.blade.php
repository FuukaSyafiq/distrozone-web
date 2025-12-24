<x-app-layout>

    <div class="max-w-4xl mx-auto my-3 bg-white border border-gray-200 rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-center">Daftar</h2>
    </div>
    <div class="p-6">
        <form method="POST" action="/register" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="nama" type="text" name="nama"  value="{{ old('nama') }}" class="form-input block w-full"
                        placeholder="Andi Nugroho" required/>
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                           
                </div>

                <div class="space-y-2">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input id="username" type="text" name="username" class="form-input block w-full"  value="{{ old('username') }}"
                        placeholder="andi_nugroho" required oninput="this.value = this.value.toLowerCase()"/>
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />

                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" class="form-input block w-full"  value="{{ old('email') }}"
                        placeholder="andi21321@gmail.com" required oninput="this.value = this.value.toLowerCase()" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

                </div>

                <div class="space-y-2">
                    <label for="nik" class="block text-sm font-medium text-gray-700">NIK</label>
                    <input id="nik" type="string" name="nik" class="form-input block w-full"  value="{{ old('nik') }}"
                        placeholder="32365462342342" required />
                        <x-input-error :messages="$errors->get('nik')" class="mt-2" />

                </div>

                <div class="space-y-2">
                    <label for="no_telepon" class="block text-sm font-medium text-gray-700">No WA/telp</label>
                    <input id="no_telepon" name="no_telepon" type="text" class="form-input block w-full"  value="{{ old('no_telepon') }}"
                        placeholder="Input no telpon"  required/>
                        <x-input-error :messages="$errors->get('no_telepon')" class="mt-2" />

                </div>

                <div class="space-y-2">
                    <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                    <input id="alamat" name="alamat" type="text" class="form-input block w-full"  value="{{ old('alamat') }}"
                        placeholder="Alamat" required />
                        <x-input-error :messages="$errors->get('alamat')" class="mt-2" />

                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" class="form-input block w-full" placeholder=""
                       required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />

                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">konfirmasi
                        password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        class="form-input block w-full" placeholder="" required />

                </div>
            </div>

            <div class="p-6 border-t border-gray-200">
                <button type="submit"
                    class="w-full py-2 px-4 text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm">
                    Sign up
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>