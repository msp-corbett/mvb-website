/* "Show me three more" on the homepage's "From the shelf" band. The three
 * cards shown on page load are already server-rendered — this only wires
 * up the button, and does nothing if that button isn't on the page. */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var shelf = document.getElementById('home-shelf');
		var button = document.getElementById('reshuffle');
		if (!shelf || !button || typeof window.MVB_REST === 'undefined') return;

		function cardMarkup(pick) {
			var kindLabel = pick.kind || '';
			var coverStyle = pick.cover
				? ' style="background-image:url(' + cssUrl(pick.cover) + ')"'
				: '';
			var article = document.createElement('figure');
			article.className = 'talker';
			article.dataset.kind = kindLabel;

			article.innerHTML =
				'<svg class="pin" aria-hidden="true"><use href="#bird"/></svg>' +
				'<p class="kind"></p>' +
				'<div class="cover' + (pick.cover ? ' has-image' : '') + '"' + coverStyle + '>' +
				'<em></em><small></small></div>' +
				(pick.note ? '<blockquote class="note"></blockquote>' : '') +
				(pick.by ? '<figcaption class="sig"></figcaption>' : '') +
				(pick.buyUrl ? '<a class="buy">Buy this online →</a>' : '');

			// Text content is set via textContent (never innerHTML) for
			// every field that comes from the API response.
			article.querySelector('.kind').textContent = kindLabel;
			article.querySelector('.cover em').textContent = pick.title || '';
			article.querySelector('.cover small').textContent = pick.author || '';
			if (pick.note) article.querySelector('.note').textContent = pick.note;
			if (pick.by) article.querySelector('.sig').textContent = pick.by;
			if (pick.buyUrl) article.querySelector('.buy').setAttribute('href', pick.buyUrl);

			return article;
		}

		// CSS url(...) needs its own escaping, distinct from HTML escaping.
		function cssUrl(url) {
			return "'" + String(url).replace(/['\\]/g, '\\$&') + "'";
		}

		button.addEventListener('click', function () {
			button.disabled = true;
			var exclude = shelf.dataset.reshuffleExclude || '';
			var endpoint = shelf.dataset.reshuffleEndpoint || 'picks/random';
			var url = window.MVB_REST.root + endpoint + '?count=3&exclude=' + encodeURIComponent(exclude);

			fetch(url, { headers: { Accept: 'application/json' } })
				.then(function (response) {
					if (!response.ok) throw new Error('Request failed');
					return response.json();
				})
				.then(function (picks) {
					if (!Array.isArray(picks) || !picks.length) return;
					shelf.innerHTML = '';
					picks.forEach(function (pick) {
						shelf.appendChild(cardMarkup(pick));
					});
					shelf.dataset.reshuffleExclude = picks.map(function (p) { return p.id; }).join(',');
				})
				.catch(function () {
					// Leave the current cards in place — the button is
					// simply not disabled, so it can be tried again.
				})
				.finally(function () {
					button.disabled = false;
				});
		});
	});
})();
