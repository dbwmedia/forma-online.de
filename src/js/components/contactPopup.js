const Component_ContactPopup = () => {
	document.addEventListener("DOMContentLoaded", () => {
		const contactTriggers = document.querySelectorAll(".popup-trigger a");
		const popup = document.getElementById("contact-popup");
		const closeBtn = document.querySelector(".close-popup");
		const slideoutCloseBtn = document.querySelector(".slideout-exit");

		if (!popup || !closeBtn) {
			return;
		}

		contactTriggers.forEach((link) => {
			link.addEventListener("click", (event) => {
				event.preventDefault();

				// Slideout schließen, falls aktiv
				const isInSlideout = link.closest("#generate-slideout-menu");
				if (isInSlideout && slideoutCloseBtn) {
					slideoutCloseBtn.click();
				}

				// Öffne das Popup
				popup.classList.add("open");
			});
		});

		popup.addEventListener("click", (event) => {
			if (event.target === popup || event.target.closest(".close-popup")) {
				popup.classList.remove("open");
			}
		});

		document.addEventListener("keydown", (event) => {
			if (event.key === "Escape" && popup.classList.contains("open")) {
				popup.classList.remove("open");
			}
		});
	});
};

export default Component_ContactPopup;
