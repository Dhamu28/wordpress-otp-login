<!-- Firebase OTP Login Form -->
<div class="card shadow-sm border-0 p-4" style="max-width: 500px; margin: auto; border-radius: 12px;">
  <h4 class="mb-3"><i class="fas fa-mobile-alt me-2"></i>Login via Mobile</h4>
  <div class="mb-3">
    <input id="phone" type="text" class="form-control form-control-lg" placeholder="+91XXXXXXXXXX" />
  </div>
  <div id="recaptcha-container" class="mb-3"></div>
  <div class="d-grid mb-4">
    <button id="send-otp-button" class="btn btn-primary btn-lg">Send OTP</button>
  </div>
  <div class="mb-3">
    <input id="otp" type="text" class="form-control form-control-lg" placeholder="Enter OTP" />
  </div>
  <div class="d-grid mb-4">
    <button id="verify-otp-button" class="btn btn-lg" style="background-color: #000; color: #fff;">Verify OTP</button>
  </div>
</div>
