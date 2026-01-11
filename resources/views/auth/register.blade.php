<x-app-layout>

    <div class="max-w-2xl mx-auto my-10 py-5 bg-white border border-gray-200 rounded-lg shadow-md">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-center">Daftar</h2>
        </div>
        <div class="p-6">
            <form method="POST" action="/register" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 gap-4">
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" type="email" name="email" class="form-input block w-full"
                            value="{{ old('email') }}" placeholder="andi21321@gmail.com" required
                            oninput="this.value = this.value.toLowerCase()" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />

                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" class="form-input block w-full"
                            placeholder="" required />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />

                    </div>

                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
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