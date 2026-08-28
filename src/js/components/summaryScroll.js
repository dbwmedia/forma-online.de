const Component_SummaryScroll = () => {
	const summaryElement = document.querySelector(
		".dbw-custom-product-container .summary"
	);

	if (!summaryElement) return;

	let mouseOverSummary = false;

	summaryElement.addEventListener("mouseenter", () => {
		mouseOverSummary = true;
	});

	summaryElement.addEventListener("mouseleave", () => {
		mouseOverSummary = false;
	});

	document.addEventListener(
		"wheel",
		(e) => {
			if (!mouseOverSummary) return;

			e.preventDefault();
			e.stopPropagation();

			if (summaryElement.scrollHeight <= summaryElement.clientHeight) return;

			const maxScrollTop =
				summaryElement.scrollHeight - summaryElement.clientHeight;
			const scrollAmount = e.deltaY * 40;

			if (e.deltaY < 0 && summaryElement.scrollTop > 0) {
				summaryElement.scrollTop = Math.max(
					0,
					summaryElement.scrollTop + scrollAmount
				);
			} else if (e.deltaY > 0 && summaryElement.scrollTop < maxScrollTop) {
				summaryElement.scrollTop = Math.min(
					maxScrollTop,
					summaryElement.scrollTop + scrollAmount
				);
			}
		},
		{ passive: false }
	);
};

export default Component_SummaryScroll;
