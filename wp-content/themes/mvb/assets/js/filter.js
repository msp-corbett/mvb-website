/* Bookshelf "filter by kind" buttons. The full grid is already
 * server-rendered with a data-kind attribute per card — this just toggles
 * [hidden] on the cards that don't match. Does nothing if the filter bar
 * isn't on the page (any page other than the Bookshelf). */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var filters = document.getElementById('filters');
		var shelf = document.getElementById('all-shelf');
		if (!filters || !shelf) return;

		function draw(kind) {
			[].forEach.call(shelf.querySelectorAll('.talker'), function (card) {
				card.hidden = kind !== 'Everything' && card.dataset.kind !== kind;
			});
			[].forEach.call(filters.querySelectorAll('button'), function (b) {
				b.setAttribute('aria-pressed', String(b.textContent === kind));
			});
		}

		filters.addEventListener('click', function (e) {
			if (e.target.tagName === 'BUTTON') draw(e.target.textContent);
		});
	});
})();
