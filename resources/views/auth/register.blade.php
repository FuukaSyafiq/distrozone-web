<x-header />

<div class="max-w-4xl mx-auto my-3 bg-white border border-gray-200 rounded-lg shadow-md">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-2xl font-bold text-center">Daftar</h2>
    </div>
    <div class="p-6">
        <form method="POST" action="/register" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="name" type="text" name="name" class="form-input block w-full"
                        placeholder="Contoh: Andi Nugroho" required/>
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" class="form-input block w-full"
                        placeholder="Contoh: email@gmail.com" required/>
                </div>

                <div class="space-y-2">
                    <label for="contact" class="block text-sm font-medium text-gray-700">No WA/telp</label>
                    <input id="contact" name="contact" type="text" class="form-input block w-full"
                        placeholder="Input no telpon"  required/>
                </div>

                <div class="space-y-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                    <input id="address" name="address" type="text" class="form-input block w-full"
                        placeholder="Alamat" required />
                </div>

                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" class="form-input block w-full" placeholder=""
                       required />
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">konfirmasi
                        password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        class="form-input block w-full" placeholder="" required />
                </div>

                <div class="space-x-4 space-y-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white mt-4"
                        for="file_input">Upload
                        KTP</label>
                    <input
                        class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                        id="file_input" required name="ktp" type="file">
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

<script></script>
