document.addEventListener("DOMContentLoaded", function () {
    const navToggle = document.querySelector(".nav-toggle");
    const mobileMenu = document.getElementById("mobileMenu");

    // toggle hamburger menu
    if (navToggle && mobileMenu) {
        navToggle.addEventListener("click", function () {
            mobileMenu.classList.toggle("active");
            const expanded = navToggle.getAttribute("aria-expanded") === "true";
            navToggle.setAttribute("aria-expanded", (!expanded).toString());
        });
    }

    // dropdown Tim & Pembalap di mobile
    document.querySelectorAll(".m-dd-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            const targetId = btn.getAttribute("data-target");
            const panel = document.getElementById(targetId);
            if (!panel) return;

            document.querySelectorAll(".m-dd-panel").forEach(p => {
                if (p !== panel) p.classList.remove("active");
            });

            panel.classList.toggle("active");
        });
    });

    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".swipe-row").forEach((row) => {
            const items = Array.from(row.children);

            function setActive() {
                const rowRect = row.getBoundingClientRect();
                const centerX = rowRect.left + rowRect.width / 2;

                let bestItem = null;
                let bestDist = Infinity;

                items.forEach((it) => {
                    const r = it.getBoundingClientRect();
                    const itCenter = r.left + r.width / 2;
                    const dist = Math.abs(centerX - itCenter);
                    if (dist < bestDist) {
                        bestDist = dist;
                        bestItem = it;
                    }
                });

                items.forEach((it) => it.classList.remove("is-active"));
                if (bestItem) bestItem.classList.add("is-active");
            }

            // update saat scroll swipe
            let t;
            row.addEventListener("scroll", () => {
                clearTimeout(t);
                t = setTimeout(setActive, 80); // tunggu berhenti swipe sedikit
            });

            // set awal
            setActive();
            window.addEventListener("resize", setActive);
        });
    });


});
