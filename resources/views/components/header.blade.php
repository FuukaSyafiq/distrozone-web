@props(['user' => auth()->user()])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{config('app.name')}}</title>
    <!-- Fonts -->
    <style>
    </style>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
<style>
body {
    background: #EEEEEE;
}

/* remove 'input type number' arrow*/
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    display: none;
    margin: 0;
}
</style>

<nav class="w-full mt-2 border-gray-200 dark:bg-gray-900 rounded-md text-white">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto">
        <a href="{{ route('index') }}" class="flex w-1/5 items-center">
            <span class="self-center text-2xl text-lime-600 font-semibold whitespace-nowrap ml-1">{{config('app.name')}}</span>
        </a>

        {{-- sidebar for responsive (Phone screen) --}}
        <x-header-sidebar />

        <div class="hidden w-4/5 md:flex  justify-end items-center" id="navbar-default">
            <ul class="mr-1 font-medium justify-end w-full items-center flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg  md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
              
                @if ($user)
                @if ($user->role_id == \App\Models\Role::getIdByRole('PENYEWA'))
                <li>
                    <a href="{{ url('/penyewa') }}" class="text-white bg-lime-400 hover:bg-lime-500  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center" aria-current="page">Kamar Anda</a>
                </li>
                @endif

                @if ($user->role_id == \App\Models\Role::getIdByRole('OWNER'))
                <li>
                    <a href="{{ url('/owner') }}" class="text-white bg-lime-400 hover:bg-lime-500  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center" aria-current="page">Kelola KOS</a>
                </li>
                @endif

                <!-- user profile start -->
                <div class="flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <button type="button" class="flex text-sm bg-white rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600 border-solid border-2 border-gray-950" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                        <span class="sr-only">Open user menu</span>
                        <img class="w-8 h-8 rounded-full" src="/assets/images/profile.svg" alt="user photo">
                    </button>
                    <!-- Dropdown menu -->
                    <div class="bg-amber-100 z-50 hidden my-4 text-base list-none    divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                        <div class="px-4 py-3">
                            <span class="block text-md text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                            <span class="block text-md  text-gray-500 truncate dark:text-gray-400">{{ Auth::user()->email }}</span>
                        </div>
                        <ul class="py-2" aria-labelledby="user-menu-button">
                            <li>
                                <form action="{{ route('logout') }}" method="post">
                                    @csrf
                                    <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white" onclick="event.preventDefault();                                                                                                         this.closest('form').submit();">
                                        Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- user profile end -->
                @elseif (!$user)
                <li>

                    <a href="{{ route('login') }}">
                        <button id="dropdownDefaultButton" class="text-white bg-lime-400 hover:bg-lime-500  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Login
                        </button>
                    </a>

                </li>
                <li>
                    <a href="{{ url('/register') }}">
                        <button id="dropdownDefaultButton" class="text-white bg-lime-400 hover:bg-lime-500  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Register
                        </button>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>