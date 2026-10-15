<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="tallstackui_darkTheme()">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <tallstackui:script />
    @livewireStyles
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased" x-bind:class="{ 'dark bg-dark-700': darkTheme, 'bg-white': ! darkTheme }">
    <x-dialog />
    <x-layout>
        <x-slot:header>
            <x-layout.header>
                <x-slot:left>
                    <x-badge :text="$title ?? ''" color="slate" light lg />
                </x-slot:left>

                {{-- <x-slot:middle>
                    <x-input icon="magnifying-glass" placeholder="Search" sm />
                </x-slot:middle> --}}

                <x-slot:right>
                    <x-dropdown>
                        <x-slot:action>
                            <x-avatar x-on:click="show = !show" text="{{ Auth::user()->initials() }}" gravatar-default="identicon" image="{{ Auth::user()->getAvatar()}}" />
                        </x-slot:action>
                        <x-slot:header>
                            <x-theme-switch block />
                        </x-slot:header>
                        <x-dropdown.items text="Profile" href="{{ route('profile.view') }}" wire:navigate />
                        <livewire:auth.logout />
                    </x-dropdown>
                </x-slot:right>
            </x-layout.header>
        </x-slot:header>

        <x-slot:menu>
            <x-side-bar collapsible thin-scroll>
                <x-slot:brand>
                    <div class="flex justify-center py-4">
                        <img src="{{ asset('logo.png') }}" class="h-10 w-10" />
                    </div>
                </x-slot:brand>
                <x-slot:brand-collapsed>
                    <div class="flex justify-center py-4">
                        <img src="{{ asset('logo.png') }}" class="h-8 w-8" />
                    </div>
                </x-slot:brand-collapsed>

                <x-side-bar.item text="Dashboard" icon="home" route="{{ route('dashboard') }}" wire:navigate :current="request()->routeIs('dashboard')" />

                {{-- <x-side-bar.item text="Notifications" icon="bell" route="#">
                    <x-slot:badge>5</x-slot:badge>
                </x-side-bar.item>
                <x-side-bar.item text="Messages" icon="envelope" badge-color="blue" route="#">
                    <x-slot:badge>3</x-slot:badge>
                </x-side-bar.item>

                <x-side-bar.separator text="Configurations" line /> --}}

                @canany(['CREATE_USER','DELETE_USER','UPDATE_USER'])
                <x-side-bar.item text="Accounts" icon="users">
                    @canany(['DELETE_USER','UPDATE_USER'])
                    <x-side-bar.item text="List" route="{{ route('accounts.table') }}" wire:navigate :current="request()->routeIs('accounts.table')" />
                    @endcanany
                    @can('CREATE_USER')
                    <x-side-bar.item text="New Account" route="{{ route('accounts.new') }}" wire:navigate :current="request()->routeIs('accounts.new')" />
                    @endcan
                </x-side-bar.item>
                @endcan

                @canany(['CREATE_ROLE','DELETE_ROLE','UPDATE_ROLE'])
                <x-side-bar.item text="Roles" icon="adjustments-horizontal">
                    @canany(['DELETE_ROLE','UPDATE_ROLE'])
                    <x-side-bar.item text="List" route="{{ route('roles.table') }}" wire:navigate :current="request()->routeIs('roles.table')" />
                    @endcanany
                    @can('CREATE_ROLE')
                    <x-side-bar.item text="New Role" route="{{ route('roles.new') }}" wire:navigate :current="request()->routeIs('roles.new')" />
                    @endcan
                </x-side-bar.item>
                @endcan

                <x-slot:footer>
                    <p class="text-sm text-gray-500">v5.0.0</p>
                </x-slot:footer>
            </x-side-bar>
        </x-slot:menu>

        <section>
            {{ $slot }}
        </section>
    </x-layout>
    @livewireScripts
</body>

</html>
