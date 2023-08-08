# Rally Checkout Adobe Commerce (Magento 2+) extension
An Adobe Commerce (Magento 2+) extension for the Rally Checkout.

# Rally Checkout Adobe Commerce module
Rally\Checkout module allows merchant to register sale transaction with the customer. Module implements consumer flow
that includes such actions like providing shipping and billing information and confirming
the purchase.

## Structure

[Learn about a typical file structure for a Magento 2 module](https://devdocs.magento.com/guides/v2.4/extension-dev-guide/build/module-file-structure.html).

## Installation

This module modifies the following tables in the database which configured to DBMS=MySQL and ENGINE=InnoDB:

- `quote` - adds column `shipping_costs` to save PPO item(s) shipping costs.
- `sales_order` - adds columns `shipping_costs` and `review_transaction` to save PPO item(s) shipping costs.
- `quote_item` - adds column `is_ppo` to set flag for PPO item(s).
- `sales_order_item` - adds column `is_ppo`  to set flag for PPO item(s).

For information about module installation in Magento 2, see [Enable or disable modules](https://devdocs.magento.com/guides/v2.4/install-gde/install/cli/install-cli-subcommands-enable.html).

## Configuration

To configure extension please navigate to Magento 2 Admin Panel > Stores > Rally > Checkout

- Enable Rally Checkout - `rally_checkout/general/enabled` (Yes/No)
- Enable Sandbox mode - `rally_checkout/general/sandbox` (Yes/No)
- Automatically Load JS SDK - `rally_checkout/general/load_sdk` (Yes/No)
- API key - `rally_checkout/general/api_key` (Encrypted Input)
- Client ID - `rally_checkout/general/client_id` (Input)

### Observer

This module observes the following events:
- `etc/events.xml`
	- `sales_order_creditmemo_save_after` event in
	   `Rally\Checkout\Observer\RefundOrderWebhookObserver` and
       `Rally\Checkout\Observer\OrderStatusWebhookObserver` files.
    - `sales_order_invoice_save_after` event in
       `Rally\Checkout\Observer\OrderStatusWebhookObserver` file.
    - `sales_order_shipment_save_after` event in
       `Rally\Checkout\Observer\OrderStatusWebhookObserver` file.
    - `sales_order_save_after` event in
       `Rally\Checkout\Observer\OrderUpdateWebhookObserver` file.
    - `admin_sales_order_address_update` event in
       `Rally\Checkout\Observer\OrderUpdateWebhookObserver` file.
    - `catalog_product_attribute_update_before` event in
       `Rally\Checkout\Observer\ProductMassUpdateAfterObserver` file.
    - `checkout_submit_all_after` event in
       `Rally\Checkout\Observer\InventoryUpdateAfterObserver` file.
    - `ppo_order_update_after` event in
       `Rally\Checkout\Observer\InventoryUpdateAfterObserver` file.
    - `rally_create_guest_account` event in
       `Rally\Checkout\Observer\GuestToCustomerObserver` file.

- `/etc/adminhtml/events.xml`
	- `admin_system_config_changed_section_rally_checkout` event in
	   `Rally\Checkout\Observer\RallyConfigSaveObserver` file.
    - `admin_system_config_changed_section_general` event in
	   `Rally\Checkout\Observer\StoreConfigSaveObserver` file.
    - `catalog_product_delete_after_done` event in
	   `Rally\Checkout\Observer\ProductDeleteAfterObserver` file.
    - `catalog_category_delete_after_done` event in
	   `Rally\Checkout\Observer\CategoryDeleteAfterObserver` file.
    - `catalog_category_save_after` event in
	   `Rally\Checkout\Observer\CategoryUpdateAfterObserver` file.
    - `catalog_product_save_after` event in
	   `Rally\Checkout\Observer\ProductUpdateAfterObserver` file.

### Layouts

This module introduces the following layouts in the `view/frontend/layout` and `view/adminhtml/layout` directories:
- `view/frontend/layout`:
    - `default`

- `view/adminhtml/layout`:
    - `sales_order_view`

### Events

The module dispatches the following events:
- `ppo_order_update_after` event in the `\Rally\Checkout\Model\OrderManager::save` method. Parameters:
    - `ppo_items` is an order items data (`array` type)

- `rally_create_guest_account` event in the `\Rally\Checkout\Model\OrderManager::processOrder` method. Parameters:
    - `order` is an order object (`\Magento\Sales\Model\Order` class)
