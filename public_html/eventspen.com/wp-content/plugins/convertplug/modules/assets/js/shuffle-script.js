!function(e, t) {
    var u, i = e.jQuery || e.Cowboy || (e.Cowboy = {});
    i.throttle = u = function(e, u, r, f) {
        function n() {
            function i() {
                a = +new Date(), r.apply(o, c);
            }
            function n() {
                s = t;
            }
            var o = this, l = +new Date() - a, c = arguments;
            f && !s && i(), s && clearTimeout(s), f === t && l > e ? i() : u !== !0 && (s = setTimeout(f ? n : i, f === t ? e - l : e));
        }
        var s, a = 0;
        return "boolean" != typeof u && (f = r, r = u, u = t), i.guid && (n.guid = r.guid = r.guid || i.guid++), 
        n;
    }, i.debounce = function(e, i, r) {
        return r === t ? u(e, i, !1) : u(e, r, i !== !1);
    };
}(this);

var shuffleme = function(e) {
    "use strict";
    var t = jQuery("#grid"), u = jQuery(".filter-options"), i = t.find(".shuffle_sizer"), r = jQuery("#cp_grid"), f = r.find(".shuffle_sizer"), n = function() {
        setTimeout(function() {
            a(), s();
        }, 100), t.shuffle({
            itemSelector: '[class*="col-"]',
            sizer: i
        }), r.shuffle({
            itemSelector: '[class*="cp-style-"]',
            sizer: f
        });
    }, s = function() {
        var e = u.children();
        e.on("click", function() {
            var e = jQuery(this), u = e.hasClass("active"), i = u ? "all" : e.data("group");
            u || jQuery(".filter-options .active").removeClass("active"), e.toggleClass("active"), 
            t.shuffle("shuffle", i), r.shuffle("shuffle", i);
        }), e = null;
    }, a = function() {
        var u = e.throttle(300, function() {
            t.shuffle("update"), r.shuffle("update");
        });
        t.find("img").each(function() {
            var e;
            this.complete && void 0 !== this.naturalWidth || (e = new Image(), jQuery(e).on("load", function() {
                jQuery(this).off("load"), u();
            }), e.src = this.src);
        }), r.find("img").each(function() {
            var e;
            this.complete && void 0 !== this.naturalWidth || (e = new Image(), jQuery(e).on("load", function() {
                jQuery(this).off("load"), u();
            }), e.src = this.src);
        }), jQuery(".js-shuffle-search").on("click", function() {
            var e = jQuery(this);
            setTimeout(function() {
                var u = e.val();
                "" == u && (r.shuffle("shuffle", "all"), t.shuffle("shuffle", "all"));
            }, 100);
        }), jQuery(".js-shuffle-search").on("keyup change", function() {
            var u = this.value.toLowerCase();
            t.shuffle("shuffle", function(t, i) {
                if ("all" !== i.group && -1 === e.inArray(i.group, t.data("groups"))) return !1;
                var r = e.trim(t.find(".cp-style-name").text()).toLowerCase();
                if ("-1" == r.indexOf(u)) var r = e.trim(t.data("tags")).toLowerCase();
                return -1 !== r.indexOf(u);
            }), r.shuffle("shuffle", function(t, i) {
                if ("all" !== i.group && -1 === e.inArray(i.group, t.data("groups"))) return !1;
                var r = e.trim(t.find(".cp-style-name").text()).toLowerCase();
                if ("-1" == r.indexOf(u)) var r = e.trim(t.data("tags")).toLowerCase();
                return -1 !== r.indexOf(u);
            });
        }), setTimeout(function() {
            u();
        }, 500);
    };
    return {
        init: n
    };
}(jQuery);

jQuery(document).ready(function() {
    shuffleme.init(), jQuery(".cp-themes").click(function() {
        var e = jQuery("#cp_grid");
        jQuery("#style-search").trigger("keyup change"), e.shuffle("shuffle", "all"), setTimeout(function() {
            e.shuffle("shuffle", "all");
        }, 100);        
        setTimeout(function() {
            jQuery("#style-search").focus();
        }, 300);
    }), jQuery(".js-shuffle-search").on("search", function() {
        jQuery(this).trigger("change");
    });
});