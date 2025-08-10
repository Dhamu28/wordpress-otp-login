// Firebase config
const firebaseConfig = {
  apiKey: "AIzaSyA8LWtV3XKqQarzRU1FrG4NgewZMgL8NuU",
  authDomain: "dhamutech-otp-auth.firebaseapp.com",
  projectId: "dhamutech-otp-auth",
  storageBucket: "dhamutech-otp-auth.firebasestorage.app",
  messagingSenderId: "2543766791",
  appId: "1:2543766791:web:876fe0d67de1e7782689a2",
  measurementId: "G-3LH4MR3VQ6"
};

firebase.initializeApp(firebaseConfig);

// ✅ RecaptchaVerifier should be initialized only once
let recaptchaVerifier;

function initRecaptcha() {
  if (!recaptchaVerifier) {
    recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container', {
      size: 'normal',
      callback: function(response) {
        console.log('reCAPTCHA solved');
      },
      'expired-callback': function () {
        console.log('reCAPTCHA expired');
      }
    });
    recaptchaVerifier.render().then(function(widgetId) {
      window.recaptchaWidgetId = widgetId;
    });
  }
}

// ✅ Initialize recaptcha on window load
window.onload = function () {
  initRecaptcha();
};

// ✅ Send OTP
document.getElementById("send-otp-button").addEventListener("click", function () {
  const phoneNumber = document.getElementById("phone").value.trim();

  // Validate phone format
  if (!/^\+\d{10,15}$/.test(phoneNumber)) {
    alert("❌ Please enter a valid phone number with country code. (e.g., +919876543210)");
    return;
  }

  firebase.auth().signInWithPhoneNumber(phoneNumber, recaptchaVerifier)
    .then(function (confirmationResult) {
      window.confirmationResult = confirmationResult;
      alert("✅ OTP Sent");
    }).catch(function (error) {
      console.error("Firebase OTP error:", error);
      alert("❌ Failed to send OTP: " + error.message);
    });
});

// ✅ Verify OTP and login to WordPress
document.getElementById("verify-otp-button").addEventListener("click", function () {
  const code = document.getElementById("otp").value.trim();

  if (!code) {
    alert("❌ Please enter the OTP.");
    return;
  }

  window.confirmationResult.confirm(code).then(function (result) {
    const user = result.user;
    const uid = user.uid;
    const phone = user.phoneNumber;

    // Call WordPress backend to login or register
    fetch(dhamutech_otp_ajax.ajax_url, {
      method: "POST",
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=dhamutech_verify_firebase_user&uid=${uid}&phone=${phone}`
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        window.location.href = data.data.redirect;
      } else {
        alert("❌ Login failed.");
      }
    });
  }).catch(function (error) {
    console.error("OTP verification failed:", error);
    alert("❌ Incorrect OTP. Please try again.");
  });
});
