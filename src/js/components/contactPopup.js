const Component_ContactPopup = () => {
	document.addEventListener("DOMContentLoaded", () => {
		const contactTriggers = document.querySelectorAll(".popup-trigger a");
		const popup = document.getElementById("contact-popup");
		const closeBtn = document.querySelector(".close-popup");
		const slideoutCloseBtn = document.querySelector(".slideout-exit");

		console.log(
			"📌 Anzahl gefundener Kontakt-Trigger (.popup-trigger a):",
			contactTriggers.length
		);
		console.log("📋 Gefundene Trigger-Links:", contactTriggers);
		console.log("📦 Popup-Element:", popup);
		console.log("❌ Close-Button:", closeBtn);
		console.log("⬅️ Slideout-Schließen-Button:", slideoutCloseBtn);

		if (!popup || !closeBtn) {
			console.warn("⚠️ Popup-Elemente nicht gefunden.");
			return;
		}

		contactTriggers.forEach((link, index) => {
			console.log(
				`➕ Füge Eventlistener zu Kontakt-Link ${index} hinzu:`,
				link
			);
			link.addEventListener("click", (event) => {
				event.preventDefault();
				console.log(`✅ Kontakt-Link ${index} geklickt`);

				// Slideout schließen, falls aktiv
				const isInSlideout = link.closest("#generate-slideout-menu");
				if (isInSlideout && slideoutCloseBtn) {
					console.log("🔒 Kontakt-Link ist im Slideout-Menü – schließe Menü.");
					slideoutCloseBtn.click();
				}

				// Öffne das Popup
				popup.classList.add("open");
				console.log("🚪 Popup geöffnet");
			});
		});

		popup.addEventListener("click", (event) => {
			if (event.target === popup || event.target.closest(".close-popup")) {
				console.log("❎ Popup wird geschlossen");
				popup.classList.remove("open");
			}
		});

		document.addEventListener("keydown", (event) => {
			if (event.key === "Escape" && popup.classList.contains("open")) {
				console.log("❎ Popup wird mit ESC geschlossen");
				popup.classList.remove("open");
			}
		});
	});
};

export default Component_ContactPopup;
