define([
    'Magento_Checkout/js/model/totals'
], function (totals) {
    'use strict';

    var mixin = {
        /**
         * Returns cart items qty
         *
         * @returns {Number}
         */
        getItemsQty: function () {
            let cartTotals = totals.totals();

            if (Number(this.totals['items_qty']) !== Number(cartTotals['items_qty'])) {
                this.totals = cartTotals;
            }
            return this._super();
        },
    };

    return function (target) {
        return target.extend(mixin);
    };
});
