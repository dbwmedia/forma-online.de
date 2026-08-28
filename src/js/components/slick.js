const HeroSlider = () => {
	const slider = document.querySelector(".slider--hero");
	if (!slider) return;

	const items = [...slider.children];
	if (!items.length) return;

	// Wrap items in track
	const track = document.createElement("div");
	track.className = "slider-track";

	items.forEach((item) => {
		item.classList.add("slider-slide");
		track.appendChild(item);
	});

	// Clone items for seamless infinite loop
	items.forEach((item) => {
		const clone = item.cloneNode(true);
		clone.setAttribute("aria-hidden", "true");
		track.appendChild(clone);
	});

	slider.appendChild(track);

	// Calculate slide width and set CSS variables
	const updateDimensions = () => {
		const containerWidth = slider.offsetWidth;
		const slideWidth = containerWidth * 0.83;
		slider.style.setProperty("--slide-width", slideWidth + "px");
		slider.style.setProperty("--slide-count", items.length);
	};

	updateDimensions();
	window.addEventListener("resize", updateDimensions);

	// Pause on hover
	slider.addEventListener("mouseenter", () => {
		track.style.animationPlayState = "paused";
	});

	slider.addEventListener("mouseleave", () => {
		track.style.animationPlayState = "running";
	});
};

export default HeroSlider;
