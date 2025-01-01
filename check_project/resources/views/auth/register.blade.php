<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Country -->
        <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" />
            <select id="country" name="country" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required
                    onchange="getCountryLanguage()">
                <option value="" disabled selected>{{ __('Select your country') }}</option>
                @foreach($countries as $value)
                    <option value="{{$value['country']}}">{{$value['country']}}</option>
                @endforeach
            </select>
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <!-- Language -->
        <div class="mt-4">
            <x-input-label for="language" :value="__('Language')" />
            <select id="language" name="language" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                <option value="" disabled selected>{{ __('Select your language') }}</option>
            </select>
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>


        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>

    function getCountryLanguage() {
        let country = $('#country').val();

        console.log(country)

        $.ajax({
            type: 'POST',
            url: '{{ route('getCountryJson') }}',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'country': country,

            },
            success: function (response) {
                console.log(response);

                let languageSelect = $('#language');
                languageSelect.empty();

                languageSelect.append('<option value="" disabled selected>{{ __('Select your language') }}</option>');

                if (response.success && response.languages) {
                    response.languages.forEach(function(language) {
                        languageSelect.append(`<option value="${language}">${language}</option>`);
                    });
                } else {
                    languageSelect.append('<option value="" disabled>{{ __('No languages available') }}</option>');
                }

            },
            error: function (xhr, status, error) {
                console.error('Error: ' + error);
            }
        });
    }


</script>
