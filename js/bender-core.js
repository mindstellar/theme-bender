/*
 * Bender — core behaviour (loaded on every page).
 * Body-class toggles, the collapsible sidebar category list, the "sort by"
 * hover menu, flash-message dismiss, the location mask auto-submit, and the
 * select-box skin. Vanilla JS only. No jQuery.
 */
(function () {
    "use strict";

    // [data-bclass-toggle="CLASS"] toggles CLASS on <body> and prevents the
    // link's default navigation (used for the mobile search/category/filter
    // reveal toggles, which are plain "#" links).
    function bindBodyClassToggles() {
        document.querySelectorAll("[data-bclass-toggle]").forEach(function (el) {
            el.addEventListener("click", function (e) {
                var classes = el.getAttribute("data-bclass-toggle").split(/\s+/);
                classes.forEach(function (c) {
                    if (c) { document.body.classList.toggle(c); }
                });
                e.preventDefault();
            });
        });
    }

    // Mobile category list: ".r-list h1 span" expands/collapses its subcategory
    // list. Only active in the responsive layout, mirroring #responsive-trigger
    // being shown only under the mobile breakpoint.
    function isResponsive() {
        var trigger = document.getElementById("responsive-trigger");
        return !!trigger && getComputedStyle(trigger).display !== "none";
    }

    function bindCategoryCollapse() {
        document.querySelectorAll(".r-list h1 span").forEach(function (span) {
            span.addEventListener("click", function (e) {
                if (!isResponsive()) { return; }
                var parent = span.closest("li");
                var icon = span.querySelector("i");
                var open = parent.classList.toggle("active");
                if (icon) {
                    icon.classList.toggle("fa-caret-down", open);
                    icon.classList.toggle("fa-caret-right", !open);
                }
                e.preventDefault();
            });
        });
    }

    // Search results "Sort by" — a hover-revealed menu, driven by the .hover
    // class rather than CSS :hover (see sass/pagination.scss).
    function bindSortByHover() {
        document.querySelectorAll(".see_by").forEach(function (el) {
            el.addEventListener("mouseenter", function () { el.classList.add("hover"); });
            el.addEventListener("mouseleave", function () { el.classList.remove("hover"); });
        });
    }

    // Flash messages: core prints a `.ico-close` dismiss anchor with no
    // behaviour of its own and no href (so not focusable) — wire it up and
    // make it keyboard-operable.
    function bindFlashDismiss() {
        document.querySelectorAll(".flashmessage .ico-close").forEach(function (btn) {
            btn.setAttribute("role", "button");
            btn.setAttribute("tabindex", "0");
            var label = btn.getAttribute("data-oc-close-label");
            if (label) { btn.setAttribute("aria-label", label); }
            function dismiss() {
                var box = btn.closest(".flashmessage");
                if (box && box.parentNode) { box.parentNode.removeChild(box); }
            }
            btn.addEventListener("click", function (e) { e.preventDefault(); dismiss(); });
            btn.addEventListener("keydown", function (e) {
                if (e.key === "Enter" || e.key === " ") { e.preventDefault(); dismiss(); }
            });
        });
    }

    // #mask_as_form: choosing a location in the mask select auto-submits.
    function bindMaskAsForm() {
        var select = document.querySelector("#mask_as_form select");
        var form = document.getElementById("mask_as_form");
        if (!select || !form) { return; }
        select.addEventListener("change", function () { form.submit(); });
    }

    // Select-box skin: wraps a <select> in a styled .select-box (label + icon)
    // so it renders as bender's own dropdown instead of the bare OS control.
    // .select-box/.select-box-label/.select-box-icon are real, styled classes
    // (sass/ui.scss), so this stays even though selects are otherwise native.
    // ItemForm's category selects are added/removed after load and dispatch
    // "created"/"removed" CustomEvents for exactly this hook.
    function selectedLabel(select) {
        var opt = select.options[select.selectedIndex];
        return opt ? opt.text.replace(/^\s+/, "") : "";
    }

    function skinSelect(select) {
        if (select.closest(".select-box")) { return; }

        var wrap = document.createElement("div");
        wrap.className = "select-box " + (select.className || "");

        var trigger = document.createElement("a");
        trigger.href = "#";
        trigger.className = "select-box-trigger";
        trigger.addEventListener("click", function (e) { e.preventDefault(); });

        var label = document.createElement("span");
        label.className = "select-box-label";
        label.textContent = selectedLabel(select);

        var icon = document.createElement("span");
        icon.className = "select-box-icon";
        icon.textContent = "0";

        trigger.appendChild(label);
        trigger.appendChild(icon);

        select.parentNode.insertBefore(wrap, select);
        wrap.appendChild(select);
        wrap.appendChild(trigger);

        select.style.opacity = "0";
        select.addEventListener("focus", function () { wrap.classList.add("select-box-focus"); });
        select.addEventListener("blur", function () { wrap.classList.remove("select-box-focus"); });
        select.addEventListener("change", function () { label.textContent = selectedLabel(select); });
        select.addEventListener("removed", function () {
            if (wrap.parentNode) { wrap.parentNode.removeChild(wrap); }
        });
    }

    function bindSelectUi() {
        document.querySelectorAll("select").forEach(skinSelect);
        document.addEventListener("created", function (e) {
            if (e.target.matches && e.target.matches('[name^="select_"]')) {
                skinSelect(e.target);
            }
        });
    }

    function ready(fn) {
        if (document.readyState !== "loading") { fn(); }
        else { document.addEventListener("DOMContentLoaded", fn); }
    }

    ready(function () {
        bindBodyClassToggles();
        bindCategoryCollapse();
        bindSortByHover();
        bindFlashDismiss();
        bindMaskAsForm();
        bindSelectUi();
    });
})();
