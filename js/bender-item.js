/*
 * Bender — item-detail behaviour: photo gallery lightbox and the share dialog.
 * Vanilla JS only. No jQuery, no Fancybox.
 */
(function () {
    "use strict";

    // Lightbox: binds the existing a.fancybox thumbnails (+ .main-photo) to a
    // native <dialog>, grouped by data-fancybox-group for prev/next.
    function bindLightbox() {
        var thumbs = Array.prototype.slice.call(document.querySelectorAll("a.fancybox"));
        var mainPhoto = document.querySelector(".main-photo");
        var box = document.getElementById("bender-lightbox");
        if (!box || typeof box.showModal !== "function" || (!thumbs.length && !mainPhoto)) { return; }

        var img = box.querySelector("[data-lightbox-img]");
        var count = box.querySelector("[data-lightbox-count]");

        var group = thumbs.length ? (thumbs[0].getAttribute("data-fancybox-group") || "group") : "group";
        var list = thumbs
            .filter(function (a) { return (a.getAttribute("data-fancybox-group") || "group") === group; })
            .map(function (a) { return { href: a.getAttribute("href"), title: a.getAttribute("title") || "" }; });
        if (!list.length && mainPhoto) {
            list = [{ href: mainPhoto.getAttribute("href"), title: mainPhoto.getAttribute("title") || "" }];
        }
        if (!list.length) { return; }

        box.querySelectorAll("[data-lightbox-step]").forEach(function (nav) {
            nav.hidden = list.length < 2;
        });

        var current = 0;
        function show(i) {
            current = (i + list.length) % list.length;
            var photo = list[current];
            if (img) { img.src = photo.href; img.alt = photo.title; }
            if (count) { count.textContent = (current + 1) + " / " + list.length; }
        }
        function open(i) {
            show(i);
            box.showModal();
        }

        thumbs.forEach(function (a, i) {
            a.addEventListener("click", function (e) {
                e.preventDefault();
                open(i);
            });
        });
        if (mainPhoto) {
            mainPhoto.addEventListener("click", function (e) {
                e.preventDefault();
                open(0);
            });
        }

        box.querySelectorAll("[data-lightbox-step]").forEach(function (nav) {
            nav.addEventListener("click", function () {
                show(current + (parseInt(nav.getAttribute("data-lightbox-step"), 10) || 1));
            });
        });
        box.addEventListener("keydown", function (e) {
            if (list.length < 2) { return; }
            if (e.key === "ArrowLeft") { show(current - 1); }
            else if (e.key === "ArrowRight") { show(current + 1); }
        });
    }

    // Generic native-<dialog> open/close: [data-dialog-open="id"] opens, and
    // [data-dialog-close] or a backdrop click closes the nearest dialog.
    function bindDialogs() {
        document.querySelectorAll("[data-dialog-open]").forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();
                var dlg = document.getElementById(btn.getAttribute("data-dialog-open"));
                if (dlg && typeof dlg.showModal === "function") { dlg.showModal(); }
            });
        });
        document.querySelectorAll("dialog").forEach(function (dlg) {
            dlg.addEventListener("click", function (e) {
                if (e.target === dlg) { dlg.close(); }
            });
            dlg.querySelectorAll("[data-dialog-close]").forEach(function (btn) {
                btn.addEventListener("click", function () { dlg.close(); });
            });
        });
    }

    // Share dialog contents: reveal the OS share sheet where navigator.share
    // exists, and wire the copy-link button.
    function bindShare() {
        var native = document.querySelector("[data-share-native]");
        if (native && navigator.share) {
            native.hidden = false;
            native.addEventListener("click", function () {
                navigator.share({
                    title: native.getAttribute("data-share-title") || document.title,
                    url: native.getAttribute("data-share-url") || location.href
                }).catch(function () {});
            });
        }

        var copy = document.querySelector("[data-share-copy]");
        var input = document.querySelector("[data-share-url]");
        var status = document.querySelector("[data-share-status]");
        if (copy && input) {
            copy.addEventListener("click", function () {
                var done = function (msg) { if (status) { status.textContent = msg; } };
                var ok = copy.getAttribute("data-copied") || "Link copied.";
                if (navigator.clipboard && navigator.clipboard.writeText) {
                    navigator.clipboard.writeText(input.value).then(function () { done(ok); },
                        function () { input.select(); done(copy.getAttribute("data-select") || ""); });
                } else {
                    input.select();
                    try { document.execCommand("copy"); done(ok); } catch (e) {}
                }
            });
        }
    }

    function ready(fn) {
        if (document.readyState !== "loading") { fn(); }
        else { document.addEventListener("DOMContentLoaded", fn); }
    }

    ready(function () {
        bindLightbox();
        bindDialogs();
        bindShare();
    });
})();
