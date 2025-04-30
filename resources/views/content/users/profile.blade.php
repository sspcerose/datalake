@extends('layouts/contentNavbarLayout')

@section('title', 'Profile')

@section('content')

<div class="container d-flex justify-content-center">
  <!-- Combined Container -->
  <div class="row col-md-12">
    <!-- Update Profile Card -->
    <div class="col-md-6">
      <div class="card mb-6 border-end">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Update Profile</h5>
          <!-- <small class="text-muted float-end">
            <a href="{{ route('user-management') }}">Back</a>
          </small> -->
        </div>
        <div class="card-body">
          <form action="{{ route('profile-update') }}" method="POST" id="updateForm">
            @method('PUT')
            @csrf
            <!-- <div class="mb-6">
            <label class="form-label" for="basic-default-fullname">Full Name</label>
                <input type="text" class="form-control" id="basic-default-fullname" placeholder="John Doe" />
            </div> -->

            <div class="mb-6">
            <label class="form-label" for="basic-default-username">User Name</label>
            <input type="text" class="form-control" id="basic-default-username" name="username" placeholder="John Doe" value="{{ old('username', $user->username) }}" />
              @error('username')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>

            <!-- <div class="row mb-6">
              <label class="col-sm-4 col-form-label" for="basic-default-username">User Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="basic-default-username" name="username" placeholder="John Doe" value="{{ old('username', $user->username) }}"/>
                @error('username')
                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
              </div>
            </div> -->

            <div class="mb-6">
              <label class="form-label" for="basic-default-first_name">First Name</label>
              <input 
                type="text" 
                class="form-control" 
                id="basic-default-first_name" 
                name="first_name" 
                placeholder="John Doe" 
                value="{{ old('first_name', $user->first_name) }}" 
              />
              @error('first_name')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>

            <!-- <div class="row mb-6">
              <label class="col-sm-4 col-form-label" for="basic-default-first_name">First Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="basic-default-first_name" name="first_name" placeholder="John Doe" value="{{ old('first_name', $user->first_name) }}"/>
                @error('first_name')
                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
              </div>
            </div> -->

            <!-- <div class="row mb-6">
              <label class="col-sm-4 col-form-label" for="basic-default-last_name">Last Name</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="basic-default-last_name" name="last_name" placeholder="John Doe" value="{{ old('last_name', $user->last_name) }}"/>
                @error('last_name')
                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
              </div>
            </div> -->
            <div class="mb-6">
              <label class="form-label" for="basic-default-last_name">Last Name</label>
              <input 
                type="text" 
                class="form-control" 
                id="basic-default-last_name" 
                name="last_name" 
                placeholder="John Doe" 
                value="{{ old('last_name', $user->last_name) }}" 
              />
              @error('last_name')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>

            <div class="mb-6">
              <label class="form-label" for="basic-default-email">Email</label>
              <div class="input-group input-group-merge">
                <input 
                  type="email" 
                  name="email" 
                  value="{{ old('email', $user->email) }}" 
                  id="basic-default-email" 
                  class="form-control" 
                  placeholder="john.doe@example.com" 
                  aria-label="Email" 
                  aria-describedby="basic-default-email2" 
                  required 
                />
              </div>
              <div class="form-text">Please enter a valid email address.</div>
              @error('email')
                <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
              @enderror
            </div>

            <!-- <div class="row mb-6">
              <label class="col-sm-4 col-form-label" for="basic-default-email">Email</label>
              <div class="col-sm-8">
                <div class="input-group input-group-merge">
                  <input type="text" name="email" value="{{ old('email', $user->email) }}" id="basic-default-email" class="form-control" placeholder="john.doe" aria-label="john.doe" aria-describedby="basic-default-email2" />
                </div>
                <div class="form-text"> You can use letters, numbers & periods </div>
                @error('email')
                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
              </div>
            </div> -->

            <div class="row mb-6">
              <label class="col-sm-4 col-form-label" for="basic-default-company">User Type</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="basic-default-company" name="user_type" value="{{ $user->user_type }}" readonly>
                @error('user_type')
                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
              </div>
            </div>

            

            <!-- <div class="row mb-6">
              <label class="col-sm-4 col-form-label" for="basic-default-company">Status</label>
              <div class="col-sm-8">
                <input type="text" class="form-control" id="basic-default-company" name="status" value="{{ $user->status }}" readonly>
                @error('status')
                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
              </div>
            </div> -->

            <div class="row">
              <div class="col-sm-12">
                <div class="mt-4">
                  <button type="submit" class="btn btn-warning me-3" id="confirmUpdateButton">Update Profile</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    
    <!-- Divider -->
    <!-- <div class="col-md-1 d-flex justify-content-center align-items-center">
      <div class="vr" style="height: 100%; width: 2px; background-color: #dee2e6;"></div>
    </div>
     -->
    <!-- Change Password Card -->
    <div class="col-md-6">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Change Password</h5>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('password.update') }}" id="changePassForm">
            @csrf
            <div class="row mb-6 form-password-toggle">
              <label class="col-sm-4 col-form-label"  for="password">Current Password</label>
              <div class="col-sm-8">
                <div class="input-group input-group-merge">
                  <input type="password" name="current_password" id="current_password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
                @error('current_password')
                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-6 form-password-toggle">
              <label class="col-sm-4 col-form-label"  for="password">New Password</label>
              <div class="col-sm-8">
                <div class="input-group input-group-merge">
                  <input type="password" name="new_password" id="new_password" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
                @error('new_password')
                    <span class="text-danger small font-weight-bold d-block mt-1">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="row mb-6 form-password-toggle">
              <label class="col-sm-4 col-form-label"  for="password">Confirm Password</label>
              <div class="col-sm-8">
                <div class="input-group input-group-merge">
                  <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                </div>
              </div>
            </div>
            <div class="row justify-content-end">
              <div class="col-sm-12">
                <button type="submit" class="btn btn-warning me-3" id="confirmChangeButton">Change Password</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- Alert Message -->
<!--Profile
<script>
  document.getElementById('confirmUpdateButton').addEventListener('click', function() {
    Swal.fire({
      title: "Update Profile?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, update it!"
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('updateForm').submit();
      }
    });
  });
</script> -->

<!-- Change Pass -->
<!-- <script>
  document.getElementById('confirmChangeButton').addEventListener('click', function() {
    Swal.fire({
      title: "Change Password?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, change it!"
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('changePassForm').submit();
      }
    });
  });
</script> -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

<!-- Profile Form -->
<script>
// $(document).ready(function () {
//     $('#basic-default-username, #basic-default-first_name, #basic-default-last_name, #basic-default-email')
//         .on('blur keyup', function () {
//             switch (this.id) {
//                 case 'basic-default-username': validateUsername(); break;
//                 case 'basic-default-first_name': validateFirstName(); break;
//                 case 'basic-default-last_name': validateLastName(); break;
//                 case 'basic-default-email': validateEmail(); break;
//             }
//         });

//         $('#confirmUpdateButton').on('click', function (e) {
//         let isValid = true;

//         validateUsername();
//         validateFirstName();
//         validateLastName();
//         validateEmail();

        
//         if ($('.text-danger').length > 0) {
//           console.log("Errors detected after validation:", $('.text-danger').length);
//           console.log("Validation errors detected");
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
//             $('#updateForm').submit();
//         }
//     });

//     function validateUsername() {
//         validateField('basic-default-username', 'Username field is required');
//     }
    
//     function validateFirstName() {
//       console.log('I am here 1');
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
//       console.log('I am here 2');
//       let lastName = document.getElementById('basic-default-last_name');
//       let nameValue = lastName.value.trim();
//       let namePattern = /^[A-Za-z\s]{2,30}$/;

//       if (!nameValue) {
//           setErrorMessage(lastName, 'Last Name is required');
//       } else if (!namePattern.test(nameValue)) {
//           setErrorMessage(lastName, 'Last Name must only contain letters and be 2-30 characters long');
//       } else {
//           clearErrorMessage(lastName);
//       }
//     }

//     function validateEmail() {
//       console.log('I am here 3');
//         let email = document.getElementById('basic-default-email');
//         let emailValue = email.value.trim();
//         let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

//         if (!emailValue) {
//             setErrorMessage(email, 'Email field is required');
//         } else if (!emailPattern.test(emailValue)) {
//             setErrorMessage(email, 'Invalid email address');
//         } else {
//           console.log('Valid');
//             clearErrorMessage(email);
//         }
//     }


//     function validateField(fieldId, errorMessage) {
//       console.log('I am here 4');
//         let input = document.getElementById(fieldId);
//         let value = input.value.trim();
//         if (!value) {
//             setErrorMessage(input, errorMessage);
//         } else {
//             clearErrorMessage(input);
//         }
//     }

//     function setErrorMessage(input, message) {
//       console.log('I am here 5');
//       console.log(`Adding error: ${message} for field: ${input.id}`);
//         clearErrorMessage(input); 
//         const errorSpan = document.createElement('span');
//         errorSpan.classList.add('text-danger', 'small', 'font-weight-bold', 'd-block', 'mt-1');
//         errorSpan.innerText = message;
//         input.closest('.col-sm-10').appendChild(errorSpan);
//     }

//     function clearErrorMessage(input) {
//       console.log('I am here 6');
//       let errorSpans = input.closest('.col-sm-10').querySelectorAll('.text-danger');
//       errorSpans.forEach(span => span.remove());
//         // let errorSpan = input.closest('.col-sm-10').querySelector('.text-danger');
//         // if (errorSpan) {
//         //     errorSpan.remove();
//         // }
//     }
    
// });

</script>

<!-- Form Validation Password -->
<script>
// $(document).ready(function () {
//     console.log('I am here 1');
//     $(' #current_password, #new_password, #new_password_confirmation')
//         .on('blur keyup', function () {
//             switch (this.id) {
//                 case 'current_password': validatePassword(); break;
//                 case 'new_password': validateNewPassword(); break;
//                 case 'new_password_confirmation': validateNewPasswordConfirmation(); break;
//             }
//         });

//         $('#confirmChangeButton').on('click', function (e) {
//         let isValid = false;
//         console.log('I am here 2');

//         validatePassword()
//         validateNewPassword();
//         validateNewPasswordConfirmation();

//         if ($('.text-danger').length !== 0) {
//           console.log("Password Form Errors:", $('#changePassForm .text-danger').length);
//             isValid = false;
//         }

//         console.log(isValid);
//         if (!isValid) {
//         e.preventDefault(); 
//         Swal.fire({
//             title: 'Error!',
//             text: 'Please fix all errors before submitting.',
//             icon: 'error',
//             confirmButtonText: 'Okay'
//         });
//         } else {
//             $('#confirmChangeButton').submit();
//         }
//     });

//     function validatePassword() {
//       console.log('Password');
//         validateField('current_password', 'Password field is required');
//     }
//     function validateNewPassword() {
//       console.log('New Pass');
//         validateField('new_password', 'New Password field is required');
//     }

//     function validateNewPasswordConfirmation() {
//       console.log('Confirm Pass');
//         let new_password = document.getElementById('new_password').value.trim();
//         let new_password_confirmation = document.getElementById('new_password_confirmation');

//         if (!new_password_confirmation.value.trim()) {
//             setErrorMessage(new_password_confirmation, 'Confirm Password field is required');
//         } else if (new_password_confirmation.value.trim() !== new_password) {
//             setErrorMessage(new_password_confirmation, 'Passwords do not match');
//         } else {
//             clearErrorMessage(new_password_confirmation);
//         }
//         // validateField('new_password_confirmation', 'Confirm Password field is required');
//     }

//     function validateField(fieldId, errorMessage) {
//       console.log('Val Field')
//         let input = document.getElementById(fieldId);
//         let value = input.value.trim();
//         if (!value) {
//             setErrorMessage(input, errorMessage);
//         } else {
//             clearErrorMessage(input);
//         }
//     }

//     function setErrorMessage(input, message) {
//       console.log('Error');
//         clearErrorMessage(input); 
//         const errorSpan = document.createElement('span');
//         errorSpan.classList.add('text-danger', 'small', 'font-weight-bold', 'd-block', 'mt-1');
//         errorSpan.innerText = message;
//         input.closest('.col-sm-10').appendChild(errorSpan);
//     }

//     function clearErrorMessage(input) {
//         // let errorSpan = input.closest('.col-sm-10').querySelector('.text-danger');
//         // if (errorSpan) {
//         //     errorSpan.remove();
//         // }
//         let errorSpans = input.closest('.col-sm-10').querySelectorAll('.text-danger');
//         errorSpans.forEach(span => span.remove());
//     }

// });
</script>


@endsection
