// Vis scroll-top knap når der scrolles ned
window.addEventListener('scroll', () => {
    const scrollBtn = document.getElementById('scrollTop');
    if (window.pageYOffset > 300) {
        scrollBtn.classList.add('show');
    } else {
        scrollBtn.classList.remove('show');
    }
});


// Burger menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const burger = document.querySelector('.burger');
    const nav = document.querySelector('nav');
    
    if (burger && nav) {
        burger.addEventListener('click', function() {
            burger.classList.toggle('open');
            nav.classList.toggle('active');
        });
    }
});

//----------------------------------------- Like button med SVG skift
document.addEventListener("click", async (e) => {
    const like = e.target.closest(".action.like");
    if (!like) return;

    e.preventDefault();
    e.stopPropagation();

    const postId = like.dataset.postId;
    if (!postId) return;

    const svg = like.querySelector("svg");
    const path = svg.querySelector("path");
    const countEl = like.querySelector(".like-count");

  // Visuel feedback før API-kald
    const wasLiked = like.classList.contains("is-liked");
    const newLiked = !wasLiked;

  // Opdater UI med det samme for bedre UX
    like.classList.toggle("is-liked");
    like.setAttribute("aria-pressed", newLiked);

  // Skift SVG path mellem outline og filled
    if (newLiked) {
    // Skift til udfyldt (filled) hjerte - DIN VERSION
    path.setAttribute("d", "M20.884 13.19c-1.351 2.48-4.001 5.12-8.379 7.67l-.503.3-.504-.3c-4.379-2.55-7.029-5.19-8.382-7.67-1.36-2.5-1.41-4.86-.514-6.67.887-1.79 2.647-2.91 4.601-3.01 1.651-.09 3.368.56 4.798 2.01 1.429-1.45 3.146-2.1 4.796-2.01 1.954.1 3.714 1.22 4.601 3.01.896 1.81.846 4.17-.514 6.67z");
    } else {
    // Skift til outline hjerte
    path.setAttribute("d", "M16.697 5.5c-1.222-.06-2.679.51-3.89 2.16l-.805 1.09-.806-1.09C9.984 6.01 8.526 5.44 7.304 5.5c-1.243.07-2.349.78-2.91 1.91-.552 1.12-.633 2.78.479 4.82 1.074 1.97 3.257 4.27 7.129 6.61 3.87-2.34 6.052-4.64 7.126-6.61 1.111-2.04 1.03-3.7.477-4.82-.561-1.13-1.666-1.84-2.908-1.91zm4.187 7.69c-1.351 2.48-4.001 5.12-8.379 7.67l-.503.3-.504-.3c-4.379-2.55-7.029-5.19-8.382-7.67-1.36-2.5-1.41-4.86-.514-6.67.887-1.79 2.647-2.91 4.601-3.01 1.651-.09 3.368.56 4.798 2.01 1.429-1.45 3.146-2.1 4.796-2.01 1.954.1 3.714 1.22 4.601 3.01.896 1.81.846 4.17-.514 6.67z");
    }

    if (countEl) {
    const n = parseInt(countEl.textContent, 10) || 0;
    countEl.textContent = n + (newLiked ? 1 : -1);
    }

  // Send API-kald baseret på om vi liker eller unlike
    const apiEndpoint = newLiked ? '/api/api-like-post.php' : '/api/api-unlike-post.php';

    try {
    const formData = new FormData();
    formData.append('post_pk', postId);
    
    const response = await fetch(apiEndpoint, {
        method: 'POST',
        body: formData
    });

    if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`);
    }

} catch (error) {
    console.error('Error:', error);
    // Rollback ved fejl
    like.classList.toggle("is-liked");
    like.setAttribute("aria-pressed", wasLiked);
    
    // Skift SVG path tilbage
    if (wasLiked) {
      // Tilbage til filled
        path.setAttribute("d", "M20.884 13.19c-1.351 2.48-4.001 5.12-8.379 7.67l-.503.3-.504-.3c-4.379-2.55-7.029-5.19-8.382-7.67-1.36-2.5-1.41-4.86-.514-6.67.887-1.79 2.647-2.91 4.601-3.01 1.651-.09 3.368.56 4.798 2.01 1.429-1.45 3.146-2.1 4.796-2.01 1.954.1 3.714 1.22 4.601 3.01.896 1.81.846 4.17-.514 6.67z");
    } else {
      // Tilbage til outline
        path.setAttribute("d", "M16.697 5.5c-1.222-.06-2.679.51-3.89 2.16l-.805 1.09-.806-1.09C9.984 6.01 8.526 5.44 7.304 5.5c-1.243.07-2.349.78-2.91 1.91-.552 1.12-.633 2.78.479 4.82 1.074 1.97 3.257 4.27 7.129 6.61 3.87-2.34 6.052-4.64 7.126-6.61 1.111-2.04 1.03-3.7.477-4.82-.561-1.13-1.666-1.84-2.908-1.91zm4.187 7.69c-1.351 2.48-4.001 5.12-8.379 7.67l-.503.3-.504-.3c-4.379-2.55-7.029-5.19-8.382-7.67-1.36-2.5-1.41-4.86-.514-6.67.887-1.79 2.647-2.91 4.601-3.01 1.651-.09 3.368.56 4.798 2.01 1.429-1.45 3.146-2.1 4.796-2.01 1.954.1 3.714 1.22 4.601 3.01.896 1.81.846 4.17-.514 6.67z");
    }
    
    if (countEl) {
        countEl.textContent = n;
    }
    
    alert('Kunne ikke like/unlike posten. Prøv igen.');
    }
});