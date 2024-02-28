var config = {
    map: {
        '*': {
            checkCustomerCart: 'Rally_Checkout/js/cart-status',
            rallyCheckoutData: 'Rally_Checkout/js/checkout-data'
        }
    },
    config: {
        mixins: {
            'Magento_Checkout/js/view/summary/item/details/thumbnail': {
                'Rally_Checkout/js/view/summary/item/details/thumbnail-mixin': true
            },
            'Magento_Checkout/js/view/summary/cart-items': {
                'Rally_Checkout/js/view/summary/cart-items-mixin': true
            }
        }
    }
}
