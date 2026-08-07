/*
 * Bender — account behaviour: delete-account confirmation dialog.
 * Vanilla JS only. No jQuery, no jQuery-UI.
 */
(function () {
    "use strict";

    function ready(fn) {
        if (document.readyState !== "loading") { fn(); }
        else { document.addEventListener("DOMContentLoaded", fn); }
    }

    ready(function () {
        var trigger = document.querySelector(".opt_delete_account a");
        var source = document.getElementById("dialog-delete-account");
        if (!trigger || !source) { return; }

        var langs = (window.bender && bender.langs) || {};

        function goDelete() {
            window.location = bender.base_url + "?page=user&action=delete&id=" + bender.user.id
                + "&secret=" + bender.user.secret;
        }

        if (typeof source.showModal !== "function" && !("HTMLDialogElement" in window)) {
            trigger.addEventListener("click", function (e) {
                e.preventDefault();
                if (window.confirm(source.textContent.trim())) { goDelete(); }
            });
            return;
        }

        // Swap the plain #dialog-delete-account div (previously handed to
        // jQuery-UI's .dialog()) for a real <dialog>, keeping the same id and
        // message; its title attribute becomes the heading.
        var dialog = document.createElement("dialog");
        dialog.id = source.id;
        source.removeAttribute("id");

        var heading = document.createElement("strong");
        heading.textContent = source.getAttribute("title") || "";
        var message = document.createElement("p");
        message.textContent = source.textContent.trim();

        var deleteBtn = document.createElement("button");
        deleteBtn.type = "button";
        deleteBtn.textContent = langs.delete || "Delete";
        deleteBtn.addEventListener("click", goDelete);

        var cancelBtn = document.createElement("button");
        cancelBtn.type = "button";
        cancelBtn.textContent = langs.cancel || "Cancel";
        cancelBtn.addEventListener("click", function () { dialog.close(); });

        dialog.appendChild(heading);
        dialog.appendChild(message);
        dialog.appendChild(deleteBtn);
        dialog.appendChild(cancelBtn);
        dialog.addEventListener("click", function (e) {
            if (e.target === dialog) { dialog.close(); }
        });

        source.parentNode.replaceChild(dialog, source);

        trigger.addEventListener("click", function (e) {
            e.preventDefault();
            dialog.showModal();
        });
    });
})();
