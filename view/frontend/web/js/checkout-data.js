define([
    'jquery',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/customer-data',
    'Magento_SalesRule/js/model/coupon',
    'Magento_Checkout/js/action/get-totals',
    'Magento_Checkout/js/action/recollect-shipping-rates',
    'Magento_Checkout/js/model/shipping-service',
    'Magento_Checkout/js/action/select-shipping-method',
    'Magento_Checkout/js/checkout-data'
],
function (
    $,
    quote,
    customerData,
    coupon,
    getTotalsAction,
    getRatesAction,
    shippingService,
    selectShippingMethodAction,
    mageCheckoutData
) {
    "use strict";

    return function() {
        let cartData = customerData.get('cart'),
            checkoutData = window.checkoutConfig,
            path = window.location.pathname;

        cartData.subscribe(function (cart) {
            if (!window.reloadCart) {
                return;
            }

            let itemsImageData = cart.items.reduce((images, cartItem) => {
                images[cartItem.item_id] = cartItem.product_image;
                return images;
            }, {});

            window.reloadCart = false;
            window.checkoutConfig.imageData = itemsImageData;

            if (cart.summary_count < 1 || cart.items.length < 1) {
                return;
            }

            let deferred = $.Deferred();
            getTotalsAction([], deferred);
            $.when(deferred).done(updateCouponCallback);
        });

        quote.shippingAddress.subscribe(function (address) {
            if (window.RallyCheckoutData.customerData) {
                let windowAddressData = window.RallyCheckoutData.customerData.shipping,
                    addressData = {
                        'countryCode': address.countryId,
                        'province': address.regionCode,
                        'zip': address.postcode
                    };

                window.RallyCheckoutData.customerData.shipping = {...windowAddressData, ...addressData};
            }
        });

        if (checkoutData && checkoutData.checkoutUrl.includes(path) && window.RallyCheckoutData) {
            window.RallyCheckoutData.rallyConfig.product = 'RALLY_OFFERS';
        }

        function updateCouponCallback() {
            let cartTotals = quote.totals();

            if (cartTotals && cartTotals['coupon_code']) {
                coupon.setCouponCode(cartTotals['coupon_code']);
                coupon.setIsApplied(true);
            } else {
                coupon.setCouponCode('');
                coupon.setIsApplied(false);
            }

            getRatesAction();

            if (cartTotals && cartTotals.total_segments) {
                let shippingData = cartTotals.total_segments.find(item => item.code === 'shipping');

                if (!shippingData) {
                    return;
                }

                let shippingRates = shippingService.getShippingRates();
                let selectedMethod = shippingRates.filter(item => {
                    if (!item) {
                        return false;
                    }

                    const title = item.carrier_title + ' - ' + item.method_title;
                    return shippingData.title.includes(title);
                });

                if (selectedMethod.length < 1) {
                    return;
                }

                let shippingMethod = selectedMethod[0];
                selectShippingMethodAction(shippingMethod);
                mageCheckoutData.setSelectedShippingRate(shippingMethod['carrier_code'] + '_' + shippingMethod['method_code']);
            }
        };
    }
});
