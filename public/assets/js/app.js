/* ======================================================
    SCROLL-TOP KNAP
====================================================== */
// Lyt efter scroll på siden
window.addEventListener("scroll", () => {
    // Find "scroll to top"-knappen
    const btn = document.getElementById("scrollTop");
    if (!btn) return; // Hvis knappen ikke findes, gør ingenting

    // Vis knappen når man har scrollet mere end 300px ned
    btn.classList.toggle("show", window.pageYOffset > 300);
});


/* ======================================================
    BURGER MENU
====================================================== */
document.addEventListener("DOMContentLoaded", () => {
    const body = document.body;
    const topbar = document.querySelector(".mobile-topbar");
    const nav = document.querySelector("nav");
    const overlay = document.querySelector(".nav-overlay");

    if (!topbar || !nav) return;

    const openNav = () => body.classList.add("nav-open");
    const closeNav = () => body.classList.remove("nav-open");

    // Delegated avatar click
    topbar.addEventListener("click", (e) => {
        const burger = e.target.closest(".avatar-burger");
        if (!burger) return;

        e.preventDefault();
        e.stopImmediatePropagation();
        body.classList.contains("nav-open") ? closeNav() : openNav();
    });

    nav.addEventListener("click", (e) => {
        // Stop kun klik på selve navigationen, ikke posts
        if (e.target.closest(".post-menu") || e.target.closest(".post-dropdown")) {
            return;
        }
        e.stopPropagation();
    });

    // Overlay click → luk
    if (overlay) {
        overlay.addEventListener("click", () => {
            closeNav();
        });
    }

    // Nav link click → luk EFTER navigation
    nav.querySelectorAll("a").forEach(a => {
        a.addEventListener("click", () => {
            closeNav();
        });
    });

    // Luk nav når der klikkes på POST-knappen (modal)
    nav.querySelectorAll("[data-open]").forEach(btn => {
        btn.addEventListener("click", () => {
            body.classList.remove("nav-open");
        });
    });

    // ESC
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") closeNav();
    });
});


/* ======================================================
    LIKE BUTTON (POSTS)
====================================================== */
document.addEventListener("click", async (event) => {
    // Reager kun hvis der klikkes på en like-knap
    const likeBtn = event.target.closest(".action.like");
    if (!likeBtn) return;

    event.preventDefault();
    event.stopPropagation();

    // Hent post-id fra data-attribut
    const postId = likeBtn.dataset.postId;
    if (!postId) return;

    const countEl = likeBtn.querySelector(".like-count");
    const wasLiked = likeBtn.classList.contains("is-liked");

    // Opdatér UI med det samme
    likeBtn.classList.toggle("is-liked");

    // Opdatér like-tæller visuelt
    if (countEl) {
        const n = parseInt(countEl.textContent, 10) || 0;
        countEl.textContent = wasLiked ? n - 1 : n + 1;
    }

    // Vælg endpoint baseret på nuværende state
    const endpoint = wasLiked ? "/unlike" : "/like";

    try {
        // Send POST request i baggrunden
        const fd = new FormData();
        fd.append("post_fk", postId);

        await fetch(endpoint, { method: "POST", body: fd });
    } catch {
        // Hvis request fejler → rul UI tilbage
        likeBtn.classList.toggle("is-liked");
        alert("Like fejlede – prøv igen");
    }
});


/* ======================================================
    POST DROPDOWN (⋮)
====================================================== */
window.togglePostDropdown = function (postPk) {
    // Luk alle andre åbne post-dropdowns (kun én må være åben)
    document.querySelectorAll(".post-dropdown.open")
        .forEach(d => d.classList.remove("open"));

    // Find dropdown til det specifikke post og toggle den
    const dropdown = document.getElementById("post-dropdown-" + postPk);
    if (dropdown) dropdown.classList.toggle("open");
};

document.addEventListener("click", event => {
    // IGNORÉR clicks på like-knapper
    if (event.target.closest(".action.like")) return;

    // IGNORÉR clicks på post-menu
    if (
        event.target.closest(".post-menu-btn") ||
        event.target.closest(".post-dropdown")
    ) return;

    document.querySelectorAll(".post-dropdown.open")
        .forEach(d => d.classList.remove("open"));
});


/* ======================================================
    COMMENT DROPDOWN (⋮)
====================================================== */
window.toggleCommentDropdown = function (commentPk) {
    // Luk alle andre åbne comment-dropdowns
    document.querySelectorAll(".comment-dropdown.open")
        .forEach(d => d.classList.remove("open"));

    // Find dropdown til den specifikke kommentar og toggle den
    const dropdown = document.getElementById("dropdown-" + commentPk);
    if (dropdown) dropdown.classList.toggle("open");
};

document.addEventListener("click", event => {
    // Hvis der klikkes på menu-knappen eller selve dropdown → gør ingenting
    if (
        event.target.closest(".comment-menu-btn") ||
        event.target.closest(".comment-dropdown")
    ) return;

    // Klik udenfor → luk alle åbne comment-dropdowns
    document.querySelectorAll(".comment-dropdown.open")
        .forEach(d => d.classList.remove("open"));
});


/* ======================================================
    BACK TO FEED
====================================================== */
document.addEventListener("click", (e) => {
    const backBtn = e.target.closest(".js-back");
    if (!backBtn) return;

    e.preventDefault();
    window.history.back();
});