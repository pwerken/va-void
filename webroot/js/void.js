"use strict";
!(function () {
	/* Navigation menu drop down */
	function f(e) {
		e.preventDefault();
		var a = document.querySelector(this.getAttribute("href")).classList;
		if (a.contains("popover-open")) {
			a.remove("popover-open")
		} else {
			o()
			a.add("popover-open")
		}
		e.stopImmediatePropagation();
	}

	var p = document.querySelectorAll("[data-popover]")
	for (var t = 0; t < p.length; t++) {
		p[t].addEventListener("click", f)
	}

	var n = document.querySelectorAll(".popover")
	function o(e) {
		for (var t = 0; t < n.length; t++)
			n[t].classList.remove("popover-open")
	}
	document.addEventListener("click", o)

	/* admin/printing */
	console.log('ping');
	function g(e) {
		var x = document.getElementById('checkboxcount');
		if (x) {
			var c = document.querySelectorAll('input[type=checkbox]:checked').length;
			x.textContent = c;
		}
	}

	var c = document.querySelectorAll('input[type=checkbox]');
	for (var t = 0; t < c.length; t++) {
		c[t].addEventListener("click", g);
	}
	g();
})();
