<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <body>
    <div class="py-12 flex items-center justify-center">
        <div class="max-w-5xl w-full mx-auto sm:px-6 lg:px-8">

            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-dark-100">
                    <div class="flex justify-between items-center">
                        <button type="button" id="getUserRoleButton" class="btn btn-info bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" onclick="userRole()">Get User Role</button>
                    </div>
                    <div id="userRole" class="mt-4 text-sm text-center"></div>
                </div>
            </div>

            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-dark-100">
                    <form id="assignRoleForm">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="assignPermission" class="block font-medium text-gray-800 dark:text-white">Permission</label>
                            <select name="permission" id="assignPermission" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach ($permission as $key => $value)
                                    <option value="{{ $key }}" class="bg-gray-700 text-white">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" id="assignRoleButton" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="ConfirmRole()">Confirm</button>
                        </div>
                        <div id="assignResponse" class="mt-4 text-sm text-center"></div>
                    </form>
                </div>
            </div>

            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-dark-100">
                    <form id="removeRoleForm">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="removePermission" class="block font-medium text-gray-800 dark:text-white">Permission</label>
                            <select name="permission" id="removePermission" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach ($permission as $key => $value)
                                    <option value="{{ $key }}" class="bg-gray-700 text-white">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" id="removeRoleButton" class="btn btn-danger bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="DeleteRole()">Delete</button>
                        </div>
                        <div id="deleteResponse" class="mt-4 text-sm text-center"></div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        function ConfirmRole() {
            let assignPermission = $('#assignPermission').val();

            $.ajax({
                type: 'POST',
                url: '{{ route('confirmRole') }}',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'permission': assignPermission,

                },
                success: function(response) {
                    console.log(response['message']);
                    if (response['message']) {
                        $(`#assignResponse`).text(response.message).addClass('text-green-600');
                    } else if (response['error']) {
                        $(`#assignResponse`).text(response.error).addClass('text-red-600');

                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        }

        function DeleteRole() {
            let removePermission = $('#removePermission').val();

            $.ajax({
                type: 'POST',
                url: '{{ route('deleteRole') }}',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'permission': removePermission,
                },
                success: function(response) { deleteResponse
                    console.log(response);
                    if (response['message']) {
                        $(`#deleteResponse`).text(response.message).addClass('text-green-600');
                    } else if (response['error']) {
                        $(`#deleteResponse`).text(response.error).addClass('text-red-600');

                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        }

        function userRole() {
            $.ajax({
                type: 'GET',
                url: '{{ route('userRole') }}',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) { deleteResponse
                    console.log(response);
                    if (response['message']) {
                        $(`#userRole`).text(response.message).addClass('text-green-600');
                    } else if (response['error']) {
                        $(`#userRole`).text(response.error).addClass('text-red-600');

                    }

                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        }


    </script>


    </body>

</x-app-layout>
