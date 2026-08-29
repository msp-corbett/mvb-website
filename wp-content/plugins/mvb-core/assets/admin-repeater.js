/* Add/remove rows for the "books in this list" repeater on the List
 * edit screen. No build step, no dependencies — plain DOM. */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		var rows = document.getElementById('mvb-list-books-rows');
		var addBtn = document.getElementById('mvb-add-list-book');
		var template = document.getElementById('mvb-list-book-row-template');
		if (!rows || !addBtn || !template) return;

		// A monotonically increasing counter, never reused after a row is
		// removed, so two rows can never end up with the same field name
		// (which would silently drop one of them on save).
		function nextIndex() {
			var next = parseInt(rows.dataset.nextIndex || '0', 10);
			rows.dataset.nextIndex = String(next + 1);
			return next;
		}

		addBtn.addEventListener('click', function () {
			var html = template.innerHTML.replace(/__INDEX__/g, String(nextIndex()));
			var wrapper = document.createElement('tbody');
			wrapper.innerHTML = html;
			rows.appendChild(wrapper.firstElementChild);
		});

		rows.addEventListener('click', function (e) {
			var btn = e.target.closest('.mvb-remove-row');
			if (!btn) return;
			var row = btn.closest('.mvb-repeater-row');
			// Keep at least one row so the table never looks broken.
			if (rows.querySelectorAll('.mvb-repeater-row').length > 1) {
				row.remove();
			} else {
				row.querySelectorAll('input').forEach(function (input) {
					input.value = '';
				});
			}
		});
	});
})();
