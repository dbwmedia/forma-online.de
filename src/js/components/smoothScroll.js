import Lenis from "lenis";

const Component_SmoothScroll = () => {
	const prefersReducedMotion = window.matchMedia(
		"(prefers-reduced-motion: reduce)"
	).matches;

	if (prefersReducedMotion) return;

	try {
		const lenis = new Lenis();

		function raf(time) {
			lenis.raf(time);
			requestAnimationFrame(raf);
		}
		requestAnimationFrame(raf);
	} catch (error) {
		document.documentElement.style.overflow = "auto";
		document.body.style.overflow = "auto";
	}
};

export default Component_SmoothScroll;
