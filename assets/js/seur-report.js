jQuery(function(a) {
	var b = {
		init: function() {
			a(document.body).on("click", "a.debug-report", this.generateReport)
		},
		generateReport: function() {
			var b = "";
			a(".seur-status-table thead, .seur-status-table tbody").each(function() {
				if (a(this).is("thead")) {
					var c = a(this).find("th:eq(0)").data("export-label") || a(this).text();
					b = b + "\n### " + a.trim(c) + " ###\n\n"
				} else a("tr", a(this)).each(function() {
					var c = a(this).find("td:eq(0)").data("export-label") || a(this).find("td:eq(0)").text(),
						d = a.trim(c).replace(/(<([^>]+)>)/gi, ""),
						e = a(this).find("td:eq(1)").clone();
					e.find(".private").remove(), e.find(".yes").replaceWith("&#10004;"), e.find(".no, .error").replaceWith("&#10060;");
					var f = a.trim(e.text()),
						g = f.split(", ");
					if (g.length > 1) {
						var h = "";
						a.each(g, function(a, b) {
							h = h + b + "\n"
						}), f = h
					}
					b = b + "" + d + ": " + f + "\n"
				})
			});
			try {
				return a("#seur-debug-report").slideDown(), a("#seur-debug-report").find("textarea").val("```" + b + "```").focus().select(), a(this).fadeOut(), !1
			} catch (a) {
				console.log(a)
			}
			return !1
		}
	};
	b.init()
});