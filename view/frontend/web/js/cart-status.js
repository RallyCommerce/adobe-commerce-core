define([
    'jquery',
    'mage/url',
    'Magento_Customer/js/customer-data'
],
function ($, url, customerData) {
    "use strict";

    return function(config) {
        const threshold = 5;
        let checkoutConfig = customerData.get('rally-checkout-config'),
            cartData = customerData.get('cart'),
            checkoutData = window.checkoutConfig,
            path = window.location.pathname;
        window.cartStatusCheck = 1;
        window.RallyCheckoutData = config.checkoutConfig;

        if (checkoutData && checkoutData.checkoutUrl.includes(path)) {
            window.RallyCheckoutData.rallyConfig.product = 'RALLY_OFFERS';
        }

        function addRallyScript() {
            if (config.loadSdk) {
                var element = document.createElement("script");
                element.src = config.sdkUrl;
                var l = document.getElementsByTagName("script")[0];
                l.parentNode.insertBefore(element, l);
            }
        }
        addRallyScript();

        if (checkoutConfig() && checkoutConfig().id) {
            window.RallyCheckoutData = checkoutConfig();
            window.RallyCheckoutData.content = cartData();

            if (checkoutData && checkoutData.checkoutUrl.includes(path)) {
                window.RallyCheckoutData.rallyConfig.product = 'RALLY_OFFERS';
            }

            document.dispatchEvent(new CustomEvent('rally.platform.initiated'));
        }

        window.RallyCheckoutData.refresh = reloadCartData;
        checkoutConfig.subscribe(function (config) {
            window.RallyCheckoutData = config;
            window.RallyCheckoutData.content = cartData();
            window.RallyCheckoutData.refresh = reloadCartData;
            if (window.Rally === undefined) {
                addRallyScript();
            }
            if (window.Rally && window.Rally.hasOwnProperty('applyCartData')) {
                window.Rally.applyCartData({
                    id: window.RallyCheckoutData.id,
                    currency: window.RallyCheckoutData.currency
                });
            }
            if (checkoutData && checkoutData.checkoutUrl.includes(path)) {
                window.RallyCheckoutData.rallyConfig.product = 'RALLY_OFFERS';
            }
        }.bind(this));

        function checkCartStatus() {
            if (window.Rally === undefined || window.Rally.q === undefined) {
                window.cartStatusCheck++;
                if (window.cartStatusCheck < threshold) {
                    window.setTimeout(checkCartStatus, 1000);
                }
            } else if (window.Rally.q.length > 0) {
                $.ajax({
                    url: url.build('rally/index/checkcustomercart'),
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if (!data.status) {
                            const sections = ['cart', 'rally-checkout-config'];
                            customerData.invalidate(sections);
                            customerData.reload(sections, true);
                        }
                    }
                });
            }
        }

        function reloadCartData(callback) {
            let sections = ['cart'];
            window.reloadCart = true;
            customerData.reload(sections).done(function () {
                typeof callback === 'function' && callback();
            });
        }

        $('.customer-address-form .form-address-edit').on('submit', function() {
            const sections = ['rally-checkout-config'];
            customerData.invalidate(sections);
        });

        checkCartStatus();
    }
});
