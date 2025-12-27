document.addEventListener("DOMContentLoaded", () => {
  console.log("popup.js loaded");


  /* ======================================================
      GENEREL POPUP-LOGIK
      - Kun én popup må være åben ad gangen
  ====================================================== */
  // Åbner en popup og lukker automatisk alle andre
  function openModal(id) {

    // Luk alle allerede åbne popups
    document.querySelectorAll(".x-dialog.active").forEach(dialog => {
      dialog.classList.remove("active");
    });

    // Find og åbn den ønskede popup via ID
    const modal = document.getElementById(id);
    if (modal) modal.classList.add("active");
  }

  // Lukker en specifik popup
  function closeModal(modal) {
    modal?.classList.remove("active");
  }


  /* ======================================================
      ÅBN POPUPS VIA data-open
  ====================================================== */
  // Find alle elementer der kan åbne en popup
  document.querySelectorAll("[data-open]").forEach(button => {

    // Lyt efter klik på knappen/linket
    button.addEventListener("click", event => {
      event.preventDefault();

      // Åbn popup med ID angivet i data-open attributten
      openModal(button.dataset.open);
    });

  });


  /* ======================================================
      LUK POPUPS
  ====================================================== */
  // Luk popup når der klikkes på overlay eller X-knappen
  document.addEventListener("click", event => {
    if (
      event.target.classList.contains("x-dialog__overlay") ||
      event.target.classList.contains("x-dialog__close")
    ) {
      // Finder den popup klikket skete i og lukker den
      closeModal(event.target.closest(".x-dialog"));
    }
  });

  // Luk alle aktive popups når brugeren trykker ESC
  document.addEventListener("keydown", event => {
    if (event.key === "Escape") {
      document.querySelectorAll(".x-dialog.active").forEach(closeModal);
    }
  });


  /* ======================================================
    EDIT POST → POPUP
  ====================================================== */
  window.openEditPostPopup = function (postPk, text) {
      // Luk dropdown
      document.querySelectorAll(".post-dropdown.open")
          .forEach(d => d.classList.remove("open"));

      // Fyld popup
      document.getElementById("edit_post_pk").value = postPk;
      document.getElementById("editPostTextarea").value = text;

      // Aktivér submit
      document.getElementById("editPostSubmitBtn")?.removeAttribute("disabled");

      // Åbn popup
      document.getElementById("editPostModal")?.classList.add("active");
  };


  /* ======================================================
    EDIT COMMENT → POPUP
  ====================================================== */
  window.openEditCommentPopup = function (commentPk, text, postPk) {
      // Luk dropdown
      document.querySelectorAll(".comment-dropdown.open")
          .forEach(d => d.classList.remove("open"));

      // Fyld popup-form
      document.getElementById("edit_comment_pk").value = commentPk;
      document.getElementById("edit_post_pk").value = postPk;
      document.getElementById("editCommentTextarea").value = text;

      // Åbn popup
      document.getElementById("editCommentModal").classList.add("active");
  };


  /* ======================================================
    LUK EDIT COMMENT POPUP EFTER UPDATE SUBMIT
  ====================================================== */
  // Find edit comment-formularen inde i edit comment popup
  const editCommentForm = document.querySelector("#editCommentModal .edit-comment-form");

  if (editCommentForm) {
    // Når comment opdateres
    editCommentForm.addEventListener("submit", () => {
      // Luk popup med det samme, så brugeren oplever handlingen som responsiv
      document.getElementById("editCommentModal")?.classList.remove("active");
    });
  }


  /* ======================================================
    AUTO-ÅBN LOGIN POPUP EFTER SIGNUP
    URL: /?login=1
  ====================================================== */
  // Læs query parameters fra URL’en
  const params = new URLSearchParams(window.location.search);

  // Hvis URL’en indeholder login=1 → åbn login-popup automatisk
  if (params.get("login") === "1") {
    openModal("loginModal");
  }
});
