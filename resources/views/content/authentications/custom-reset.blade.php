
@extends('layouts/blankLayout')

@section('title', 'Reset Password')

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <!-- Register -->
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <!-- <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
              <span class="app-brand-text demo text-heading fw-bold">{{config('variables.templateName')}}</span> -->
              <img src="{{ asset('assets/img/favicon/data-lake-logo.png') }}" alt="Logo" style="height: 85px; width: auto;" />
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">Reset Password</h4>
          <p class="mb-6">Make sure it's strong and easy for you to remember</p>

          <form id="formAuthentication" class="mb-6" action="{{ route('password.update1') }}"  method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" placeholder="Enter your Email" autofocus>
              @error('email')
                <div class="text-danger mt-2 small">
                    {{ $message }}
                </div>
            @enderror
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">New Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Confirm Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
              <div class="mt-6">
                <button type="submit" class="btn btn-primary me-3">Reset Password</button>
                <a href="{{route('auth-login-basic')}}" class="btn btn-outline-danger">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
