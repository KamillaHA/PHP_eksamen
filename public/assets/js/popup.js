document.addEventListener("DOMContentLoaded", () => {
  console.log("popup.js loaded");

  /* ======================================================
      GENEREL POPUP-LOGIK
      - Alle popups bruger .x-dialog + .active
      - Kun én popup må være åben ad gangen
  ====================================================== */

  // Åbn en popup og luk alle andre
  function openModal(id) {
    document.querySelectorAll(".x-dialog.active").forEach(dialog => {
      dialog.classList.remove("active");
    });

    document.getElementById(id)?.classList.add("active");
  }

  // Luk en specifik popup
  function closeModal(modal) {
    modal?.classList.remove("active");
  }

  /* ======================================================
      ÅBN POPUPS VIA data-open ATTRIBUT
      Eksempel:
      <button data-open="editProfileModal">
  ====================================================== */

  document.querySelectorAll("[data-open]").forEach(button => {
    button.addEventListener("click", event => {
      event.preventDefault();

      const targetId = button.dataset.open;
      if (!targetId) return;

      openModal(targetId);

      // Hvis det er profile popup → init validering
      if (targetId === "editProfileModal") {
        setTimeout(initProfileValidation, 50);
      }
    });
  });

  /* ======================================================
      LUK POPUPS
      - Klik på overlay
      - Klik på X-knap
      - Tryk ESC
  ====================================================== */

  document.addEventListener("click", event => {
    if (
      event.target.classList.contains("x-dialog__overlay") ||
      event.target.classList.contains("x-dialog__close")
    ) {
      closeModal(event.target.closest(".x-dialog"));
    }
  });

  document.addEventListener("keydown", event => {
    if (event.key === "Escape") {
      document.querySelectorAll(".x-dialog.active").forEach(closeModal);
    }
  });

  /* ======================================================
      PROFIL FORM VALIDERING (CLIENT SIDE)
      Matcher PHP-validering
  ====================================================== */

  function validateProfileForm() {
    const email = document.getElementById("user_email")?.value.trim();
    const username = document.getElementById("user_username")?.value.trim();
    const fullName = document.getElementById("user_full_name")?.value.trim();

    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    const usernameValid = /^[a-zA-Z0-9_]{3,20}$/.test(username);
    const fullNameValid = fullName.length >= 2 && fullName.length <= 50;

    return emailValid && usernameValid && fullNameValid;
  }

  function initProfileValidation() {
    const form = document.querySelector(".edit-profile-form");
    const submitBtn = document.querySelector(".update-profile-btn");

    if (!form || !submitBtn) return;

    form.querySelectorAll("input").forEach(input => {
      input.addEventListener("input", () => {
        submitBtn.disabled = !validateProfileForm();
      });
    });

    // Initial state
    submitBtn.disabled = !validateProfileForm();
  }

  /* ======================================================
      LUK EDIT PROFILE POPUP EFTER SUBMIT
      (PHP håndterer redirect / response)
  ====================================================== */

  const editProfileForm = document.querySelector(
    "#editProfileModal .edit-profile-form"
  );

  if (editProfileForm) {
    editProfileForm.addEventListener("submit", () => {
      setTimeout(() => {
        closeModal(document.getElementById("editProfileModal"));
      }, 100);
    });
  }

  /* ======================================================
      DELETE PROFILE FLOW
      - Luk edit popup
      - Åbn confirm delete popup
  ====================================================== */

  document.querySelectorAll(".delete-profile-btn").forEach(button => {
    button.addEventListener("click", () => {
      openModal("confirmDeleteProfileModal");
    });
  });

  /* ======================================================
      CANCEL DELETE → TILBAGE TIL EDIT PROFILE
  ====================================================== */

  document.querySelectorAll(".cancel-delete-profile").forEach(button => {
    button.addEventListener("click", () => {
      openModal("editProfileModal");
    });
  });
  
    /* ======================================================
      PROFILE COVER UPLOAD
  ====================================================== */
  document.querySelectorAll(".profile-cover").forEach(cover => {
      cover.addEventListener("click", () => {
          const input = cover.querySelector('input[type="file"]');
          if (input) {
              input.click();
          }
      });
  });

});
