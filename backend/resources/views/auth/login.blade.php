<x-guest-layout>
<div class="login-box">
  <div class="card card-outline card-primary shadow">
    <div class="card-header text-center py-4">
      <h3 class="mb-0"><b>Task Manager</b> Admin</h3>
    </div>
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      @if ($errors->any())
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
              <ul class="mb-0 pl-3">
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </ul>
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif

      @if (session('status'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ session('status') }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
          </div>
      @endif

      <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
          <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required autofocus autocomplete="username">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required autocomplete="current-password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row align-items-center">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember" style="font-weight: 500;">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mb-1 mt-3">
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">I forgot my password</a>
        @endif
      </p>
      <p class="mb-0">
        <a href="{{ route('register') }}" class="text-center">Register a new account</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
</x-guest-layout>
