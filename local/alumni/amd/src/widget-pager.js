(function (factory) {
    if (typeof define === "function" && define.amd) {
        define(["jquery"], factory);
    } else if (typeof module === "object" && typeof module.exports === "object") {
        module.exports = factory(require("jquery"));
    } else {
        factory(jQuery);
    }
})(function (jQuery) {
    /*! Widget: Pager - updated 2020-03-03 (v2.31.3) */
    !(function (S) {
        "use strict";
        var C,
            $ = S.tablesorter;
        $.addWidget({
            id: "pager",
            priority: 55,
            options: {
                pager_output: "{startRow} to {endRow} of {totalRows} rows",
                pager_updateArrows: !0,
                pager_startPage: 0,
                pager_pageReset: 0,
                pager_size: 10,
                pager_maxOptionSize: 20,
                pager_savePages: !0,
                pager_storageKey: "tablesorter-pager",
                pager_fixedHeight: !1,
                pager_countChildRows: !1,
                pager_removeRows: !1,
                pager_ajaxUrl: null,
                pager_customAjaxUrl: function (e, a) {
                    return a;
                },
                pager_ajaxError: null,
                pager_ajaxObject: { dataType: "json" },
                pager_processAjaxOnInit: !0,
                pager_ajaxProcessing: function (e) {
                    return e;
                },
                pager_css: { container: "tablesorter-pager", errorRow: "tablesorter-errorRow", disabled: "disabled" },
                pager_selectors: { container: ".pager", first: ".first", prev: ".prev", next: ".next", last: ".last", gotoPage: ".gotoPage", pageDisplay: ".pagedisplay", pageSize: ".pagesize" },
            },
            init: function (e) {
                C.init(e);
            },
            format: function (e, a) {
                if (!a.pager || !a.pager.initialized) return C.initComplete(a);
                C.moveToPage(a, a.pager, !1);
            },
            remove: function (e, a, t, r) {
                C.destroyPager(a, r);
            },
        }),
            (C = $.pager = {
                init: function (e) {
                    if (!(e.hasInitialized && e.config.pager && e.config.pager.initialized)) {
                        var a,
                            t = e.config,
                            r = t.widgetOptions,
                            i = r.pager_selectors,
                            s = (t.pager = S.extend(
                                {
                                    totalPages: 0,
                                    filteredRows: 0,
                                    filteredPages: 0,
                                    currentFilters: [],
                                    page: r.pager_startPage,
                                    startRow: 0,
                                    endRow: 0,
                                    ajaxCounter: 0,
                                    $size: null,
                                    last: {},
                                    setSize: r.pager_size,
                                    setPage: r.pager_startPage,
                                },
                                t.pager
                            ));
                        (s.removeRows = r.pager_removeRows),
                        s.isInitializing ||
                        ((s.isInitializing = !0),
                        $.debug(t, "pager") && console.log("Pager >> Initializing"),
                            (s.size = S.data(e, "pagerLastSize") || r.pager_size),
                            (s.$container = S(i.container).addClass(r.pager_css.container).show()),
                            (s.totalRows = t.$tbodies
                                .eq(0)
                                .children("tr")
                                .not(r.pager_countChildRows ? "" : "." + t.cssChildRow).length),
                            (s.oldAjaxSuccess = s.oldAjaxSuccess || r.pager_ajaxObject.success),
                            (t.appender = C.appender),
                            (s.initializing = !0),
                        r.pager_savePages &&
                        $.storage &&
                        ((a = $.storage(e, r.pager_storageKey) || {}),
                            (s.page = (isNaN(a.page) ? s.page : a.page) || s.setPage || 0),
                            (s.size = "all" === a.size ? a.size : (isNaN(a.size) ? s.size : a.size) || s.setSize || 10),
                            C.setPageSize(t, s.size)),
                            (s.regexRows = new RegExp("(" + (r.filter_filteredRow || "filtered") + "|" + t.selectorRemove.slice(1) + "|" + t.cssChildRow + ")")),
                            (s.regexFiltered = new RegExp(r.filter_filteredRow || "filtered")),
                            (s.initialized = !1),
                            t.$table.triggerHandler("pagerBeforeInitialized", t),
                            C.enablePager(t, !1),
                            (s.ajaxObject = r.pager_ajaxObject),
                            (s.ajaxObject.url = r.pager_ajaxUrl),
                            "string" == typeof r.pager_ajaxUrl ? ((s.ajax = !0), (r.filter_serversideFiltering = !0), (t.serverSideSorting = !0), C.moveToPage(t, s)) : ((s.ajax = !1), $.appendCache(t, !0)));
                    }
                },
                initComplete: function (e) {
                    var a = e.pager;
                    C.bindEvents(e),
                    a.ajax || C.hideRowsSetup(e),
                        (a.initialized = !0),
                        (a.initializing = !1),
                        (a.isInitializing = !1),
                        C.setPageSize(e, a.size),
                    $.debug(e, "pager") && console.log("Pager >> Triggering pagerInitialized"),
                        e.$table.triggerHandler("pagerInitialized", e),
                    (e.widgetOptions.filter_initialized && $.hasWidget(e.table, "filter")) || C.updatePageDisplay(e, !a.ajax);
                },
                bindEvents: function (i) {
                    var s,
                        o,
                        e,
                        g = i.pager,
                        n = i.widgetOptions,
                        a = i.namespace + "pager",
                        t = n.pager_selectors,
                        r = $.debug(i, "pager");
                    i.$table
                        .off(a)
                        .on("filterInit filterStart ".split(" ").join(a + " "), function (e, a) {
                            var t;
                            if (((g.currentFilters = S.isArray(a) ? a : i.$table.data("lastSearch")), g.ajax && "filterInit" === e.type)) return C.moveToPage(i, g, !1);
                            (t = $.filter.equalFilters ? $.filter.equalFilters(i, i.lastSearch, g.currentFilters) : (i.lastSearch || []).join("") !== (g.currentFilters || []).join("")),
                            "filterStart" !== e.type || !1 === n.pager_pageReset || t || (g.page = n.pager_pageReset);
                        })
                        .on("filterEnd sortEnd ".split(" ").join(a + " "), function () {
                            (g.currentFilters = i.$table.data("lastSearch")),
                            (g.initialized || g.initializing) && (i.delayInit && i.rowsCopy && 0 === i.rowsCopy.length && C.updateCache(i), C.updatePageDisplay(i, !1), $.applyWidget(i.table));
                        })
                        .on("disablePager" + a, function (e) {
                            e.stopPropagation(), C.showAllRows(i);
                        })
                        .on("enablePager" + a, function (e) {
                            e.stopPropagation(), C.enablePager(i, !0);
                        })
                        .on("destroyPager" + a, function (e) {
                            e.stopPropagation(), $.removeWidget(i.table, "pager", !1);
                        })
                        .on("updateComplete" + a, function (e, a, t) {
                            if ((e.stopPropagation(), a && !t && !g.ajax)) {
                                var r = i.$tbodies.eq(0).children("tr").not(i.selectorRemove);
                                (g.totalRows = r.length - (n.pager_countChildRows ? 0 : r.filter("." + i.cssChildRow).length)),
                                    (g.totalPages = "all" === g.size ? 1 : Math.ceil(g.totalRows / g.size)),
                                r.length && i.rowsCopy && 0 === i.rowsCopy.length && C.updateCache(i),
                                g.page >= g.totalPages && C.moveToLastPage(i, g),
                                    C.hideRows(i),
                                    C.changeHeight(i),
                                    C.updatePageDisplay(i, !1),
                                    $.applyWidget(a),
                                    C.updatePageDisplay(i);
                            }
                        })
                        .on("pageSize refreshComplete ".split(" ").join(a + " "), function (e, a) {
                            e.stopPropagation(), C.setPageSize(i, C.parsePageSize(i, a, "get")), C.moveToPage(i, g, !0), C.hideRows(i), C.updatePageDisplay(i, !1);
                        })
                        .on("pageSet pagerUpdate ".split(" ").join(a + " "), function (e, a) {
                            e.stopPropagation(), "pagerUpdate" === e.type && ((a = void 0 === a ? g.page + 1 : a), (g.last.page = !0)), (g.page = (parseInt(a, 10) || 1) - 1), C.moveToPage(i, g, !0), C.updatePageDisplay(i, !1);
                        })
                        .on("pageAndSize" + a, function (e, a, t) {
                            e.stopPropagation(), (g.page = (parseInt(a, 10) || 1) - 1), C.setPageSize(i, C.parsePageSize(i, t, "get")), C.moveToPage(i, g, !0), C.hideRows(i), C.updatePageDisplay(i, !1);
                        }),
                        (s = [t.first, t.prev, t.next, t.last]),
                        (o = ["moveToFirstPage", "moveToPrevPage", "moveToNextPage", "moveToLastPage"]),
                    r && !g.$container.length && console.warn('Pager >> "container" not found'),
                        g.$container
                            .find(s.join(","))
                            .attr("tabindex", 0)
                            .off("click" + a)
                            .on("click" + a, function (e) {
                                e.stopPropagation();
                                var a,
                                    t = S(this),
                                    r = s.length;
                                if (!t.hasClass(n.pager_css.disabled))
                                    for (a = 0; a < r; a++)
                                        if (t.is(s[a])) {
                                            C[o[a]](i, g);
                                            break;
                                        }
                            }),
                        (e = g.$container.find(n.pager_selectors.gotoPage)).length
                            ? e.off("change" + a).on("change" + a, function () {
                                (g.page = S(this).val() - 1), C.moveToPage(i, g, !0), C.updatePageDisplay(i, !1);
                            })
                            : r && console.warn('Pager >> "goto" selector not found'),
                        (e = g.$container.find(n.pager_selectors.pageSize)).length
                            ? (e.find("option").removeAttr("selected"),
                                e.off("change" + a).on("change" + a, function () {
                                    if (!S(this).hasClass(n.pager_css.disabled)) {
                                        var e = S(this).val();
                                        g.$container.find(n.pager_selectors.pageSize).val(e), C.setPageSize(i, e), C.moveToPage(i, g, !0), C.changeHeight(i);
                                    }
                                    return !1;
                                }))
                            : r && console.warn('Pager >> "size" selector not found');
                },
                pagerArrows: function (e, a) {
                    var t = e.pager,
                        r = !!a,
                        i = r || 0 === t.page,
                        s = C.getTotalPages(e, t),
                        o = r || t.page === s - 1 || 0 === s,
                        g = e.widgetOptions,
                        n = g.pager_selectors;
                    g.pager_updateArrows &&
                    (t.$container
                        .find(n.first + "," + n.prev)
                        .toggleClass(g.pager_css.disabled, i)
                        .prop("aria-disabled", i),
                        t.$container
                            .find(n.next + "," + n.last)
                            .toggleClass(g.pager_css.disabled, o)
                            .prop("aria-disabled", o));
                },
                calcFilters: function (e) {
                    var a,
                        t,
                        r,
                        i = e.widgetOptions,
                        s = e.pager,
                        o = e.$table.hasClass("hasFilters");
                    if (o && !s.ajax)
                        if (S.isEmptyObject(e.cache))
                            s.filteredRows = s.totalRows = e.$tbodies
                                .eq(0)
                                .children("tr")
                                .not(i.pager_countChildRows ? "" : "." + e.cssChildRow).length;
                        else for (s.filteredRows = 0, r = (a = e.cache[0].normalized).length, t = 0; t < r; t++) s.filteredRows += s.regexRows.test(a[t][e.columns].$row[0].className) ? 0 : 1;
                    else o || (s.filteredRows = s.totalRows);
                },
                updatePageDisplay: function (t, e) {
                    if (!t.pager || !t.pager.initializing) {
                        var a,
                            r,
                            i,
                            s,
                            o,
                            g,
                            n,
                            l = t.table,
                            p = t.widgetOptions,
                            d = t.pager,
                            c = t.namespace + "pager",
                            f = C.parsePageSize(t, d.size, "get");
                        if (
                            ("all" === f && (f = d.totalRows),
                            p.pager_countChildRows && (r[r.length] = t.cssChildRow),
                                d.$container
                                    .find(p.pager_selectors.pageSize + "," + p.pager_selectors.gotoPage)
                                    .removeClass(p.pager_css.disabled)
                                    .removeAttr("disabled")
                                    .prop("aria-disabled", "false"),
                                (d.totalPages = Math.ceil(d.totalRows / f)),
                                (t.totalRows = d.totalRows),
                                C.parsePageNumber(t, d),
                                C.calcFilters(t),
                                (t.filteredRows = d.filteredRows),
                                (d.filteredPages = Math.ceil(d.filteredRows / f) || 0),
                            0 <= C.getTotalPages(t, d))
                        ) {
                            if (
                                ((r = f * d.page > d.filteredRows && e),
                                    (d.page = r ? p.pager_pageReset || 0 : d.page),
                                    (d.startRow = r ? f * d.page + 1 : 0 === d.filteredRows ? 0 : f * d.page + 1),
                                    (d.endRow = Math.min(d.filteredRows, d.totalRows, f * (d.page + 1))),
                                    (i = d.$container.find(p.pager_selectors.pageDisplay)),
                                    (a =
                                        "function" == typeof p.pager_output
                                            ? p.pager_output(l, d)
                                            : ((n = i.attr("data-pager-output" + (d.filteredRows < d.totalRows ? "-filtered" : "")) || p.pager_output),
                                                ((d.ajaxData && d.ajaxData.output && d.ajaxData.output) || n)
                                                    .replace(/\{page([\-+]\d+)?\}/gi, function (e, a) {
                                                        return d.totalPages ? d.page + (a ? parseInt(a, 10) : 1) : 0;
                                                    })
                                                    .replace(/\{\w+(\s*:\s*\w+)?\}/gi, function (e) {
                                                        var a,
                                                            t,
                                                            r = e.replace(/[{}\s]/g, ""),
                                                            i = r.split(":"),
                                                            s = d.ajaxData,
                                                            o = /(rows?|pages?)$/i.test(r) ? 0 : "";
                                                        return /(startRow|page)/.test(i[0]) && "input" === i[1]
                                                            ? ((a = ("" + ("page" === i[0] ? d.totalPages : d.totalRows)).length),
                                                                (t = "page" === i[0] ? d.page + 1 : d.startRow),
                                                            '<input type="text" class="ts-' + i[0] + '" style="max-width:' + a + 'em" value="' + t + '"/>')
                                                            : 1 < i.length && s && s[i[0]]
                                                                ? s[i[0]][i[1]]
                                                                : d[r] || (s ? s[r] : o) || o;
                                                    }))),
                                    d.$container.find(p.pager_selectors.gotoPage).length)
                            ) {
                                for (r = "", g = (s = C.buildPageSelect(t, d)).length, o = 0; o < g; o++) r += '<option value="' + s[o] + '">' + s[o] + "</option>";
                                d.$container
                                    .find(p.pager_selectors.gotoPage)
                                    .html(r)
                                    .val(d.page + 1);
                            }
                            i.length &&
                            (i["INPUT" === i[0].nodeName ? "val" : "html"](a),
                                i
                                    .find(".ts-startRow, .ts-page")
                                    .off("change" + c)
                                    .on("change" + c, function () {
                                        var e = S(this).val(),
                                            a = S(this).hasClass("ts-startRow") ? Math.floor(e / f) + 1 : e;
                                        t.$table.triggerHandler("pageSet" + c, [a]);
                                    }));
                        }
                        C.pagerArrows(t),
                            C.fixHeight(t),
                        d.initialized &&
                        !1 !== e &&
                        ($.debug(t, "pager") && console.log("Pager >> Triggering pagerComplete"),
                            t.$table.triggerHandler("pagerComplete", t),
                        p.pager_savePages && $.storage && $.storage(l, p.pager_storageKey, { page: d.page, size: f === d.totalRows ? "all" : f }));
                    }
                },
                buildPageSelect: function (e, a) {
                    var t,
                        r,
                        i,
                        s,
                        o,
                        g,
                        n = e.widgetOptions,
                        l = C.getTotalPages(e, a) || 1,
                        p = 5 * Math.ceil(l / n.pager_maxOptionSize / 5),
                        d = l > n.pager_maxOptionSize,
                        c = a.page + 1,
                        f = p,
                        u = l - p,
                        h = [1];
                    for (t = d ? p : 1; t <= l; ) (h[h.length] = t), (t += d ? p : 1);
                    if (((h[h.length] = l), d)) {
                        for (i = [], (f = c - (r = Math.max(Math.floor(n.pager_maxOptionSize / p) - 1, 5))) < 1 && (f = 1), l < (u = c + r) && (u = l), t = f; t <= u; t++) i[i.length] = t;
                        p / 2 <
                        (o = (h = S.grep(h, function (e, a) {
                            return S.inArray(e, h) === a;
                        })).length) -
                        (g = i.length) &&
                        o + g > n.pager_maxOptionSize &&
                        ((s = Math.floor(o / 2) - Math.floor(g / 2)), Array.prototype.splice.apply(h, [s, g])),
                            (h = h.concat(i));
                    }
                    return (h = S.grep(h, function (e, a) {
                        return S.inArray(e, h) === a;
                    }).sort(function (e, a) {
                        return e - a;
                    }));
                },
                fixHeight: function (e) {
                    var a,
                        t,
                        r,
                        i = e.table,
                        s = e.pager,
                        o = e.widgetOptions,
                        g = e.$tbodies.eq(0);
                    g.find("tr.pagerSavedHeightSpacer").remove(),
                    o.pager_fixedHeight &&
                    !s.isDisabled &&
                    (t = S.data(i, "pagerSavedHeight")) &&
                    ((r = 0),
                    1 < S(i).css("border-spacing").split(" ").length &&
                    (r = S(i)
                        .css("border-spacing")
                        .split(" ")[1]
                        .replace(/[^-\d\.]/g, "")),
                    5 < (a = t - g.height() + r * s.size - r) &&
                    S.data(i, "pagerLastSize") === s.size &&
                    g.children("tr:visible").length < ("all" === s.size ? s.totalRows : s.size) &&
                    g.append('<tr class="pagerSavedHeightSpacer ' + e.selectorRemove.slice(1) + '" style="height:' + a + 'px;"></tr>'));
                },
                changeHeight: function (e) {
                    var a,
                        t = e.table,
                        r = e.pager,
                        i = "all" === r.size ? r.totalRows : r.size,
                        s = e.$tbodies.eq(0);
                    s.find("tr.pagerSavedHeightSpacer").remove(),
                    s.children("tr:visible").length || s.append('<tr class="pagerSavedHeightSpacer ' + e.selectorRemove.slice(1) + '"><td>&nbsp</td></tr>'),
                        (a = s.children("tr").eq(0).height() * i),
                        S.data(t, "pagerSavedHeight", a),
                        C.fixHeight(e),
                        S.data(t, "pagerLastSize", r.size);
                },
                hideRows: function (e) {
                    if (!e.widgetOptions.pager_ajaxUrl) {
                        var a,
                            t,
                            r,
                            i,
                            s,
                            o = e.pager,
                            g = e.widgetOptions,
                            n = e.$tbodies.length,
                            l = "all" === o.size ? o.totalRows : o.size,
                            p = o.page * l,
                            d = p + l,
                            c = -1,
                            f = 0;
                        for (o.cacheIndex = [], a = 0; a < n; a++) {
                            for (i = (r = e.$tbodies.eq(a).children("tr")).length, c = -1, t = f = s = 0; t < i; t++)
                                o.regexFiltered.test(r[t].className) ||
                                (f === p && r[t].className.match(e.cssChildRow)
                                    ? (r[t].style.display = "none")
                                    : ((r[t].style.display = p <= f && f < d ? "" : "none"),
                                    c !== f && p <= f && f < d && ((o.cacheIndex[o.cacheIndex.length] = t), (c = f)),
                                    (f += r[t].className.match(e.cssChildRow + "|" + e.selectorRemove.slice(1)) && !g.pager_countChildRows ? 0 : 1) === d &&
                                    "none" !== r[t].style.display &&
                                    r[t].className.match($.css.cssHasChild) &&
                                    (s = t)));
                            if (0 < s && r[s].className.match($.css.cssHasChild)) for (; ++s < i && r[s].className.match(e.cssChildRow); ) r[s].style.display = "";
                        }
                    }
                },
                hideRowsSetup: function (e) {
                    var a = e.pager,
                        t = e.namespace + "pager",
                        r = a.$container.find(e.widgetOptions.pager_selectors.pageSize).val();
                    (a.size = C.parsePageSize(e, r, "get")),
                        C.setPageSize(e, a.size),
                        C.pagerArrows(e),
                    e.widgetOptions.pager_removeRows ||
                    (C.hideRows(e),
                        e.$table.on("sortEnd filterEnd ".split(" ").join(t + " "), function () {
                            C.hideRows(e);
                        }));
                },
                renderAjax: function (e, a, t, r, i) {
                    var s = a.table,
                        o = a.pager,
                        g = a.widgetOptions,
                        n = $.debug(a, "pager");
                    if (S.isFunction(g.pager_ajaxProcessing)) {
                        a.$tbodies.eq(0).empty();
                        var l,
                            p,
                            d,
                            c,
                            f,
                            u,
                            h,
                            w,
                            P,
                            b,
                            z,
                            R,
                            v,
                            m,
                            x,
                            j = a.$table,
                            _ = "",
                            y = g.pager_ajaxProcessing(e, s, t) || [0, []];
                        if (($.showError(s), i)) n && console.error("Pager >> Ajax Error", t, r, i), $.showError(s, t, r, i), a.$tbodies.eq(0).children("tr").detach(), (o.totalRows = 0);
                        else {
                            if (
                                (S.isArray(y)
                                    ? ((v = y[(d = isNaN(y[0]) && !isNaN(y[1])) ? 1 : 0]),
                                        (o.totalRows = isNaN(v) ? o.totalRows || 0 : v),
                                        (a.totalRows = a.filteredRows = o.filteredRows = o.totalRows),
                                        (z = 0 === o.totalRows ? [] : y[d ? 0 : 1] || []),
                                        (b = y[2]))
                                    : ((o.ajaxData = y), (a.totalRows = o.totalRows = y.total), (a.filteredRows = o.filteredRows = void 0 !== y.filteredRows ? y.filteredRows : y.total), (b = y.headers), (z = y.rows || [])),
                                    (R = z && z.length),
                                z instanceof S)
                            )
                                g.pager_processAjaxOnInit && (a.$tbodies.eq(0).empty(), a.$tbodies.eq(0).append(z));
                            else if (R) {
                                for (l = 0; l < R; l++) {
                                    for (_ += "<tr>", p = 0; p < z[l].length; p++) _ += /^\s*<td/.test(z[l][p]) ? S.trim(z[l][p]) : "<td>" + z[l][p] + "</td>";
                                    _ += "</tr>";
                                }
                                g.pager_processAjaxOnInit && a.$tbodies.eq(0).html(_);
                            }
                            if (((g.pager_processAjaxOnInit = !0), b)) {
                                for (
                                    u = (c = j.hasClass("hasStickyHeaders"))
                                        ? g.$sticky
                                            .children("thead:first")
                                            .children("tr:not(." + a.cssIgnoreRow + ")")
                                            .children()
                                        : "",
                                        f = j.find("tfoot tr:first").children(),
                                        m = (h = a.$headers.filter("th")).length,
                                        p = 0;
                                    p < m;
                                    p++
                                )
                                    (w = h.eq(p)).find("." + $.css.icon).length
                                        ? ((P = w.find("." + $.css.icon).clone(!0)),
                                            w
                                                .find("." + $.css.headerIn)
                                                .html(b[p])
                                                .append(P),
                                        c &&
                                        u.length &&
                                        ((P = u
                                            .eq(p)
                                            .find("." + $.css.icon)
                                            .clone(!0)),
                                            u
                                                .eq(p)
                                                .find("." + $.css.headerIn)
                                                .html(b[p])
                                                .append(P)))
                                        : (w.find("." + $.css.headerIn).html(b[p]),
                                        c &&
                                        u.length &&
                                        ((o.$container = o.$container.add(g.$sticky)),
                                            u
                                                .eq(p)
                                                .find("." + $.css.headerIn)
                                                .html(b[p]))),
                                        f.eq(p).html(b[p]);
                                c && C.bindEvents(a);
                            }
                        }
                        a.showProcessing && $.isProcessing(s),
                            (x = C.parsePageSize(a, o.size, "get")),
                            (o.totalPages = "all" === x ? 1 : Math.ceil(o.totalRows / x)),
                            (o.last.totalRows = o.totalRows),
                            (o.last.currentFilters = o.currentFilters),
                            (o.last.sortList = (a.sortList || []).join(",")),
                            (o.initializing = !1),
                            C.updatePageDisplay(a, !1),
                            $.updateCache(a, function () {
                                o.initialized &&
                                setTimeout(function () {
                                    n && console.log("Pager >> Triggering pagerChange"), j.triggerHandler("pagerChange", o), $.applyWidget(s), C.updatePageDisplay(a);
                                }, 0);
                            });
                    }
                    o.initialized || $.applyWidget(s);
                },
                getAjax: function (i) {
                    var r,
                        e = C.getAjaxUrl(i),
                        s = S(document),
                        o = i.namespace + "pager",
                        g = i.pager;
                    "" !== e &&
                    (i.showProcessing && $.isProcessing(i.table, !0),
                        s.on("ajaxError" + o, function (e, a, t, r) {
                            C.renderAjax(null, i, a, t, r), s.off("ajaxError" + o);
                        }),
                        (r = ++g.ajaxCounter),
                        (g.last.ajaxUrl = e),
                        (g.ajaxObject.url = e),
                        (g.ajaxObject.success = function (e, a, t) {
                            r < g.ajaxCounter || (C.renderAjax(e, i, t), s.off("ajaxError" + o), "function" == typeof g.oldAjaxSuccess && g.oldAjaxSuccess(e));
                        }),
                    $.debug(i, "pager") && console.log("Pager >> Ajax initialized", g.ajaxObject),
                        S.ajax(g.ajaxObject));
                },
                getAjaxUrl: function (e) {
                    var a,
                        t,
                        r = e.pager,
                        i = e.widgetOptions,
                        s = i.pager_ajaxUrl
                            ? i.pager_ajaxUrl
                                .replace(/\{page([\-+]\d+)?\}/, function (e, a) {
                                    return r.page + (a ? parseInt(a, 10) : 0);
                                })
                                .replace(/\{size\}/g, r.size)
                            : "",
                        o = e.sortList,
                        g = r.currentFilters || e.$table.data("lastSearch") || [],
                        n = s.match(/\{\s*sort(?:List)?\s*:\s*(\w*)\s*\}/),
                        l = s.match(/\{\s*filter(?:List)?\s*:\s*(\w*)\s*\}/),
                        p = [];
                    if (n) {
                        for (n = n[1], t = o.length, a = 0; a < t; a++) p[p.length] = n + "[" + o[a][0] + "]=" + o[a][1];
                        (s = s.replace(/\{\s*sort(?:List)?\s*:\s*(\w*)\s*\}/g, p.length ? p.join("&") : n)), (p = []);
                    }
                    if (l) {
                        for (l = l[1], t = g.length, a = 0; a < t; a++) g[a] && (p[p.length] = l + "[" + a + "]=" + encodeURIComponent(g[a]));
                        (s = s.replace(/\{\s*filter(?:List)?\s*:\s*(\w*)\s*\}/g, p.length ? p.join("&") : l)), (r.currentFilters = g);
                    }
                    return S.isFunction(i.pager_customAjaxUrl) && (s = i.pager_customAjaxUrl(e.table, s)), $.debug(e, "pager") && console.log("Pager >> Ajax url = " + s), s;
                },
                renderTable: function (e, a) {
                    var t,
                        r,
                        i,
                        s,
                        o = e.table,
                        g = e.pager,
                        n = e.widgetOptions,
                        l = $.debug(e, "pager"),
                        p = e.$table.hasClass("hasFilters"),
                        d = (a && a.length) || 0,
                        c = "all" === g.size ? g.totalRows : g.size,
                        f = g.page * c;
                    if (d < 1) l && console.warn("Pager >> No rows for pager to render");
                    else {
                        if (g.page >= g.totalPages) return C.moveToLastPage(e, g);
                        if (((g.cacheIndex = []), (g.isDisabled = !1), g.initialized && (l && console.log("Pager >> Triggering pagerChange"), e.$table.triggerHandler("pagerChange", e)), n.pager_removeRows)) {
                            for ($.clearTableBody(o), t = $.processTbody(o, e.$tbodies.eq(0), !0), i = r = p ? 0 : f, s = 0; s < c && r < a.length; )
                                (p && g.regexFiltered.test(a[r][0].className)) || (f < ++i && s <= c && (s++, (g.cacheIndex[g.cacheIndex.length] = r), t.append(a[r]))), r++;
                            $.processTbody(o, t, !1);
                        } else C.hideRows(e);
                        C.updatePageDisplay(e), (n.pager_startPage = g.page), (n.pager_size = g.size), o.isUpdating && (l && console.log("Pager >> Triggering updateComplete"), e.$table.triggerHandler("updateComplete", [o, !0]));
                    }
                },
                showAllRows: function (e) {
                    var a,
                        t,
                        r,
                        i = e.table,
                        s = e.pager,
                        o = e.widgetOptions;
                    for (
                        s.ajax
                            ? C.pagerArrows(e, !0)
                            : (S.data(i, "pagerLastPage", s.page),
                                S.data(i, "pagerLastSize", s.size),
                                (s.page = 0),
                                (s.size = s.totalRows),
                                (s.totalPages = 1),
                                e.$table.addClass("pagerDisabled").removeAttr("aria-describedby").find("tr.pagerSavedHeightSpacer").remove(),
                                C.renderTable(e, e.rowsCopy),
                                (s.isDisabled = !0),
                                $.applyWidget(i),
                            $.debug(e, "pager") && console.log("Pager >> Disabled")),
                            r = (t = s.$container.find(o.pager_selectors.pageSize + "," + o.pager_selectors.gotoPage + ",.ts-startRow, .ts-page")).length,
                            a = 0;
                        a < r;
                        a++
                    )
                        t.eq(a).prop("aria-disabled", "true").addClass(o.pager_css.disabled)[0].disabled = !0;
                },
                updateCache: function (r) {
                    var i = r.pager;
                    $.updateCache(r, function () {
                        if (!S.isEmptyObject(r.cache)) {
                            var e,
                                a = [],
                                t = r.cache[0].normalized;
                            for (i.totalRows = t.length, e = 0; e < i.totalRows; e++) a[a.length] = t[e][r.columns].$row;
                            (r.rowsCopy = a), C.moveToPage(r, i, !0), (i.last.currentFilters = [" "]);
                        }
                    });
                },
                moveToPage: function (e, a, t) {
                    if (!a.isDisabled) {
                        if (!1 !== t && a.initialized && S.isEmptyObject(e.cache)) return C.updateCache(e);
                        var r,
                            i = e.table,
                            s = e.widgetOptions,
                            o = a.last,
                            g = $.debug(e, "pager");
                        (a.ajax && !s.filter_initialized && $.hasWidget(i, "filter")) ||
                        (C.parsePageNumber(e, a),
                            C.calcFilters(e),
                            (o.currentFilters = "" === (o.currentFilters || []).join("") ? [] : o.currentFilters),
                            (a.currentFilters = "" === (a.currentFilters || []).join("") ? [] : a.currentFilters),
                        (o.page === a.page &&
                            o.size === a.size &&
                            o.totalRows === a.totalRows &&
                            (o.currentFilters || []).join(",") === (a.currentFilters || []).join(",") &&
                            (o.ajaxUrl || "") === (a.ajaxObject.url || "") &&
                            (o.optAjaxUrl || "") === (s.pager_ajaxUrl || "") &&
                            o.sortList === (e.sortList || []).join(",")) ||
                        (g && console.log("Pager >> Changing to page " + a.page),
                            (a.last = { page: a.page, size: a.size, sortList: (e.sortList || []).join(","), totalRows: a.totalRows, currentFilters: a.currentFilters || [], ajaxUrl: a.ajaxObject.url || "", optAjaxUrl: s.pager_ajaxUrl }),
                            a.ajax
                                ? s.pager_processAjaxOnInit || S.isEmptyObject(s.pager_initialRows)
                                    ? C.getAjax(e)
                                    : ((s.pager_processAjaxOnInit = !0),
                                        (r = s.pager_initialRows),
                                        (a.totalRows = void 0 !== r.total ? r.total : (g && console.error("Pager >> No initial total page set!")) || 0),
                                        (a.filteredRows = void 0 !== r.filtered ? r.filtered : (g && console.error("Pager >> No initial filtered page set!")) || 0),
                                        C.updatePageDisplay(e, !1))
                                : a.ajax || C.renderTable(e, e.rowsCopy),
                            S.data(i, "pagerLastPage", a.page),
                        a.initialized &&
                        !1 !== t &&
                        (g && console.log("Pager >> Triggering pageMoved"),
                            e.$table.triggerHandler("pageMoved", e),
                            $.applyWidget(i),
                        !a.ajax && i.isUpdating && (g && console.log("Pager >> Triggering updateComplete"), e.$table.triggerHandler("updateComplete", [i, !0])))));
                    }
                },
                getTotalPages: function (e, a) {
                    return $.hasWidget(e.table, "filter") ? Math.min(a.totalPages, a.filteredPages) : a.totalPages;
                },
                parsePageNumber: function (e, a) {
                    var t = C.getTotalPages(e, a) - 1;
                    return (a.page = parseInt(a.page, 10)), (a.page < 0 || isNaN(a.page)) && (a.page = 0), a.page > t && 0 <= t && (a.page = t), a.page;
                },
                parsePageSize: function (e, a, t) {
                    var r = e.pager,
                        i = e.widgetOptions,
                        s = parseInt(a, 10) || r.size || i.pager_size || 10;
                    return r.initialized && (/all/i.test(s + " " + a) || s === r.totalRows) ? (r.$container.find(i.pager_selectors.pageSize + ' option[value="all"]').length ? "all" : r.totalRows) : "get" === t ? s : r.size;
                },
                setPageSize: function (e, a) {
                    var t = e.pager,
                        r = e.table;
                    (t.size = C.parsePageSize(e, a, "get")),
                        t.$container.find(e.widgetOptions.pager_selectors.pageSize).val(t.size),
                        S.data(r, "pagerLastPage", C.parsePageNumber(e, t)),
                        S.data(r, "pagerLastSize", t.size),
                        (t.totalPages = "all" === t.size ? 1 : Math.ceil(t.totalRows / t.size)),
                        (t.filteredPages = "all" === t.size ? 1 : Math.ceil(t.filteredRows / t.size));
                },
                moveToFirstPage: function (e, a) {
                    (a.page = 0), C.moveToPage(e, a, !0);
                },
                moveToLastPage: function (e, a) {
                    (a.page = C.getTotalPages(e, a) - 1), C.moveToPage(e, a, !0);
                },
                moveToNextPage: function (e, a) {
                    a.page++;
                    var t = C.getTotalPages(e, a) - 1;
                    a.page >= t && (a.page = t), C.moveToPage(e, a, !0);
                },
                moveToPrevPage: function (e, a) {
                    a.page--, a.page <= 0 && (a.page = 0), C.moveToPage(e, a, !0);
                },
                destroyPager: function (e, a) {
                    var t = e.table,
                        r = e.pager,
                        i = e.widgetOptions.pager_selectors || {},
                        s = [i.first, i.prev, i.next, i.last, i.gotoPage, i.pageSize].join(","),
                        o = e.namespace + "pager";
                    if (r) {
                        if (((r.initialized = !1), e.$table.off(o), r.$container.hide().find(s).off(o), a)) return;
                        (e.appender = null), C.showAllRows(e), $.storage && $.storage(t, e.widgetOptions.pager_storageKey, ""), (r.$container = null), (e.pager = null), (e.rowsCopy = null);
                    }
                },
                enablePager: function (e, a) {
                    var t,
                        r,
                        i = e.table,
                        s = e.pager,
                        o = e.widgetOptions,
                        g = s.$container.find(o.pager_selectors.pageSize);
                    (s.isDisabled = !1),
                        (s.page = S.data(i, "pagerLastPage") || s.page || 0),
                        (r = g.find("option[selected]").val()),
                        (s.size = S.data(i, "pagerLastSize") || C.parsePageSize(e, r, "get")),
                        C.setPageSize(e, s.size),
                        (s.totalPages = "all" === s.size ? 1 : Math.ceil(C.getTotalPages(e, s) / s.size)),
                        e.$table.removeClass("pagerDisabled"),
                    i.id && !e.$table.attr("aria-describedby") && ((t = (g = s.$container.find(o.pager_selectors.pageDisplay)).attr("id")) || ((t = i.id + "_pager_info"), g.attr("id", t)), e.$table.attr("aria-describedby", t)),
                        C.changeHeight(e),
                    a && ($.update(e), C.setPageSize(e, s.size), C.moveToPage(e, s, !0), C.hideRowsSetup(e), $.debug(e, "pager") && console.log("Pager >> Enabled"));
                },
                appender: function (e, a) {
                    var t = e.config,
                        r = t.widgetOptions,
                        i = t.pager;
                    i.ajax
                        ? C.moveToPage(t, i, !0)
                        : ((t.rowsCopy = a),
                            (i.totalRows = r.pager_countChildRows ? t.$tbodies.eq(0).children("tr").length : a.length),
                            (i.size = S.data(e, "pagerLastSize") || i.size || r.pager_size || i.setSize || 10),
                            (i.totalPages = "all" === i.size ? 1 : Math.ceil(i.totalRows / i.size)),
                            C.moveToPage(t, i),
                            C.updatePageDisplay(t, !1));
                },
            }),
            ($.showError = function (e, a, t, r) {
                function i() {
                    o.$table.find("thead").find(o.selectorRemove).remove();
                }
                var s = S(e),
                    o = s[0].config,
                    g = o && o.widgetOptions,
                    n = (o.pager && o.pager.cssErrorRow) || (g && g.pager_css && g.pager_css.errorRow) || "tablesorter-errorRow",
                    l = typeof a,
                    p = !0,
                    d = "";
                if (s.length) {
                    if ("function" == typeof o.pager.ajaxError) {
                        if (!1 === (p = o.pager.ajaxError(o, a, t, r))) return i();
                        d = p;
                    } else if ("function" == typeof g.pager_ajaxError) {
                        if (!1 === (p = g.pager_ajaxError(o, a, t, r))) return i();
                        d = p;
                    }
                    if ("" === d)
                        if ("object" == l)
                            d =
                                0 === a.status
                                    ? "Not connected, verify Network"
                                    : 404 === a.status
                                        ? "Requested page not found [404]"
                                        : 500 === a.status
                                            ? "Internal Server Error [500]"
                                            : "parsererror" === r
                                                ? "Requested JSON parse failed"
                                                : "timeout" === r
                                                    ? "Time out error"
                                                    : "abort" === r
                                                        ? "Ajax Request aborted"
                                                        : "Uncaught error: " + a.statusText + " [" + a.status + "]";
                        else {
                            if ("string" != l) return i();
                            d = a;
                        }
                    S(/tr\>/.test(d) ? d : '<tr><td colspan="' + o.columns + '">' + d + "</td></tr>")
                        .click(function () {
                            S(this).remove();
                        })
                        .appendTo(o.$table.find("thead:first"))
                        .addClass(n + " " + o.selectorRemove.slice(1))
                        .attr({ role: "alert", "aria-live": "assertive" });
                } else console.error("tablesorter showError: no table parameter passed");
            });
    })(jQuery);
    return jQuery;
});
