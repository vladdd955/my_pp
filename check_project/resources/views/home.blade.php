<x-app-layout>
    <!-- resources/views/home.blade.php -->

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <body>
    <div class="container">
        <h1 style="color: #9ca3af">Welcome to the Home Page</h1>
        @foreach($tt as $val)
            <p>{{$val}}</p>
        @endforeach
    </div>
    </body>

</x-app-layout>
