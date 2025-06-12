// =======================
// Preloader
// =======================
jQuery(window).on("load", function () {
	$("#preloader").fadeOut(500)
	$("#main-wrapper").addClass("show")
});

// =======================
// jQuery DOM Operations
// =======================
(function ($) {
	"use strict"

	// Keep current page menu item active
	$(function () {
		const currentUrl = window.location.href
		let $activeLink = $(".settings-menu a, .menu a")
			.filter(function () {
				return this.href === currentUrl
			})
			.addClass("active")
			.parent()
			.addClass("active")

		while ($activeLink.is("li")) {
			$activeLink = $activeLink.parent().addClass("show").parent().addClass("active")
		}
	})

	// Set minimum height for content body
	$(".content-body").css("min-height", $(window).height() + 50 + "px")
})(jQuery)

// =======================
// Theme Handling
// =======================
window.addEventListener("load", () => {
	const savedTheme = localStorage.getItem("theme") || "light-theme"
	document.body.classList.add(savedTheme)

	const themeLabel = document.getElementById("theme")
	if (themeLabel) {
		themeLabel.textContent = savedTheme
	}
})

function themeToggle() {
	const body = document.body
	const isDark = body.classList.toggle("light-theme")

	localStorage.setItem("theme", isDark ? "light-theme" : "")

	const themeLabel = document.getElementById("theme")
	if (themeLabel) {
		themeLabel.textContent = isDark ? "light-theme" : "light"
	}
}
