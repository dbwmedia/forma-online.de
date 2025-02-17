import "../../../node_modules/slick-carousel/slick/slick.min";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

const Slick = () => {
	jQuery(".slider--hero").slick({
		dots: false,
		loop: true,
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
};
export default Slick;

// const Slick = () => {
// 	jQuery(".slider--ec").slick({
// 		dots: false, // Keine Punkte, optional
// 		arrows: true, // Pfeile aktiviert
// 		infinite: true, // Endlos-Modus
// 		autoplay: true, // Automatische Bewegung
// 		autoplaySpeed: 0, // Sofortige, kontinuierliche Bewegung
// 		speed: 5000, // Geschwindigkeit der Animation
// 		cssEase: "linear", // Flüssige Bewegung
// 		slidesToShow: 1.5, // 1 Slide vollständig sichtbar, der zweite leicht angeschnitten
// 		slidesToScroll: 1, // Kontinuierliches Scrollen
// 		centerMode: true, // Mitte zentrieren
// 		variableWidth: false, // Einheitliche Bildgrößen beibehalten
// 		pauseOnHover: false, // Bewegung wird nicht durch Hover gestoppt
// 		accessibility: false, // Deaktiviert unnötige ARIA-Warnungen
// 	});
// };

// export default Slick;
