@extends('layouts.adminlte')

@section('title', 'My Profile')
@section('page_title', 'My Profile')

@section('breadcrumbs')
  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
  <li class="breadcrumb-item active">Profile</li>
@endsection

@section('content')
<div class="row">
  <!-- Left Column: Profile Card & Stats -->
  <div class="col-12 col-md-4">
    <div class="card card-primary card-outline shadow-sm">
      <div class="card-body box-profile">
        <div class="text-center">
          <i class="fas fa-user-circle fa-7x text-secondary mb-3"></i>
        </div>

        <h3 class="profile-username text-center font-weight-bold text-dark">{{ Auth::user()->name }}</h3>
        <p class="text-muted text-center"><i class="fas fa-envelope mr-1"></i>{{ Auth::user()->email }}</p>

        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Total Tasks</b> 
            <a class="float-right text-primary font-weight-bold">{{ Auth::user()->tasks()->count() }}</a>
          </li>
          <li class="list-group-item">
            <b>Completed Tasks</b> 
            <a class="float-right text-success font-weight-bold">{{ Auth::user()->tasks()->where('is_completed', true)->count() }}</a>
          </li>
          <li class="list-group-item">
            <b>Pending Tasks</b> 
            <a class="float-right text-warning font-weight-bold">{{ Auth::user()->tasks()->where('is_completed', false)->count() }}</a>
          </li>
          <li class="list-group-item">
            <b>Member Since</b> 
            <a class="float-right text-secondary">{{ Auth::user()->created_at->format('M d, Y') }}</a>
          </li>
        </ul>
      </div>
      <!-- /.card-body -->
    </div>
  </div>

  <!-- Right Column: Tabs Forms Card -->
  <div class="col-12 col-md-8">
    <div class="card card-primary card-tabs shadow-sm">
      <div class="card-header p-0 pt-1">
        <ul class="nav nav-tabs" id="profileTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="profile-info-tab" data-toggle="pill" href="#profile-info" role="tab" aria-controls="profile-info" aria-selected="true">
              <i class="fas fa-user-edit mr-1"></i> Profile Info
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="change-password-tab" data-toggle="pill" href="#change-password" role="tab" aria-controls="change-password" aria-selected="false">
              <i class="fas fa-key mr-1"></i> Change Password
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-danger" id="delete-account-tab" data-toggle="pill" href="#delete-account" role="tab" aria-controls="delete-account" aria-selected="false">
              <i class="fas fa-exclamation-triangle mr-1"></i> Delete Account
            </a>
          </li>
        </ul>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="tab-content" id="profileTabContent">
          
          <!-- Tab 1: Profile Info -->
          <div class="tab-pane fade show active" id="profile-info" role="tabpanel" aria-labelledby="profile-info-tab">
            <form method="post" action="{{ route('profile.update') }}">
              @csrf
              @method('patch')

              <div class="form-group">
                <label for="name" class="font-weight-bold">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required autocomplete="name">
                @error('name')
                  <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              <div class="form-group">
                <label for="email" class="font-weight-bold">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required autocomplete="email">
                @error('email')
                  <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>

              @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible py-2 px-3 mb-3">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <i class="icon fas fa-check mr-1"></i> Profile updated successfully.
                </div>
              @endif

              <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-save mr-1"></i> Save Changes</button>
            </form>
          </div>

          <!-- Tab 2: Change Password -->
          <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
            <form method="post" action="{{ route('password.update') }}">
              @csrf
              @method('put')

              <div class="form-group">
                <label for="current_password" class="font-weight-bold">Current Password <span class="text-danger">*</span></label>
                <input type="password" name="current_password" id="current_password" class="form-control @if($errors->updatePassword->has('current_password')) is-invalid @endif" required autocomplete="current-password">
                @if($errors->updatePassword->has('current_password'))
                  <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $errors->updatePassword->first('current_password') }}</strong>
                  </span>
                @endif
              </div>

              <div class="form-group">
                <label for="password" class="font-weight-bold">New Password <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" class="form-control @if($errors->updatePassword->has('password')) is-invalid @endif" required autocomplete="new-password">
                @if($errors->updatePassword->has('password'))
                  <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $errors->updatePassword->first('password') }}</strong>
                  </span>
                @endif
              </div>

              <div class="form-group">
                <label for="password_confirmation" class="font-weight-bold">Confirm New Password <span class="text-danger">*</span></label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @if($errors->updatePassword->has('password_confirmation')) is-invalid @endif" required autocomplete="new-password">
                @if($errors->updatePassword->has('password_confirmation'))
                  <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $errors->updatePassword->first('password_confirmation') }}</strong>
                  </span>
                @endif
              </div>

              @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible py-2 px-3 mb-3">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <i class="icon fas fa-check mr-1"></i> Password updated successfully.
                </div>
              @endif

              <button type="submit" class="btn btn-primary btn-flat"><i class="fas fa-key mr-1"></i> Update Password</button>
            </form>
          </div>

          <!-- Tab 3: Delete Account -->
          <div class="tab-pane fade" id="delete-account" role="tabpanel" aria-labelledby="delete-account-tab">
            <div class="callout callout-danger shadow-sm">
              <h5><i class="fas fa-exclamation-triangle text-danger mr-1"></i> Warning!</h5>
              <p class="mb-0">Once your account is deleted, all of its resources and data will be permanently deleted. This action is irreversible.</p>
            </div>

            <button type="button" class="btn btn-danger btn-flat mt-3" data-toggle="modal" data-target="#confirmDeleteModal">
              <i class="fas fa-trash-alt mr-1"></i> Delete My Account
            </button>
          </div>

        </div>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</div>

<!-- Confirm Delete Account Modal -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title font-weight-bold text-white" id="confirmDeleteModalLabel">Confirm Account Deletion</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="post" action="{{ route('profile.destroy') }}">
        @csrf
        @method('delete')
        <div class="modal-body">
          <p class="text-dark">Are you sure you want to delete your account? Please enter your password to confirm permanent deletion.</p>
          
          <div class="form-group mb-0">
            <label for="delete_password" class="font-weight-bold text-dark">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" id="delete_password" class="form-control @if($errors->userDeletion->has('password')) is-invalid @endif" placeholder="Enter your password to confirm" required>
            @if($errors->userDeletion->has('password'))
              <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $errors->userDeletion->first('password') }}</strong>
              </span>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger btn-flat">Delete Account</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    // If password validation error occurs, auto-switch to "Change Password" tab
    @if($errors->updatePassword->isNotEmpty() || session('status') === 'password-updated')
      $('#profileTab a[href="#change-password"]').tab('show');
    @endif

    // If account deletion validation error occurs, auto-switch to "Delete Account" tab and open modal
    @if($errors->userDeletion->isNotEmpty())
      $('#profileTab a[href="#delete-account"]').tab('show');
      $('#confirmDeleteModal').modal('show');
    @endif
  });
</script>
@endsection
