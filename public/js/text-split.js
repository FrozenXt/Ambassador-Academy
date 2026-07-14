/*!
 * text-split.js
 * A tiny drop-in replacement for GSAP's Club GreenSock "SplitText" plugin.
 * Implements the same minimal surface used in script.js: `new SplitText(el, {type})`
 * returning `.words`, `.lines`, and `.chars` arrays of elements, plus `.revert()`.
 * Not affiliated with GreenSock — swap in the real SplitText.min.js if you have
 * a Club GreenSock license for more accurate line-breaking.
 */
(function (global) {
    function wrapUnits(el, unitRegex, className) {
        const original = el.innerHTML;
        const text = el.textContent;
        const units = text.split(unitRegex).filter((u) => u.length);
        const frag = document.createDocumentFragment();

        text.split(unitRegex).forEach((raw, i) => {
            if (raw === "") return;
            const span = document.createElement("span");
            span.className = className;
            span.style.display = "inline-block";
            span.style.willChange = "transform, opacity";
            span.textContent = raw;
            frag.appendChild(span);
            // preserve spacing between words
            if (className === "split-word" && i < units.length) {
                frag.appendChild(document.createTextNode(" "));
            }
        });

        el.dataset.splitOriginal = original;
        el.innerHTML = "";
        el.appendChild(frag);
        return Array.from(el.querySelectorAll("." + className));
    }

    function SplitText(target, opts) {
        opts = opts || {};
        const type = opts.type || "lines,words";
        const els =
            typeof target === "string"
                ? document.querySelectorAll(target)
                : [target];

        this.words = [];
        this.chars = [];
        this.lines = [];
        this._els = [];

        els.forEach((el) => {
            this._els.push(el);
            if (type.indexOf("chars") > -1) {
                this.chars = this.chars.concat(
                    wrapUnits(el, /(?=.)/, "split-char"),
                );
            } else if (type.indexOf("words") > -1) {
                this.words = this.words.concat(
                    wrapUnits(el, /\s+/, "split-word"),
                );
            }
            // "lines" isn't distinguished here (needs layout measurement); treat
            // each block element itself as a single line wrapper for masking.
            this.lines.push(el);
        });
    }

    SplitText.prototype.revert = function () {
        this._els.forEach((el) => {
            if (el.dataset.splitOriginal !== undefined) {
                el.innerHTML = el.dataset.splitOriginal;
                delete el.dataset.splitOriginal;
            }
        });
    };

    // Only define it if the real (paid) plugin isn't already present.
    if (!global.SplitText) {
        global.SplitText = SplitText;
    }
})(window);
