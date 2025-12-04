document.addEventListener("DOMContentLoaded", () => {
  console.log("Popup.js: DOM loaded");
  
  // ========== INITIALIZATION ==========
  // Ensure all popups are closed when page loads
  document.querySelectorAll('.x-dialog.active').forEach(dialog => {
    dialog.classList.remove('active');
  });
  
  // ========== SECTION 1: BASIC POPUP CONTROLS ==========
  console.log("Initializing basic popup controls...");
  
  const openButtons = document.querySelectorAll("[data-open]");
  const closeButtons = document.querySelectorAll(
    ".x-dialog__close, .x-dialog__overlay"
  );

  // Open popup
  openButtons.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      const target = btn.getAttribute("data-open");
      const dialog = document.getElementById(target);
      
      if (target === "createCommentModal") {
        const postId = btn.getAttribute("data-post-id");
        const postAuthor = btn.getAttribute("data-post-author");
        const postContent = btn.getAttribute("data-post-content");
        
        document.getElementById("comment_post_pk").value = postId;
        
        const preview = document.getElementById("originalPostPreview");
        if (preview) {
          preview.innerHTML = `
            <div class="post-author">${postAuthor}</div>
            <div class="post-content">${postContent}</div>
          `;
        }
        
        const textarea = document.getElementById("commentTextarea");
        if (textarea) {
          textarea.value = "";
          textarea.dispatchEvent(new Event('input'));
        }
      }
      
      dialog.classList.add("active");
    });
  });

  // Close dialog
  closeButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      btn.closest(".x-dialog").classList.remove("active");
    });
  });

  // ESC key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.x-dialog.active').forEach(dialog => {
        dialog.classList.remove('active');
      });
    }
  });

  // ========== SECTION 2: FORM VALIDATIONS ==========
  console.log("Setting up form validations...");
  
  // Create Post validation
  const postTextarea = document.querySelector('#createPostModal textarea');
  const postSubmitBtn = document.querySelector('#postSubmitBtn');
  
  if (postTextarea && postSubmitBtn) {
    postTextarea.addEventListener('input', function() {
      postSubmitBtn.disabled = !(this.value.trim().length > 0 && this.value.length <= 300);
    });
    postSubmitBtn.disabled = true;
  }

  // Create Comment validation
  const commentTextarea = document.querySelector('#createCommentModal textarea');
  const commentSubmitBtn = document.querySelector('#commentSubmitBtn');
  
  if (commentTextarea && commentSubmitBtn) {
    commentTextarea.addEventListener('input', function() {
      commentSubmitBtn.disabled = !(this.value.trim().length > 0 && this.value.length <= 280);
    });
    commentSubmitBtn.disabled = true;
  }

  // Edit Comment validation
  const editCommentTextarea = document.querySelector('#editCommentModal textarea');
  const editCommentSubmitBtn = document.querySelector('#editCommentSubmitBtn');
  
  if (editCommentTextarea && editCommentSubmitBtn) {
    editCommentTextarea.addEventListener('input', function() {
      editCommentSubmitBtn.disabled = !(this.value.trim().length > 0 && this.value.length <= 255);
    });
    setTimeout(() => {
      if (editCommentTextarea.value) {
        editCommentSubmitBtn.disabled = !(editCommentTextarea.value.trim().length > 0 && editCommentTextarea.value.length <= 255);
      } else {
        editCommentSubmitBtn.disabled = true;
      }
    }, 100);
  }

  // Edit Post validation
  const editPostTextarea = document.querySelector('#editPostModal textarea');
  const editPostSubmitBtn = document.querySelector('#editPostSubmitBtn');
  
  if (editPostTextarea && editPostSubmitBtn) {
    editPostTextarea.addEventListener('input', function() {
      const isValid = this.value.trim().length > 0 && this.value.length <= 300;
      editPostSubmitBtn.disabled = !isValid;
    });
    
    // Initialize button state based on existing text
    setTimeout(() => {
      if (editPostTextarea.value) {
        const isValid = editPostTextarea.value.trim().length > 0 && editPostTextarea.value.length <= 300;
        editPostSubmitBtn.disabled = !isValid;
      } else {
        editPostSubmitBtn.disabled = true;
      }
    }, 100);
  }

  // ========== SECTION 3: POPUP SUBMIT HANDLERS ==========
  console.log("Setting up popup submit handlers...");
  
  // Close popups on submit
  const postForm = document.querySelector('#createPostModal .post-form');
  if (postForm) postForm.addEventListener('submit', () => {
    document.getElementById('createPostModal')?.classList.remove('active');
  });
  
  const commentForm = document.querySelector('#createCommentModal .comment-form');
  if (commentForm) commentForm.addEventListener('submit', () => {
    document.getElementById('createCommentModal')?.classList.remove('active');
  });
  
  const editCommentForm = document.querySelector('#editCommentModal .edit-comment-form');
  if (editCommentForm) editCommentForm.addEventListener('submit', () => {
    document.getElementById('editCommentModal')?.classList.remove('active');
  });
  
  // TILFØJ DENNE FOR EDIT POST FORM:
  const editPostForm = document.querySelector('#editPostModal .edit-post-form');
  if (editPostForm) editPostForm.addEventListener('submit', () => {
    // Luk popup med delay for at sikre mix.js håndterer submission
    setTimeout(() => {
      document.getElementById('editPostModal')?.classList.remove('active');
    }, 100);
  });

  console.log("Basic popup handlers initialized");

  // ========== SECTION 4: COMMENT DROPDOWN HANDLERS ==========
  console.log("Setting up comment dropdown handlers...");
  
  // Function to toggle dropdown visibility
  function toggleCommentDropdown(commentId) {
    console.log('Toggling comment dropdown for:', commentId);
    
    const dropdown = document.getElementById('dropdown-' + commentId);
    if (!dropdown) {
      console.error('Comment dropdown not found for:', commentId);
      return;
    }
    
    // Close all other dropdowns
    document.querySelectorAll('.comment-dropdown.visible, .post-dropdown.visible').forEach(d => {
      if (d.id !== 'dropdown-' + commentId) {
        d.classList.remove('visible');
      }
    });
    
    // Toggle this dropdown
    dropdown.classList.toggle('visible');
  }
  
  // Handle edit button click
  function handleEditClick(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const commentId = event.target.getAttribute('data-comment-id');
    const commentText = event.target.getAttribute('data-comment-text') || '';
    
    console.log('Edit clicked for comment:', commentId);
    
    // Set values in edit form
    const editPkInput = document.getElementById('edit_comment_pk');
    const editTextarea = document.getElementById('editCommentTextarea');
    
    if (editPkInput && editTextarea) {
      editPkInput.value = commentId;
      editTextarea.value = commentText;
      
      // Trigger validation
      editTextarea.dispatchEvent(new Event('input'));
      
      // Open edit popup
      document.getElementById('editCommentModal')?.classList.add('active');
      
      // Focus textarea
      setTimeout(() => {
        editTextarea.focus();
        editTextarea.setSelectionRange(commentText.length, commentText.length);
      }, 100);
    }
    
    // Close dropdown
    const dropdown = document.getElementById('dropdown-' + commentId);
    if (dropdown) {
      dropdown.classList.remove('visible');
    }
  }
  
  // Handle delete button click with confirmation
  function handleDeleteClick(event) {
    if (!confirm('Are you sure you want to delete this comment?')) {
      event.preventDefault();
      
      // Close dropdown
      const form = event.target.closest('.dropdown-item-form');
      const commentId = form.querySelector('input[name="comment_pk"]').value;
      const dropdown = document.getElementById('dropdown-' + commentId);
      if (dropdown) {
        dropdown.classList.remove('visible');
      }
    }
  }
  
  // Initialize comment dropdown functionality
  function initCommentDropdowns() {
    console.log('Initializing comment dropdowns...');
    
    // 1. Add click handlers to menu buttons
    document.querySelectorAll('.comment-menu-btn').forEach(button => {
      // Remove existing listeners by cloning
      const newButton = button.cloneNode(true);
      if (button.parentNode) {
        button.parentNode.replaceChild(newButton, button);
      }
      
      // Add click event
      newButton.addEventListener('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        
        const commentId = this.getAttribute('data-comment-id');
        if (commentId) {
          toggleCommentDropdown(commentId);
        }
      });
    });
    
    // 2. Add click handlers to edit buttons
    document.querySelectorAll('.dropdown-item[data-action="edit"]').forEach(button => {
      button.addEventListener('click', handleEditClick);
    });
    
    // 3. Add submit handlers to delete forms
    document.querySelectorAll('.dropdown-item-form').forEach(form => {
      form.addEventListener('submit', handleDeleteClick);
    });
    
    console.log('Comment dropdowns initialized');
  }
  
  // ========== SECTION 5: POST DROPDOWN HANDLERS ==========
  console.log("Setting up post dropdown handlers...");
  
  // Function to toggle post dropdown visibility
  function togglePostDropdown(postId) {
    console.log('Toggling post dropdown for:', postId);
    
    const dropdown = document.getElementById('post-dropdown-' + postId);
    if (!dropdown) {
      console.error('Post dropdown not found for post:', postId);
      return;
    }
    
    // Close all other dropdowns (both post and comment)
    document.querySelectorAll('.post-dropdown.visible, .comment-dropdown.visible').forEach(d => {
      if (d.id !== 'post-dropdown-' + postId) {
        d.classList.remove('visible');
      }
    });
    
    // Toggle this dropdown
    dropdown.classList.toggle('visible');
  }
  
  // Handle edit post button click
  function handleEditPostClick(event) {
    event.preventDefault();
    event.stopPropagation();
    
    const postId = event.target.getAttribute('data-post-id');
    const postText = event.target.getAttribute('data-post-text') || '';
    
    console.log('Edit post clicked for post:', postId);
    
    // Set values in edit post form
    const editPkInput = document.getElementById('edit_post_pk');
    const editTextarea = document.getElementById('editPostTextarea');
    
    if (editPkInput && editTextarea) {
      editPkInput.value = postId;
      editTextarea.value = postText;
      
      // Trigger validation
      editTextarea.dispatchEvent(new Event('input'));
      
      // Open edit post popup
      document.getElementById('editPostModal')?.classList.add('active');
      
      // Focus textarea
      setTimeout(() => {
        editTextarea.focus();
        editTextarea.setSelectionRange(postText.length, postText.length);
      }, 100);
    }
    
    // Close dropdown
    const dropdown = document.getElementById('post-dropdown-' + postId);
    if (dropdown) {
      dropdown.classList.remove('visible');
    }
  }
  
  // Handle delete post button click with confirmation
  function handleDeletePostClick(event) {
    if (!confirm('Are you sure you want to delete this post? All comments will also be deleted.')) {
      event.preventDefault();
      
      // Close dropdown
      const form = event.target.closest('.dropdown-item-form');
      const postId = form.querySelector('input[name="post_pk"]').value;
      const dropdown = document.getElementById('post-dropdown-' + postId);
      if (dropdown) {
        dropdown.classList.remove('visible');
      }
    }
  }
  
  // Initialize post dropdown functionality
  function initPostDropdowns() {
    console.log('Initializing post dropdowns...');
    
    // 1. Add click handlers to post menu buttons
    document.querySelectorAll('.post-menu-btn').forEach(button => {
      // Remove existing listeners by cloning
      const newButton = button.cloneNode(true);
      if (button.parentNode) {
        button.parentNode.replaceChild(newButton, button);
      }
      
      // Add click event
      newButton.addEventListener('click', function(event) {
        event.preventDefault();
        event.stopPropagation();
        
        const postId = this.getAttribute('data-post-id');
        if (postId) {
          togglePostDropdown(postId);
        }
      });
    });
    
    // 2. Add click handlers to edit post buttons
    document.querySelectorAll('.dropdown-item[data-action="edit-post"]').forEach(button => {
      button.addEventListener('click', handleEditPostClick);
    });
    
    // 3. Add submit handlers to delete post forms
    document.querySelectorAll('.post-dropdown .dropdown-item-form').forEach(form => {
      form.addEventListener('submit', handleDeletePostClick);
    });
    
    console.log('Post dropdowns initialized');
  }
  
  // ========== SECTION 6: DROPDOWN CLOSE HANDLERS ==========
  // Close dropdowns when clicking outside
  function closeAllDropdowns(event) {
    // If click is NOT on a menu button or inside a dropdown
    if (!event.target.closest('.comment-menu-btn') && 
        !event.target.closest('.comment-dropdown') &&
        !event.target.closest('.post-menu-btn') && 
        !event.target.closest('.post-dropdown')) {
      document.querySelectorAll('.post-dropdown.visible, .comment-dropdown.visible').forEach(d => {
        d.classList.remove('visible');
      });
    }
  }
  
  // Add global click listener for closing dropdowns
  document.addEventListener('click', closeAllDropdowns);
  
  // ========== SECTION 7: INITIALIZE EVERYTHING ==========
  console.log("Initializing all dropdown handlers...");
  
  // Initialize dropdowns
  initCommentDropdowns();
  initPostDropdowns();
  
  // Re-initialize after mix.js reloads (if using mix)
  if (typeof mix !== 'undefined') {
    document.addEventListener('mix:after', function() {
      console.log('Mix reloaded, re-initializing dropdowns...');
      setTimeout(() => {
        initCommentDropdowns();
        initPostDropdowns();
      }, 100);
    });
  }
  
  console.log("Popup.js: All handlers initialized successfully!");
});

// ========== PROFILE POPUP VALIDATION ==========
console.log("Setting up profile popup validation...");

// Function to validate profile form
function validateProfileForm() {
    const email = document.getElementById('user_email')?.value.trim();
    const username = document.getElementById('user_username')?.value.trim();
    const fullName = document.getElementById('user_full_name')?.value.trim();
    
    // Email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isEmailValid = emailRegex.test(email);
    
    // Username validation: 3-20 chars, letters, numbers, underscore
    const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
    const isUsernameValid = usernameRegex.test(username);
    
    // Full name validation: 2-50 chars
    const isFullNameValid = fullName.length >= 2 && fullName.length <= 50;
    
    return isEmailValid && isUsernameValid && isFullNameValid;
}

// Initialize profile form validation
function initProfileValidation() {
    const profileForm = document.querySelector('.edit-profile-form');
    const profileSubmitBtn = document.querySelector('.update-profile-btn');
    
    if (profileForm && profileSubmitBtn) {
        const inputs = profileForm.querySelectorAll('input[type="email"], input[type="text"]');
        
        // Real-time validation on input
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const isValid = validateProfileForm();
                profileSubmitBtn.disabled = !isValid;
            });
        });
        
        // Initial validation
        const initialValid = validateProfileForm();
        profileSubmitBtn.disabled = !initialValid;
    }
}

// ========== PROFILE POPUP OPEN HANDLER ==========
document.addEventListener("DOMContentLoaded", () => {
    // ... eksisterende kode ...
    
    // Tilføj profile validation når popup åbnes
    openButtons.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const target = btn.getAttribute("data-open");
            
            // ... eksisterende kode for andre popups ...
            
            if (target === "editProfileModal") {
                // Initialize validation for profile popup
                setTimeout(initProfileValidation, 100);
            }
        });
    });
    
    // ... resten af din eksisterende kode ...
    
    // Tilføj: Close profile popup on submit
    const profileForm = document.querySelector('#editProfileModal .edit-profile-form');
    if (profileForm) profileForm.addEventListener('submit', function() {
        setTimeout(() => {
            document.getElementById('editProfileModal')?.classlist.remove('active');
        }, 100);
    });
});