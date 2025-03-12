const Component_ContactPopup = () => {
	document.addEventListener("DOMContentLoaded", () => {
		// Selektieren der spezifischen Link-Elemente
		const menuContactLink = document.querySelector(
			"#menu-item-526.popup-trigger a"
		);
		const callMeLink = document.querySelector("[data-popup='kontakt'] a");
		const popup = document.getElementById("contact-popup");
		const closeBtn = document.querySelector(".close-popup");

		if (!popup || !closeBtn) {
			console.warn("Popup-Elemente nicht gefunden");
			return;
		}

		// Eventlistener für den Menü-Kontakt-Link
		if (menuContactLink) {
			menuContactLink.addEventListener("click", (event) => {
				event.preventDefault();
				popup.classList.add("open");
			});
		}

		// Eventlistener für den "Call me" Link
		if (callMeLink) {
			callMeLink.addEventListener("click", (event) => {
				event.preventDefault();
				popup.classList.add("open");
			});
		}

		// Schließen des Popups bei Klick auf den Schließen-Button oder außerhalb des Popups
		popup.addEventListener("click", (event) => {
			if (event.target === popup || event.target.closest(".close-popup")) {
				popup.classList.remove("open");
			}
		});

		// Optional: Popup mit Escape-Taste schließen
		document.addEventListener("keydown", (event) => {
			if (event.key === "Escape" && popup.classList.contains("open")) {
				popup.classList.remove("open");
			}
		});
	});
};

export default Component_ContactPopup;
