define(function () {
    'use strict';

    var mixin = {
        /**
         * @param {Object} item
         * @return {null}
         */
        getSrc: function (item) {
            let itemsImageData = window.checkoutConfig.imageData;

            if (this.imageData !== itemsImageData) {
                this.imageData = itemsImageData;
            }

            return this._super();
        },
    };

    return function (target) {
        return target.extend(mixin);
    };
});
