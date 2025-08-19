const Component_SummaryScroll = () => {
	// Warten bis DOM geladen ist (IE9+ kompatibel)
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", initSummaryScroll);
	} else {
		initSummaryScroll();
	}

	function initSummaryScroll() {
		const summaryElement = document.querySelector(
			".dbw-custom-product-container .summary"
		);

		if (!summaryElement) {
			return; // Element nicht gefunden, Komponente beenden
		}

		// Flag um zu tracken ob die Maus über dem Summary-Element ist
		let mouseOverSummary = false;

		// Mouse enter Event - Maus betritt Summary-Bereich
		summaryElement.addEventListener("mouseenter", function () {
			mouseOverSummary = true;
		});

		// Mouse leave Event - Maus verlässt Summary-Bereich
		summaryElement.addEventListener("mouseleave", function () {
			mouseOverSummary = false;
		});

		// Cross-browser Wheel-Event Handler
		function handleWheel(e) {
			// Event-Objekt normalisieren (für ältere Browser)
			e = e || window.event;

			// Wenn Maus über Summary ist, IMMER das Event stoppen
			if (mouseOverSummary) {
				// Cross-browser preventDefault
				if (e.preventDefault) {
					e.preventDefault();
				} else {
					e.returnValue = false; // IE8 und älter
				}

				// Cross-browser stopPropagation
				if (e.stopPropagation) {
					e.stopPropagation();
				} else {
					e.cancelBubble = true; // IE8 und älter
				}

				// Nur scrollen wenn Summary tatsächlich scrollbar ist
				if (summaryElement.scrollHeight > summaryElement.clientHeight) {
					// Cross-browser deltaY Erkennung
					let deltaY = 0;
					if (e.deltaY !== undefined) {
						deltaY = e.deltaY; // Modern browsers
					} else if (e.wheelDelta !== undefined) {
						deltaY = -e.wheelDelta / 120; // IE, Chrome, Safari
					} else if (e.detail !== undefined) {
						deltaY = e.detail / 3; // Firefox (DOMMouseScroll)
					}

					// Aktuelle Scroll-Position prüfen
					const canScrollUp = summaryElement.scrollTop > 0;
					const maxScrollTop =
						summaryElement.scrollHeight - summaryElement.clientHeight;
					const canScrollDown = summaryElement.scrollTop < maxScrollTop;

					// Scroll-Geschwindigkeit anpassen (cross-browser)
					const scrollAmount = deltaY * 40; // 40px pro Wheel-Step

					// Scrolling nach oben (negatives deltaY)
					if (deltaY < 0 && canScrollUp) {
						summaryElement.scrollTop = Math.max(
							0,
							summaryElement.scrollTop + scrollAmount
						);
					}
					// Scrolling nach unten (positives deltaY)
					else if (deltaY > 0 && canScrollDown) {
						summaryElement.scrollTop = Math.min(
							maxScrollTop,
							summaryElement.scrollTop + scrollAmount
						);
					}
				}
			}
		}

		// Event Listener für verschiedene Browser hinzufügen
		if (summaryElement.addEventListener) {
			// Modern browsers (IE9+, Chrome, Firefox, Safari)
			document.addEventListener("wheel", handleWheel, false);
			// Firefox (ältere Versionen)
			document.addEventListener("DOMMouseScroll", handleWheel, false);
			// IE6-8 + Chrome, Safari (ältere Versionen)
			document.addEventListener("mousewheel", handleWheel, false);
		} else if (summaryElement.attachEvent) {
			// IE8 und älter
			document.attachEvent("onmousewheel", handleWheel);
		}

		// Touch-Events für mobile Geräte (mit Feature Detection)
		if ("ontouchstart" in window || navigator.maxTouchPoints > 0) {
			let touchStartY = 0;

			summaryElement.addEventListener("touchstart", function (e) {
				touchStartY = e.touches[0].clientY;
			});

			summaryElement.addEventListener("touchmove", function (e) {
				if (summaryElement.scrollHeight > summaryElement.clientHeight) {
					// Touch-Scrolling hier behalten, nicht weiterleiten
					if (e.stopPropagation) {
						e.stopPropagation();
					}
				}
			});
		}

		// Console-Log nur wenn console verfügbar (IE8 Sicherheit)
		if (window.console && window.console.log) {
			console.log("Summary Scroll Component initialized");
		}
	}
};

export default Component_SummaryScroll;
