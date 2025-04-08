<!-- <p>You requested a password reset. Click the link below to reset your password:</p>
<a href="{{ $link }}">Reset Password</a>
<p>If you did not request this, please ignore this email.</p> -->

<div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title text-center text-primary mb-3">Password Reset Request</h3>
                        <p class="card-text">Dear User,</p>
                        <p>You requested a password reset. Click the button below to reset your password:</p>
                        <div class="text-center my-3">
                            <a href="{{ $link }}" class="btn btn-primary btn-lg">Reset Password</a>
                        </div>
                        <p>If you did not request this, please ignore this email.</p>
                        <p>Thank you,<br>The Support Team</p>
                    </div>
                </div>
            </div>
        </div>
    </div>