@extends('layouts.adminlte')

@section('title', 'Tasks Dashboard')
@section('page_title', 'Tasks Dashboard')

@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Tasks</li>
@endsection

@section('content')
<!-- Stats Cards Row -->
<div class="row">
  <div class="col-12 col-sm-6 col-md-4">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-info elevation-1"><i class="fas fa-clipboard-list"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Total Tasks</span>
        <span class="info-box-number font-weight-bold h4 mb-0">{{ $totalTasks }}</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-4">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hourglass-half text-white"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Pending Tasks</span>
        <span class="info-box-number font-weight-bold h4 mb-0">{{ $pendingTasks }}</span>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-md-4">
    <div class="info-box shadow-sm">
      <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
      <div class="info-box-content">
        <span class="info-box-text">Completed Tasks</span>
        <span class="info-box-number font-weight-bold h4 mb-0">{{ $completedTasks }}</span>
      </div>
    </div>
  </div>
</div>

<!-- Main Table Card -->
<div class="row mt-3">
  <div class="col-12">
    <div class="card card-primary card-outline shadow-sm">
      <div class="card-header border-transparent">
        <h3 class="card-title font-weight-bold"><i class="fas fa-tasks mr-2 text-primary"></i>My Tasks</h3>
        <div class="card-tools">
          <button class="btn btn-primary btn-sm btn-flat" data-toggle="modal" data-target="#addTaskModal">
            <i class="fas fa-plus mr-1"></i> Add New Task
          </button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover m-0 align-middle">
            <thead>
              <tr>
                <th style="width: 5%">#</th>
                <th style="width: 25%">Title</th>
                <th style="width: 30%">Description</th>
                <th style="width: 10%">Category</th>
                <th style="width: 10%">Priority</th>
                <th style="width: 10%">Due Date</th>
                <th style="width: 10%" class="text-center">Status</th>
                <th style="width: 10%" class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tasks as $index => $task)
                <tr class="{{ $task->is_completed ? 'table-light text-muted' : '' }}">
                  <td>{{ $index + 1 }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <!-- Toggle Status Checkbox Form -->
                      <form action="{{ route('tasks.toggle', $task) }}" method="POST" class="mr-2">
                        @csrf
                        @method('PATCH')
                        <input type="checkbox" onchange="this.form.submit()" {{ $task->is_completed ? 'checked' : '' }} class="mr-2" style="transform: scale(1.2); cursor: pointer;">
                      </form>
                      <span class="{{ $task->is_completed ? 'text-decoration-line-through' : 'font-weight-bold text-dark' }}">
                        {{ $task->title }}
                      </span>
                    </div>
                  </td>
                  <td>
                    <span class="text-sm text-wrap text-break" style="max-width: 350px; display: inline-block;">
                      {{ $task->description ?: 'No description provided.' }}
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-light border px-2 py-1">{{ $task->category }}</span>
                  </td>
                  <td>
                    @if($task->priority === 'High')
                      <span class="badge badge-danger px-2 py-1"><i class="fas fa-arrow-up mr-1 text-xs"></i>High</span>
                    @elseif($task->priority === 'Medium')
                      <span class="badge badge-warning px-2 py-1"><i class="fas fa-minus mr-1 text-xs"></i>Medium</span>
                    @else
                      <span class="badge badge-info px-2 py-1"><i class="fas fa-arrow-down mr-1 text-xs"></i>Low</span>
                    @endif
                  </td>
                  <td>
                    <span class="text-sm">
                      <i class="far fa-calendar-alt mr-1 text-secondary"></i>
                      {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No Due Date' }}
                    </span>
                  </td>
                  <td class="text-center">
                    @if($task->is_completed)
                      <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1 text-xs"></i>Completed</span>
                    @else
                      <span class="badge badge-secondary px-2 py-1"><i class="fas fa-clock mr-1 text-xs"></i>Pending</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="btn-group">
                      <button class="btn btn-default btn-xs mr-1 edit-task-btn" 
                              data-id="{{ $task->id }}"
                              data-title="{{ $task->title }}"
                              data-description="{{ $task->description }}"
                              data-category="{{ $task->category }}"
                              data-priority="{{ $task->priority }}"
                              data-due-date="{{ $task->due_date ? $task->due_date->format('Y-m-d') : '' }}"
                              data-toggle="modal" 
                              data-target="#editTaskModal"
                              title="Edit Task">
                        <i class="fas fa-edit text-primary"></i>
                      </button>
                      
                      <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-default btn-xs" title="Delete Task">
                          <i class="fas fa-trash text-danger"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-4">
                    <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
                    <p class="text-muted">No tasks found. Click "Add New Task" to create one!</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</div>

<!-- Add Task Modal -->
<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bold" id="addTaskModalLabel">Create New Task</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('tasks.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="add_title" class="font-weight-bold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="add_title" class="form-control" placeholder="Enter task title" required>
          </div>
          <div class="form-group">
            <label for="add_description" class="font-weight-bold">Description</label>
            <textarea name="description" id="add_description" class="form-control" rows="3" placeholder="Enter task description"></textarea>
          </div>
          <div class="form-group">
            <label for="add_category" class="font-weight-bold">Category <span class="text-danger">*</span></label>
            <input type="text" name="category" id="add_category" class="form-control" placeholder="e.g. Work, Personal, Shopping" required>
          </div>
          <div class="form-group">
            <label for="add_priority" class="font-weight-bold">Priority <span class="text-danger">*</span></label>
            <select name="priority" id="add_priority" class="form-control" required>
              <option value="Low">Low</option>
              <option value="Medium" selected>Medium</option>
              <option value="High">High</option>
            </select>
          </div>
          <div class="form-group">
            <label for="add_due_date" class="font-weight-bold">Due Date</label>
            <input type="date" name="due_date" id="add_due_date" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-flat">Save Task</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editTaskModal" tabindex="-1" role="dialog" aria-labelledby="editTaskModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title font-weight-bold text-white" id="editTaskModalLabel">Edit Task</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editTaskForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label for="edit_title" class="font-weight-bold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" id="edit_title" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="edit_description" class="font-weight-bold">Description</label>
            <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
          </div>
          <div class="form-group">
            <label for="edit_category" class="font-weight-bold">Category <span class="text-danger">*</span></label>
            <input type="text" name="category" id="edit_category" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="edit_priority" class="font-weight-bold">Priority <span class="text-danger">*</span></label>
            <select name="priority" id="edit_priority" class="form-control" required>
              <option value="Low">Low</option>
              <option value="Medium">Medium</option>
              <option value="High">High</option>
            </select>
          </div>
          <div class="form-group">
            <label for="edit_due_date" class="font-weight-bold">Due Date</label>
            <input type="date" name="due_date" id="edit_due_date" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info btn-flat text-white">Update Task</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // Populate values in the edit modal when edit button is clicked
    $('.edit-task-btn').click(function() {
      var id = $(this).data('id');
      var title = $(this).data('title');
      var description = $(this).data('description');
      var category = $(this).data('category');
      var priority = $(this).data('priority');
      var dueDate = $(this).data('due-date');

      // Set form action dynamically
      var formAction = "{{ url('tasks') }}/" + id;
      $('#editTaskForm').attr('action', formAction);

      // Populate input values
      $('#edit_title').val(title);
      $('#edit_description').val(description);
      $('#edit_category').val(category);
      $('#edit_priority').val(priority);
      $('#edit_due_date').val(dueDate);
    });
  });
</script>
@endsection
