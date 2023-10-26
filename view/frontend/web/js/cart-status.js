define([
    "jquery",
    "mage/url",
    "Magento_Customer/js/customer-data"
],
function ($, url, customerData) {
    "use strict";

    return function(config) {
        const threshold = 5;
        let checkoutConfig = customerData.get('rally-checkout-config'),
            cartData = customerData.get('cart');
        window.cartStatusCheck = 1;

        if (checkoutConfig() && checkoutConfig().id) {
            window.RallyCheckoutData = checkoutConfig();
            window.RallyCheckoutData.content = cartData();
        } else {
            window.RallyCheckoutData = config.checkoutConfig;
        }
        checkoutConfig.subscribe(function (config) {
            window.RallyCheckoutData = config;
            window.RallyCheckoutData.content = cartData();
            if (window.Rally === undefined) {
                addRallyScript();
            }
            if (window.Rally && window.Rally.hasOwnProperty('applyCartData')) {
                window.Rally.applyCartData({
                    id: window.RallyCheckoutData.id,
                    currency: window.RallyCheckoutData.currency
                });
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

        function addRallyScript() {
            if (config.loadSdk) {
                var element = document.createElement("script");
                element.src = config.sdkUrl;
                var l = document.getElementsByTagName("script")[0];
                l.parentNode.insertBefore(element, l);
            }
        }

        $('.customer-address-form .form-address-edit').on('submit', function() {
            const sections = ['rally-checkout-config'];
            customerData.invalidate(sections);
        });

        checkCartStatus();
        addRallyScript();
    }
});
