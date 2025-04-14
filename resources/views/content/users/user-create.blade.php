@extends('layouts/contentNavbarLayout')

@section('title', 'Users')

@section('content')

<!-- Basic Layout & Basic with Icons -->
<div class="container d-flex justify-content-center">
  <!-- Basic Layout -->
  <div class="col-md-10">
    <div class="card mb-6">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="mb-0">Register</h5> <small class="text-muted float-end"><a href="{{route('user-management')}}">Back</a>
      </div>
      <div class="card-body">
        <form action="{{ route('user-register') }}" method="POST" id="addForm">
          @csrf
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-username">User Name<span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="basic-default-username" name="username" placeholder="John Doe" value="{{old('username')}}" />
              @error('username')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-first_name">First Name<span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="basic-default-first_name" name="first_name" placeholder="John Doe" value="{{old('first_name')}}" />
              @error('first_name')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-last_name">Last Name<span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <input type="text" class="form-control" id="basic-default-last_name" name="last_name" placeholder="John Doe" value="{{old('last_name')}}" />
              @error('last_name')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-company">User Type<span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <select class="form-select" id="basic-default-company" aria-label="Default select example" name="user_type">
              <option value="" disabled {{ old('user_type') == null ? 'selected' : '' }}>Select User Type</option>
              <option value="Super Admin" {{ old('user_type') == 'Super Admin' ? 'selected' : '' }}>Super Admin ([Add Desc Later])</option>
              <option value="Admin" {{ old('user_type') == 'Admin' ? 'selected' : '' }}>Admin ([Add Desc Later])</option>
              <option value="Viewer" {{ old('user_type') == 'Viewer' ? 'selected' : '' }}>Viewer (Access to view content only.)</option>
              </select>
              @error('user_type')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6">
            <label class="col-sm-2 col-form-label" for="basic-default-email">Email<span class="text-danger">*</span></label>
            <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <input type="text" name="email" id="basic-default-email" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-default-email2" value="{{old('email')}}" />
              </div>
              <div class="form-text"> You can use letters, numbers & periods </div>
              @error('email')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="row mb-6 form-password-toggle">
              <label class="col-sm-2 col-form-label"  for="password">Password<span class="text-danger">*</span></label>
              <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <input type="password" name="password" id="password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
            @error('password')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
        </div>
        <div class="row mb-6 form-password-toggle">
              <label class="col-sm-2 col-form-label"  for="password">Confirm Password<span class="text-danger">*</span></label>
              <div class="col-sm-10">
              <div class="input-group input-group-merge">
                <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
            </div>
            @error('confirmpassword')
                  <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>
        </div>
        <div class="row justify-content-end">
            <div class="col-sm-10">
              <div class="mt-6">
                <button type="submit" class="btn btn-primary me-3" id="confirmAddButton">Add Account</button>
                <a href="{{route('user-management')}}" class="btn btn-outline-danger">Cancel</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Alert Nesssage -->
<script>
  // document.getElementById('confirmAddButton').addEventListener('click', function() {
  //   Swal.fire({
  //     title: "Add New User?",
  //     text: "You won't be able to revert this!",
  //     icon: "warning",
  //     showCancelButton: true,
  //     confirmButtonColor: "#3085d6",
  //     cancelButtonColor: "#d33",
  //     confirmButtonText: "Yes, add it!"
  //   }).then((result) => {
  //     if (result.isConfirmed) {
  //       // Submit the form immediately after confirmation
  //       document.getElementById('addForm').submit();
  //     }
  //   });
  // });
</script>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<!-- Form Validation -->
<script>
// $(document).ready(function () {
//     $('#basic-default-username, #basic-default-first_name, #basic-default-last_name, #basic-default-email, #password, #confirmpassword')
//         .on('blur keyup', function () {
//             switch (this.id) {
//                 case 'basic-default-username': validateUsername(); break;
//                 case 'basic-default-first_name': validateFirstName(); break;
//                 case 'basic-default-last_name': validateLastName(); break;
//                 case 'basic-default-company': validateUserType(); break;
//                 case 'basic-default-email': validateEmail(); break;
//                 case 'password': validatePassword(); validateConfirmPassword(); break;
//                 case 'confirmpassword': validateConfirmPassword(); break;
//             }
//         });

//         $('#confirmAddButton').on('click', function (e) {
//         let isValid = true;

//         validateUsername();
//         validateFirstName();
//         validateLastName();
//         validateEmail();
//         validateUserType();
//         validatePassword();
//         validateConfirmPassword();

//         if ($('.text-danger').length > 0) {
//             isValid = false;
//         }

//         if (!isValid) {
//         e.preventDefault(); 
//         Swal.fire({
//             title: 'Error!',
//             text: 'Please fix all errors before submitting.',
//             icon: 'error',
//             confirmButtonText: 'Okay'
//         });
//         } else {
//             $('#addForm').submit();
//         }
//     });
    
//     function validateFirstName() {
//       let firstName = document.getElementById('basic-default-first_name');
//       let nameValue = firstName.value.trim();
//       let namePattern = /^[A-Za-z\s]{2,30}$/;

//       if (!nameValue) {
//           setErrorMessage(firstName, 'First Name field is required');
//       } else if (!namePattern.test(nameValue)) {
//           setErrorMessage(firstName, 'First Name must only contain letters and be 2-30 characters long');
//       } else {
//           clearErrorMessage(firstName);
//       }
//     }

//     function validateLastName() {
//       let lastName = document.getElementById('basic-default-last_name');
//       let nameValue = lastName.value.trim();
//       let namePattern = /^[A-Za-z\s]{2,30}$/;

//       if (!nameValue) {
//           setErrorMessage(lastName, 'Last Name is required');
//       } else if (!namePattern.test(nameValue)) {
//           setErrorMessage(lastName, 'First Name must only contain letters and be 2-30 characters long');
//       } else {
//           clearErrorMessage(lastName);
//       }
//     }

//     $('#basic-default-company').on('change', function () {
//         validateUserType();
//     });

//     function validateUsername() {
//         validateField('basic-default-username', 'Username field is required');
//     }

//     function validateUserType() {
//     let userType = document.getElementById('basic-default-company');
//     let selectedValue = userType.value.trim();

//       if (!selectedValue) {
//           setErrorMessage(userType, 'Please select a valid User Type');
//       } else {
//           clearErrorMessage(userType);
//       }
//   }

//     function validateEmail() {
//         let email = document.getElementById('basic-default-email');
//         let emailValue = email.value.trim();
//         let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

//         if (!emailValue) {
//             setErrorMessage(email, 'Email field is required');
//         } else if (!emailPattern.test(emailValue)) {
//             setErrorMessage(email, 'Invalid email address');
//         } else {
//             clearErrorMessage(email);
//         }
//     }

//     function validatePassword() {
//         validateField('password', 'Password field is required');
//     }

//     function validateConfirmPassword() {
//         let password = document.getElementById('password').value.trim();
//         let confirmPassword = document.getElementById('confirmpassword');

//         if (!confirmPassword.value.trim()) {
//             setErrorMessage(confirmPassword, 'Confirm Password field is required');
//         } else if (confirmPassword.value.trim() !== password) {
//             setErrorMessage(confirmPassword, 'Passwords do not match');
//         } else {
//             clearErrorMessage(confirmPassword);
//         }
//     }

//     function validateField(fieldId, errorMessage) {
//         let input = document.getElementById(fieldId);
//         let value = input.value.trim();
//         if (!value) {
//             setErrorMessage(input, errorMessage);
//         } else {
//             clearErrorMessage(input);
//         }
//     }

//     function setErrorMessage(input, message) {
//         clearErrorMessage(input); 
//         const errorSpan = document.createElement('span');
//         errorSpan.classList.add('text-danger', 'small', 'font-weight-bold', 'd-block', 'mt-1');
//         errorSpan.innerText = message;
//         input.closest('.col-sm-10').appendChild(errorSpan);
//     }

//     function clearErrorMessage(input) {
//         let errorSpan = input.closest('.col-sm-10').querySelector('.text-danger');
//         if (errorSpan) {
//             errorSpan.remove();
//         }
//     }
// });

</script>

@endsection
