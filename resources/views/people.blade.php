<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <!-- Styles -->
        @livewireStyles
        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
            [x-cloak] { display: none !important; }
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#F5F5F5]">
        <div class="my-8 mx-64">
            <div class="py-5">
                <h1 class="text-[32px] leading-[38px] font-bold">Top Secret CIA database</h1>
            </div>
            <livewire:people-table />
        </div>
        @livewireScripts
    </body>
</html>
