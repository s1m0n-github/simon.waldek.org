document.querySelectorAll(".transition-link").forEach(link => {
	link.addEventListener("click", (e) => {
		e.preventDefault();
		const targetUrl = link.getAttribute("href");

		// Seite überlagern
		const overlay = document.createElement("div");
		overlay.id = "page-overlay";

		const clonedBody = document.body.cloneNode(true);
		clonedBody.className = document.body.className;
		overlay.appendChild(clonedBody);

		const headStyles = document.head.querySelectorAll('link[rel="stylesheet"], style');
		headStyles.forEach(style => {
			overlay.appendChild(style.cloneNode(true));
		});

		document.body.appendChild(overlay);

		// Neue Seite vorbereiten
		const newPage = document.createElement("iframe");
		newPage.id = "new-page";
		newPage.src = targetUrl;

		// Hier: Prüfen, ob wir zur Startseite zurückgehen
		const isReturningToHome =
			(targetUrl === "../index.html" && window.location.pathname.includes("/about/")) ||
			targetUrl === "/";

		newPage.style.position = "fixed";
		newPage.style.left = "0";
		newPage.style.width = "100%";
		newPage.style.height = "100%";
		newPage.style.border = "none";
		newPage.style.zIndex = "100";
		newPage.style.transition = "top 1s ease-in-out";

		// Richtung festlegen
		newPage.style.top = isReturningToHome ? "-100%" : "100%";

		document.body.appendChild(newPage);

		// Starte die Animation, sobald iframe geladen ist
		newPage.onload = () => {
			requestAnimationFrame(() => {
				newPage.style.top = "0";
			});

			setTimeout(() => {
				window.location.href = targetUrl;
			}, 1000);
		};
	});
});

window.addEventListener("pageshow", (event) => {
	// Wenn Seite aus dem Cache kommt (typisch bei Back/Forward)
	if (event.persisted || performance.getEntriesByType("navigation")[0].type === "back_forward") {
		// Seite wirklich neu laden
		window.location.reload();
	}
});
