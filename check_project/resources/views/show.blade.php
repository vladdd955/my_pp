<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Show Task') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6">
                        <label for="taskTitle" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Created at</label>
                        <input type="text" id="taskCreated" name="created_at" value="{{ $task->created_at }}" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                    </div>

                    <div class="mb-6">
                        <label for="taskTitle" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Title</label>
                        <input type="text" id="taskTitle" name="title" value="{{ $task->title }}" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                    </div>

                    <div class="mb-6">
                        <label for="taskStatus" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Status</label>
                        <input type="text" id="taskStatus" name="status" value="{{ ucfirst($task->status) }}" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>
                    </div>

                    <div>
                        <label for="taskDescription" class="block text-sm font-medium text-gray-900 dark:text-gray-100">Description</label>
                        <textarea id="taskDescription" name="description" rows="10" class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" readonly>{{ $task->description }}</textarea>
                    </div>
                </div>
                @if($allowedCloseButton)
                    <button type="button" id="closeTask" class="btn btn-danger mt-3" style="width: 15%" data-task-id="{{ $task->id }}">Close Task</button>
                @endif
                <button type="button" id="updateTask" class="btn btn-primary mt-3" style="width: 15%" data-task-id="{{ $task->id }}">Update Task</button>
            </div>
        </div>
    </div>

    <!-- Модальное окно в тёмной теме -->
    <div class="modal fade" id="updateTaskModal" tabindex="-1" aria-labelledby="updateTaskModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content dark:bg-gray-800 dark:text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateTaskModalLabel">Update Task</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="taskIdInput">
                    <label for="newStatusInput" class="form-label">New Status:</label>
                    <select class="form-select bg-gray-700 text-white border-gray-600 dark:bg-gray-700 dark:border-gray-600" id="newStatusInput">
                        @foreach($taskStatus as $status)
                            <option value="{{ $status }}">{{ $status }}</option>
                        @endforeach
                    </select>

                    <input class="block w-full mt-1 bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" type="hidden" id="userIdInput">
                    <label for="newUserIdInput" class="form-label">Chose User:</label>
                    <select class="form-select bg-gray-700 text-white border-gray-600 dark:bg-gray-700 dark:border-gray-600" id="newUserIdInput">
                        @foreach($usersList as $user)
                            <option value="{{ $user['id'] }}">{{ 'User id: '.$user['id'].' ('.$user['name'].')' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="updateTask()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Close task
    $(document).ready(function() {
        $('#closeTask').click(function() {
            let taskId = $(this).data('task-id');
            $.ajax({
                type: 'POST',
                url: '{{route('closeTask', true)}}',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'taskId': taskId,
                },
                success: function(response) {
                    console.log(response);
                    window.location.reload()
                },
                error: function(xhr, status, error) {
                    console.error('Error: ' + error);
                }
            });
        });
    });

    $(document).ready(function() {
        $('#updateTask').click(function() {

            console.log('ggggg')
            openUpdateModel();
        });
    });

    function updateTask() {
        let taskId = $('#taskIdInput').val();
        let newStatus = $('#newStatusInput').val();
        let userId = $('#newUserIdInput').val();

        $.ajax({
            type: 'POST',
            url: '{{ route('updateTask') }}',
            data: {
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'taskId': taskId,
                'newStatus': newStatus,
                'userId': userId
            },
            success: function(response) {
                console.log(response);
                $('#updateTaskModal').modal('hide');
                window.location.reload()

            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        });
    }

    function openUpdateModel() {
        let taskId = $('#updateTask').data('task-id');
        console.log('ddddddddd')
        $('#taskIdInput').val(taskId);
        $('#updateTaskModal').modal('show');
    }
</script>
