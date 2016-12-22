function renderChart() {
    jQuery(".smile-absolute-loader").css("visibility", "visible");
    var e = "undefined" != typeof cpcpGetUrlVars().sd ? cpcpGetUrlVars().sd : "", t = "undefined" != typeof cpcpGetUrlVars().ed ? cpcpGetUrlVars().ed : "", a = "undefined" != typeof cpcpGetUrlVars().style ? decodeURIComponent(cpcpGetUrlVars().style) : "all", l = "undefined" != typeof cpcpGetUrlVars().cType ? cpcpGetUrlVars().cType : "line", s = "undefined" != typeof cpcpGetUrlVars().compFactor ? cpcpGetUrlVars().compFactor : "imp";
    a = a.split("||");
    var r = "get_style_analytics_data", o = {
        action: r,
        module: jQuery("#cp-module").val(),
        chartType: l,
        compFactor: s,
        styleid: a,
        startDate: e,
        endDate: t
    }, n = jQuery.ajax({
        url: ajaxurl,
        data: o,
        method: "POST",
        dataType: "JSON",
        async: !1
    }).responseText;
    setTimeout(function() {
        jQuery(".cp-analytics-filter-section").fadeIn(1e3);
        var e = jQuery(".analytics-form .col-sm-2").width();
        jQuery(".analytics-form .select2-search__field").attr("style", "width:" + e + "px !important; max-width:" + e + "px !important"), 
        jQuery(".cp-graph-area").removeClass("cp-hidden"), jQuery(".smile-absolute-loader").css("visibility", "hidden");
    }, 1100), setTimeout(function() {
        var e = jQuery.parseJSON(n);
        if (jQuery("#chart-legend").html(""), "undefined" != typeof e.labels && e.labels.length > 15) var t = !0; else var t = !1;
        if ("unavailable" != e) {
            var a = jQuery("#cp-chart-comp-type option:selected").text(), s = jQuery("#cp-chart-comp-type option:selected").val();
            Chart.defaults.global.pointHitDetectionRadius = 1, Chart.defaults.global.customTooltips = function(e) {
                var t = jQuery("#chartjs-tooltip");
                if (!e) return void t.css({
                    opacity: 0
                });
                t.removeClass("above below"), t.addClass(e.yAlign);
                var l = "";
                if ("undefined" != typeof e.labels) {
                    l += [ '<span class="chartjs-tooltip-title">' + a + " on " + e.title + "<br></span>" ].join("");
                    for (var r = e.labels.length - 1; r >= 0; r--) {
                        value = e.labels[r].split(":");
                        var o = value[0], n = "";
                        "convRate" == s && (n = "%"), l += [ '<div class="chartjs-tooltip-section">', '<span class="chartjs-tooltip-color" style="background-color:' + e.legendColors[r].fill + '"></span>' + o + ': <span class="chartjs-tooltip-value">' + value[1] + " " + n + "</span>", "</div>" ].join("");
                    }
                }
                if ("undefined" != typeof e.text) {
                    var i = (e.chart.ctx, e.chart.ctx.strokeStyle);
                    value = e.text.split(":"), o = value[1].length > 15 ? value[1].substring(0, 15) + ".." : value[1];
                    var c = value[0];
                    l += [ '<span class="chartjs-tooltip-title">' + a + " On " + c + "<br></span>" ].join(""), 
                    l += [ '<div class="chartjs-tooltip-section">', '<span class="chartjs-tooltip-color" style="background-color:' + i + '"></span>' + o + ': <span class="chartjs-tooltip-value">' + value[2] + "</span>", "</div>" ].join("");
                }
                t.html(l), t.css({
                    opacity: 1,
                    left: e.chart.canvas.offsetLeft + e.x + "px",
                    top: e.chart.canvas.offsetTop + e.y + "px"
                });
            }, jQuery(".chart-holder").html('<canvas id="line-chart"  />'), "" != myChart && myChart.destroy();
            var r = document.getElementById("line-chart").getContext("2d"), o = {
                responsive: !0,
                skipXLabels: t,
                maintainAspectRatio: !1,
                scaleShowHorizontalLines: !0,
                scaleShowVerticalLines: !1,
                datasetFill: !0,
                bezierCurveTension: .3,
                scaleFontFamily: "'Open Sans',sans-serif",
                scaleFontColor: "#444",
                tooltipTitleFontFamily: "'Open Sans',sans-serif",
                tooltipTitleFontSize: 12
            };
            ("bar" == l || "line" == l) && (o.tooltipTemplate = "<%= label %>: <%= datasetLabel %>: <%= value %>", 
            o.multiTooltipTemplate = "<%= datasetLabel %>: <%= value %>"), "bar" == l ? (o.legendTemplate = '<ul class="legend-list"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].strokeColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%>: <%=datasets[i].bars[0].tpl_var_count%><%}%></li><%}%></ul>', 
            myChart = new Chart(r).Bar(e, o)) : "line" == l ? (o.legendTemplate = '<ul class="legend-list"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].pointColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%>: <%=datasets[i].points[0].tpl_var_count%><%}%></li><%}%></ul>', 
            myChart = new Chart(r).Line(e, o)) : "donut" == l ? (o.customTooltips = !1, o.legendTemplate = '<ul class="legend-list"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>', 
            myChart = new Chart(r).Doughnut(e, o)) : "polararea" == l && (o.customTooltips = !1, 
            o.legendTemplate = '<ul class="legend-list"><% for (var i=0; i<segments.length; i++){%><li><span style="background-color:<%=segments[i].fillColor%>"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>', 
            myChart = new Chart(r).PolarArea(e, o));
            var i = myChart.generateLegend();
            jQuery("#chart-legend").html(i);
        } else jQuery(".chart-holder").html("<p class='cp-empty-graph'>No data available for selected styles. </p>");
        var c = jQuery(".cp-graph-width").width() + "px";
        jQuery("#line-chart").css("width", c, "important");
    }, 1800);
}

function cpcpGetUrlVars() {
    var e = {};
    return window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(t, a, l) {
        e[a] = l;
    }), e;
}

jQuery(document).ready(function() {

    function e() {
        var e = jQuery("#style-dropdown").val();
        null !== e && (jQuery.inArray("all", e) > -1 || "all" == e || e.length > 1 ? (jQuery("#cp-chart-comp-type").find("option[value='impVsconv']").remove(), 
        jQuery("#cp-chart-comp-type").prop("disabled", !1)) : (void 0 === jQuery("#cp-chart-comp-type option[value='impVsconv']").val() && jQuery("#cp-chart-comp-type").prepend("<option value='impVsconv'>Impression Vs Conversion</option>").val("impVsconv"), 
        jQuery("#cp-chart-comp-type").val("impVsconv"), jQuery("#cp-chart-comp-type").prop("disabled", !1)));
    }
    e(), jQuery("#style-dropdown").select2();
    var t = jQuery("#style-dropdown");
    jQuery(document).on("click", ".select2-selection__choice__remove", function() {
        t.select2("close");
    }), t.on("select2:select", function() {
        e();
    }), t.on("select2:unselect", function() {
        e();
    });
    var a = {
        time: "connects-icon-clock",
        date: "dashicons dashicons-calendar-alt",
        up: "dashicons dashicons-arrow-up-alt2",
        down: "dashicons dashicons-arrow-down-alt2",
        previous: "dashicons dashicons-arrow-left-alt2",
        next: "dashicons dashicons-arrow-right-alt2",
        today: "dashicons dashicons-screenoptions",
        clear: "dashicons dashicons-trash"
    };
    jQuery("#cp-startDate").datetimepicker({
        format: "DD-MM-YYYY",
        maxDate: new Date(),
        icons: a
    }).on("dp.change", function(e) {
        jQuery("#cp-endDate").data("DateTimePicker").minDate(e.date);
    }), jQuery("#cp-endDate").datetimepicker({
        format: "DD-MM-YYYY",
        maxDate: new Date(),
        icons: a
    }).on("dp.change", function(e) {
        jQuery("#cp-startDate").data("DateTimePicker").maxDate(e.date);
    });
    jQuery('#cp-startDate').click(function(event){      
       jQuery('#cp-startDate').data("DateTimePicker").show();
    });
    jQuery('#cp-endDate').click(function(event){      
       jQuery('#cp-endDate').data("DateTimePicker").show();
    });
    var l = cpcpGetUrlVars().style;
    "undefined" == typeof campaign && (l = Array("all")), renderChart();
});

var myLineChart = "";

jQuery(document).on("click", "#submit-query", function() {
    var e = jQuery("#style-dropdown").val(), t = jQuery("#cp-startDate").val(), a = jQuery("#cp-endDate").val(), l = jQuery("#cp-chart-type").val(), s = jQuery("#cp-chart-comp-type").val();
    if (null == e) return alert("Please select style"), !1;
    if ("" == t && "" != a || "" != t && "" == a) return alert("Please select both start and end date"), 
    !1;
    
    var module = jQuery("#cp-module").val(); 
    var r = "?page=smile-"+module+"-designer&style-view=analytics";
    "" !== t && "" !== a && (r += "&sd=" + t + "&ed=" + a);
    var o = e.join("||");
    r += "&style=" + encodeURIComponent(o) + "&cType=" + l + "&compFactor=" + s, window.history.pushState("/admin.php?page=smile-"+module+"-designer&style-view=analytics", "Connects", r), 
    renderChart();
});

var myChart = "";