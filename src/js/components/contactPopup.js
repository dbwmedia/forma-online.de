const Component_ContactPopup = () => {
	document.addEventListener("DOMContentLoaded", () => {
		const popupTrigger = document.querySelector(".popup-trigger");
		const popup = document.getElementById("contact-popup");
		const closeBtn = document.querySelector(".close-popup");

		if (!popupTrigger || !popup || !closeBtn) {
			return;
		}

		// Öffnen des Popups
		popupTrigger.addEventListener("click", (event) => {
			event.preventDefault();
			popup.classList.add("open");
		});

		// Schließen des Popups bei Klick auf den Schließen-Button oder außerhalb des Popups
		popup.addEventListener("click", (event) => {
			if (
				event.target === popup ||
				event.target.classList.contains("close-popup")
			) {
				popup.classList.remove("open");
			}
		});
	});
};

export default Component_ContactPopup;
