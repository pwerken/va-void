"use strict";
!(function () {
	function r(e) {
		e.preventDefault();
		var c = document.querySelector(this.getAttribute("href")).classList;
		if (c.contains("popover-open")) {
			c.remove("popover-open")
		} else {
			o()
			c.add("popover-open")
		}
		e.stopImmediatePropagation();
	}
	var e = document.querySelectorAll("[data-popover]")
	for (var t = 0; t < e.length; t++)
		e[t].addEventListener("click", r)

	var n = document.querySelectorAll(".popover")
	function o(e) {
		for (var t = 0; t < n.length; t++)
			n[t].classList.remove("popover-open")
	}
	document.addEventListener("click", o)
})();
