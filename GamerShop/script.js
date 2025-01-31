document.getElementById("login-tab").addEventListener("click", () => {
  document.getElementById("login-form").classList.remove("d-none");
  document.getElementById("register-form").classList.add("d-none");
  document.getElementById("login-tab").classList.add("active");
  document.getElementById("register-tab").classList.remove("active");
});

document.getElementById("register-tab").addEventListener("click", () => {
  document.getElementById("register-form").classList.remove("d-none");
  document.getElementById("login-form").classList.add("d-none");
  document.getElementById("register-tab").classList.add("active");
  document.getElementById("login-tab").classList.remove("active");
});



document.addEventListener("DOMContentLoaded", function () {
  const loginTab = document.getElementById("login-tab");
  const registerTab = document.getElementById("register-tab");
  const loginForm = document.getElementById("login-form");
  const registerForm = document.getElementById("register-form");

  loginTab.addEventListener("click", function () {
      loginForm.classList.remove("d-none");
      registerForm.classList.add("d-none");
      loginTab.classList.add("active");
      registerTab.classList.remove("active");
  });

  registerTab.addEventListener("click", function () {
      loginForm.classList.add("d-none");
      registerForm.classList.remove("d-none");
      registerTab.classList.add("active");
      loginTab.classList.remove("active");
  });

  // Обработчик входа
  loginForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(loginForm);

      fetch("login.php", {
          method: "POST",
          body: formData,
      })
      .then(response => response.json())
      .then(data => {
          if (data.status === "success") {
              if (data.role === "admin") {
                  window.location.href = "admin.php";
              } else {
                  window.location.href = "index.php";
              }
          } else {
              alert(data.message);
          }
      });
  });

  // Обработчик регистрации
  registerForm.addEventListener("submit", function (e) {
      e.preventDefault();
      const formData = new FormData(registerForm);

      fetch("register.php", {
          method: "POST",
          body: formData,
      })
      .then(response => response.json())
      .then(data => {
          if (data.status === "success") {
              alert("Регистрация успешна! Теперь войдите в систему.");
              loginTab.click();
          } else {
              alert(data.message);
          }
      });
  });
});
