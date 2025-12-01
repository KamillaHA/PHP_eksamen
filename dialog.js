document.addEventListener("DOMContentLoaded", () => {
  const openButtons = document.querySelectorAll("[data-open]");
  const closeButtons = document.querySelectorAll(
    ".x-dialog__close, .x-dialog__overlay"
  );

  // Open dialog
  openButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const target = btn.getAttribute("data-open");
      document.getElementById(target).classList.add("active");
    });
  });

  // Close dialog
  closeButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      btn.closest(".x-dialog").classList.remove("active");
    });
  });

  // Post form validation
  const postTextarea = document.querySelector('#createPostModal textarea');
  const postSubmitBtn = document.querySelector('#postSubmitBtn');
  
  if (postTextarea && postSubmitBtn) {
    postTextarea.addEventListener('input', function() {
      const isValid = this.value.trim().length > 0 && this.value.length <= 300;
      postSubmitBtn.disabled = !isValid;
    });
    
    // Initialize button state
    postSubmitBtn.disabled = true;
  }

  // Luk popup når formen submitter
  const postForm = document.querySelector('#createPostModal .post-form');
  if (postForm) {
    postForm.addEventListener('submit', () => {
      document.getElementById('createPostModal')?.classList.remove('active');
    });
  }
});
