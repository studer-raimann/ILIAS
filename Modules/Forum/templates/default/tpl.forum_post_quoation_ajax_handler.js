(function($, root) {
	function ilFrmQuoteAjaxHandler(t, ed) {
		$.ajax({
			"type": "GET",
			"url": "{IL_FRM_QUOTE_CALLBACK_SRC}"
		}).done((response) => {
			console.log(response);
			if (typeof response !== "undefined") {
				var uid = 'frm_quote_' + new Date().getTime();
				tinyMCE.execCommand("mceInsertContent", false, t._ilfrmquote2html(ed, response) + '<p id="' + uid + '">&nbsp;</p>');

				var rng = tinymce.DOM.createRng();
				var newNode = ed.dom.select('#' + uid)[0];
				rng.setStart(newNode, 0);
				rng.setEnd(newNode, 0);
				ed.selection.setRng(rng);
				ed.focus();
			}
		});

		return false;
	}

	root.ilFrmQuoteAjaxHandler = ilFrmQuoteAjaxHandler;
})(jQuery, window);