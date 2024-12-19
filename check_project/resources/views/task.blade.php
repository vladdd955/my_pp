<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create new task') }}
        </h2>
    </x-slot>

    <div class="py-12 flex items-center justify-center">
        <div class="max-w-5xl w-full mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-dark-100">
                    <form action="{{ route('task.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label for="taskTitle" class="block font-medium text-gray-800 dark:text-white">Task Title</label>
                            <input type="text" name="title" id="taskTitle" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Enter task title" value="{{ old('title') }}">
                            @if ($errors->has('title'))
                                <span class="text-red-600 text-sm">{{ $errors->first('title') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="taskStatus" class="block font-medium text-gray-800 dark:text-gray-200">Task Status</label>
                            <select name="status" id="taskStatus" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                @foreach ($status as $value => $label)
                                    <option value="{{ $value }}" {{ old('status') == $value ? 'selected' : '' }} class="bg-gray-700 text-white">{{ $label }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('status'))
                                <span class="text-red-600 text-sm">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                        <div class="form-group mb-4">
                            <label for="taskDescription" class="block font-medium text-gray-800 dark:text-gray-200">Task Description</label>
                            <textarea name="description" id="taskDescription" rows="4" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Enter task description">{{ old('description') }}</textarea>
                            @if ($errors->has('description'))
                                <span class="text-red-600 text-sm">{{ $errors->first('description') }}</span>
                            @endif
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
