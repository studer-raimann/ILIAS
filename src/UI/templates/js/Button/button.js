il = il || {};
il.UI = il.UI || {};
il.UI.button = il.UI.button || {};
(function($, il) {
	il.UI.button = (function($) {

		/* month button */
		var initMonth = function (id) {
			$("#" + id).find(".inline-picker").each(function(o) {
				$(this).datetimepicker({
					inline: true,
					sideBySide: true,
					viewMode: "months",
					format: "MM/YYYY",
					defaultDate: $(this).parent().data("defaultDate"),
					locale: $(this).parent().data("lang")
				}).on("dp.change", function (ev) {
					var i, d, months = [];
					var d = new Date(ev.date);
					var m = d.getMonth() + 1;
					m = ("00" + m).substring(m.toString().length);

					for (i = 1; i <= 12; i++) {
						months.push(il.Language.txt("month_" + (("00" + i).substring(i.toString().length)) + "_short"));
					}

					$("#" + id + " span.il-current-month").html(months[d.getMonth()] + " " + d.getFullYear());
					$("#" + id).trigger("il.ui.button.month.changed", [id, m + "-" + d.getFullYear()]);
				});
			});
		};

		/* toggle button */
		var handleToggleClick = function (event, id, on_url, off_url, signals) {
			var b = $("#" + id);
			var pressed = b.attr("aria-pressed");
			for (var i = 0; i < signals.length; i++) {
				var s = signals[i];
				if (s.event === "click" ||
					(pressed === "true" && s.event === "toggle_on") ||
					(pressed !== "true" && s.event === "toggle_off")
				) {
					$(b).trigger(s.signal_id, {
						'id' : s.signal_id,
						'event' : s.event,
						'triggerer' : b,
						'options' : s.options});
				}
			}

			if (pressed === "true" && on_url !== '') {
				window.location = on_url;
			}

			if (pressed !== "true" && off_url !== '') {
				window.location = off_url;
			}

			//console.log('handleToggelClick: ' + id);
			return false;
		};

		var activateLoadingAnimation = function(id){
			console.log('#'+id);
			var $button = $('#'+id);
			$button.addClass('il-btn-with-loading-animation');
			$button.addClass('disabled');
			return $button;
		};

		var deactivateLoadingAnimation = function(id){
			var $button = $('#'+id);
			$button.removeClass('il-btn-with-loading-animation');
			$button.removeClass('disabled');
			return $button;
		};

		return {
			initMonth: initMonth,
			handleToggleClick: handleToggleClick,
			activateLoadingAnimation: activateLoadingAnimation,
			deactivateLoadingAnimation: deactivateLoadingAnimation
		};
	})($);
})($, il);

// toggle init
document.addEventListener("DOMContentLoaded", function() {

	document.querySelectorAll(".il-toggle-button:not(.unavailable)").forEach(button => {
		const refreshLabels = (b, toggle = false) => {
			let on = b.classList.contains("on");
			if (toggle) {
				on = !on;
			}
			if (b.querySelectorAll(".il-toggle-label-off, .il-toggle-label-on").length > 0) {
				b.querySelectorAll(".il-toggle-label-off, .il-toggle-label-on").forEach(l => {
					l.style.display = "none";
				});
				if (on) {
					b.setAttribute('aria-pressed', true);
					b.classList.add("on");
					b.classList.remove("off");
					b.querySelector(".il-toggle-label-on").style.display = "";
				} else {
					b.setAttribute('aria-pressed', false);
					b.classList.add("off");
					b.classList.remove("on");
					b.querySelector(".il-toggle-label-off").style.display = "";
				}
			} else {
				if (on) {
					b.setAttribute('aria-pressed', true);
					b.classList.add("on");
					b.classList.remove("off");
				} else {
					b.setAttribute('aria-pressed', false);
					b.classList.add("off");
					b.classList.remove("on");
				}
			}
		}
		refreshLabels(button);

		button.addEventListener("click", e => {
			const b = e.currentTarget;
			refreshLabels(b, true);
		});
	});
});