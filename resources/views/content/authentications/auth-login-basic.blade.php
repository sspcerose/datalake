@extends('layouts/blankLayout')

@section('title', 'Login')

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
          <div class="app-brand mb-3">
            <a href="{{ url('/') }}" class="app-brand-link">
              <img src="{{ asset('assets/img/favicon/data-lake-logo.png') }}" alt="Logo" style="height: 85px; width: auto;" />
              <!-- <span class="app-brand-text demo text-heading fw-bold">ADD LOGO</span> -->
            </a>
          </div>
          <!-- /Logo -->
          <h4 class="mb-1 d-flex">Welcome!</h4>
          <p class="mb-6">Please sign-in to your account</p>

          <form id="formAuthentication" class="mb-6" action="{{ route('auth-login-basic2')}}" method="POST">
            @csrf
            <div class="mb-6">
              <label for="email" class="form-label">Email</label>
              <input type="text" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" autofocus required>
              @error('email')
                <div class="text-danger mt-2 small">
                    {{ $message }}
                </div>
            @enderror
            </div>
            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required/>
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
              </div>
            </div>
            <div class="mb-6">
              <div class="d-flex justify-content-end mt-4">
                <!-- <div class="form-check mb-0 ms-2">
                  <input class="form-check-input" type="checkbox" id="remember-me">
                  <label class="form-check-label" for="remember-me">
                    Remember Me
                  </label>
                </div> -->
                <a href="{{url('auth/forgot-password-basic')}}">
                  <span>Forgot Password?</span>
                </a>
              </div>
            </div>
            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" id="loginBtn" type="submit">Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /Register -->
  </div>
</div>
<!-- Alert Message -->
@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}", 
            showConfirmButton: true
        });
    </script>
@endif

<!-- <script>
  $(document).ready(function () {
    $('#loginBtn').on('click', function () {
      const email = $('#email').val();
      const password = $('#password').val();

      $.ajax({
        url: "{{ route('auth-login-basic2') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          email: email,
          password: password
        },
        success: function (response) {
          if (response.success) {
            window.location.href = response.redirect_url;
          } else {
            $('#emailError').text(response.errors?.email || '');
            $('#passwordError').text(response.errors?.password || '');
            $('#generalError').text(response.message || '');
          }
        },
        error: function (xhr) {
          const errors = xhr.responseJSON?.errors || {};
          $('#emailError').text(errors.email || '');
          $('#passwordError').text(errors.password || '');
          $('#generalError').text(xhr.responseJSON?.message || 'An unexpected error occurred.');
        }
      });
    });
  });
</script> -->


<!-- <script>
$(document).ready(function () {
    console.log('I am here 1');
    $(' #email, #password')
        .on('blur keyup', function () {
            switch (this.id) {
                case 'email': validateEmail(); break;
                case 'password': validatePassword(); break;
            }
        });
    $('#login').on('click', function (e) {
        let isValid = true;
        console.log('I am here 2');

        validateEmail()
        validatePassword();

      if ($('.text-danger').length > 0) {
            isValid = false;
        }
        if (!isValid) {
        e.preventDefault(); 
        Swal.fire({
            title: 'Error!',
            text: 'Please provide all required fields.',
            icon: 'error',
            confirmButtonText: 'Okay'
        });
        } else {
            $('#formAuthentication').submit();
        }
    });

    function validateEmail() {
        validateField('email', 'Email field is required');
    }
    function validatePassword() {
        validateField('password', 'Password field is required');
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
</script> -->
@endsection
