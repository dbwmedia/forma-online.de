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
		pauseOnHover: false, // Wir deaktivieren die eingebaute pauseOnHover-Funktion
		pauseOnFocus: false, // Wir deaktivieren auch pauseOnFocus
		useTransform: true,
		autoplaySpeed: 1, // Minimaler Wert für kontinuierliche Bewegung
		speed: 8000, // Sehr langsame Bewegung über 8 Sekunden
		cssEase: "linear", // Linear für gleichmäßige Bewegung
		variableWidth: false,
		slidesToShow: 1,
		slidesToScroll: 1,
		adaptiveHeight: false,
		centerMode: false,
		rtl: false,
		swipeToSlide: true,
		touchThreshold: 8,
		responsive: [
			{
				breakpoint: 992,
				settings: {
					arrows: true,
					autoplaySpeed: 1,
					speed: 8000,
				},
			},
			{
				breakpoint: 768,
				settings: {
					arrows: true,
					autoplaySpeed: 1,
					speed: 8000,
				},
			},
			{
				breakpoint: 480,
				settings: {
					arrows: true,
					autoplaySpeed: 1,
					speed: 8000,
				},
			},
		],
	});

	// Benutzerdefinierte Hover-Funktionalität hinzufügen
	jQuery(document).ready(function () {
		var $slider = jQuery(".slider--hero");

		// Wenn die Maus über dem Slider ist
		$slider.on("mouseenter", function () {
			$slider.slick("slickPause");
		});

		// Wenn die Maus den Slider verlässt
		$slider.on("mouseleave", function () {
			$slider.slick("slickPlay");
		});
	});
	// // Collection-Slider (läuft als Band)
	// jQuery(".slider--collection").slick({
	// 	draggable: false,
	// 	dots: false,
	// 	arrows: false,
	// 	infinite: true,
	// 	autoplay: true,
	// 	useTransform: false,
	// 	autoplaySpeed: 0,
	// 	speed: 12500,
	// 	cssEase: "linear",
	// 	variableWidth: true,
	// 	slidesToScroll: 1,
	// });

	// // **Sofortiges Stoppen beim Hover**
	// jQuery(".slider--collection").on("mouseenter", function () {
	// 	console.log("Hover erkannt: Slider stoppt sofort");

	// 	let $slickTrack = jQuery(this).find(".slick-track");

	// 	// Speichert den aktuellen Transform-Wert
	// 	let currentTransform = $slickTrack.css("transform");

	// 	// Stoppt die Animation (keine neue Frames mehr)
	// 	cancelAnimationFrame(animationFrame);

	// 	// Pausiert den Slider sofort
	// 	$slickTrack.css({
	// 		transition: "none", // Entfernt weiche Animation
	// 		transform: currentTransform, // Hält den aktuellen Stand
	// 	});
	// });

	// // **Bewegung fortsetzen beim Verlassen**
	// jQuery(".slider--collection").on("mouseleave", function () {
	// 	console.log("Hover verlassen: Bewegung setzt sich fort");

	// 	let $slickTrack = jQuery(this).find(".slick-track");

	// 	// Setzt Animation fort
	// 	$slickTrack.css({
	// 		transition: "transform 12.5s linear", // Startet sanft neu
	// 	});

	// 	// Startet Slick erneut, falls nötig
	// 	animationFrame = requestAnimationFrame(() => {
	// 		jQuery(".slider--collection").slick("slickPlay");
	// 	});
	// });
};

export default Slick;
