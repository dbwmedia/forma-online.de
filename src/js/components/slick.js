import "../../../node_modules/slick-carousel/slick/slick.min";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

let animationFrame; // Variable für die Pause-Funktion

const Slick = () => {
	// Hero-Slider (läuft ohne Pause)
	jQuery(".slider--hero").slick({
		dots: false,
		arrows: true,
		infinite: true,
		autoplay: true,
		pauseOnHover: false,
		useTransform: false,
		autoplaySpeed: 0,
		speed: 12500,
		cssEase: "linear",
		variableWidth: true,
	});

	// Collection-Slider (läuft als Band)
	jQuery(".slider--collection").slick({
		draggable: false,
		dots: false,
		arrows: false,
		infinite: true,
		autoplay: true,
		useTransform: false,
		autoplaySpeed: 0,
		speed: 12500,
		cssEase: "linear",
		variableWidth: true,
		slidesToScroll: 1,
	});

	// **Sofortiges Stoppen beim Hover**
	jQuery(".slider--collection").on("mouseenter", function () {
		console.log("Hover erkannt: Slider stoppt sofort");

		let $slickTrack = jQuery(this).find(".slick-track");

		// Speichert den aktuellen Transform-Wert
		let currentTransform = $slickTrack.css("transform");

		// Stoppt die Animation (keine neue Frames mehr)
		cancelAnimationFrame(animationFrame);

		// Pausiert den Slider sofort
		$slickTrack.css({
			transition: "none", // Entfernt weiche Animation
			transform: currentTransform, // Hält den aktuellen Stand
		});
	});

	// **Bewegung fortsetzen beim Verlassen**
	jQuery(".slider--collection").on("mouseleave", function () {
		console.log("Hover verlassen: Bewegung setzt sich fort");

		let $slickTrack = jQuery(this).find(".slick-track");

		// Setzt Animation fort
		$slickTrack.css({
			transition: "transform 12.5s linear", // Startet sanft neu
		});

		// Startet Slick erneut, falls nötig
		animationFrame = requestAnimationFrame(() => {
			jQuery(".slider--collection").slick("slickPlay");
		});
	});
};

export default Slick;
