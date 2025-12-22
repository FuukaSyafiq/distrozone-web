<x-header :user="$user" />

<style>
    #mainContent {
        background-image: url('/assets/main-bg-awal.webp');
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }
</style>
<div class="min-h-screen flex flex-col">
    <main class="h-screen my-5 justify-center w-full items-center flex bg-green-500 text-white flex-col lg:flex-row"
        id="mainContent">
        <div class="w-full flex flex-col lg:flex-row items-center lg:justify-start justify-center">

            <div class="mr-5 flex w-full justify-center items-center flex-col lg:text-left">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold break-words">
                    Welcome To {{config('app.name')}}
                </h1>
                <p class="my-5 text-lg sm:text-xl md:text-2xl font-bolder font-poppins break-words">
                    Solusi Kos Ideal Anda.
                </p>
            </div>
        </div>

    </main>
    <x-footer />
</div>
