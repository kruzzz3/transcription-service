/************************************************************************
 * VENDORS
 ***********************************************************************/

window.$ = window.jQuery = require('jquery'); // jQuery
// Fix lighthouse scroll-listener "issue"
window.$.event.special.touchstart = window.jQuery.event.special.touchstart = {
    setup: function (_, ns, handle) {
        this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
    }
};
window.$.event.special.touchmove = window.jQuery.event.special.touchmove = {
    setup: function (_, ns, handle) {
        this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
    }
};
window.$.event.special.wheel = window.jQuery.event.special.wheel = {
    setup: function (_, ns, handle) {
        this.addEventListener("wheel", handle, { passive: true });
    }
};
window.$.event.special.mousewheel = window.jQuery.event.special.mousewheel = {
    setup: function (_, ns, handle) {
        this.addEventListener("mousewheel", handle, { passive: true });
    }
};

require('bootstrap'); // bootstrap


/************************************************************************
 * CUSTOM SCRIPTS
 ***********************************************************************/

$(function () {
    if (window.customInit === undefined) {
        window.customInit = [];
    }

    // Call custom init stack
    for (i = 0; i < window.customInit.length; i++) {
        window.customInit[i]();
    }
});

