@extends('layouts/blankLayout')
@section('title', 'Login Basic - Pages')

@section('page-style')
@vite([
  'resources/assets/vendor/scss/pages/page-auth.scss'
])
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="{{url('/')}}" class="app-brand-link gap-2">
              <!-- <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
              <span class="app-brand-text demo text-heading fw-bold">{{config('variables.templateName')}}</span> -->
              <span class="app-brand-text demo text-heading fw-bold">DATA LAKE</span>
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1">Welcome! 👋</h4>
          <p class="mb-6">Please change your temporary password</p>
          <form id="changePassForm" class="mb-6" action="{{ route('password.update') }}"  method="POST" >
            @csrf
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Current Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="current_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
              @error('current_password')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">New Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="new_password" class="form-control" name="new_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
              @error('new_password')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Confirm Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="new_password_confirmation" class="form-control" name="new_password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
              @error('new_password_confirmation')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
              <div class="mt-6">
                <button type="submit" id="confirmChangeButton" class="btn btn-primary me-3">Change Password</button>
                <a href="{{route('auth-login-basic')}}" class="btn btn-outline-danger">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<!-- Form Validation -->
<script>
  $(document).ready(function () {
    console.log('I am here 1');
    $(' #password, #new_password, #new_password_confirmation')
        .on('blur keyup', function () {
            switch (this.id) {
                case 'password': validatePassword(); break;
                case 'new_password': validateNewPassword(); break;
                case 'new_password_confirmation': validateNewPasswordConfirmation(); break;
            }
        });

        $('#confirmChangeButton').on('click', function (e) {
        let isValid = true;
        console.log('I am here 2');

        validatePassword()
        validateNewPassword();
        validateNewPasswordConfirmation();

        if ($('.text-danger').length > 0) {
            isValid = false;
        }
        if (!isValid) {
        e.preventDefault(); 
        Swal.fire({
            title: 'Error!',
            text: 'Please fix all errors before submitting.',
            icon: 'error',
            confirmButtonText: 'Okay'
        });
        } else {
            $('#changePassForm').submit();
        }
    });

    function validatePassword() {
        validateField('password', 'Password field is required');
    }
    function validateNewPassword() {
        validateField('new_password', 'New Password field is required');
    }
    function validateNewPasswordConfirmation() {
        validateField('new_password_confirmation', 'Confirm Password field is required');
    }

    function validateField(fieldId, errorMessage) {
        let input = document.getElementById(fieldId);
        let value = input.value.trim();
        if (!value) {
            setErrorMessage(input, errorMessage);
        } else {
            clearErrorMessage(input);
        }
    }

    function setErrorMessage(input, message) {
        clearErrorMessage(input); 
        const errorSpan = document.createElement('span');
        errorSpan.classList.add('text-danger', 'small', 'font-weight-bold', 'd-block', 'mt-1');
        errorSpan.innerText = message;
        input.closest('.mb-6').appendChild(errorSpan);
    }

    function clearErrorMessage(input) {
        let errorSpan = input.closest('.mb-6').querySelector('.text-danger');
        if (errorSpan) {
            errorSpan.remove();
        }
    }

});
</script>
@endsection
