<?php
/*
Plugin Name: Hamza Javed Header Selector
Plugin URI: https://techosolution.com/
Description: A custom plugin to display different headers based on user selection.
Version: 1.0
Author: Hamza Javed
Author URI: https://techosolution.com/
License: GPL2
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Enqueue scripts and styles
function hamza_javed_enqueue_scripts() {
    if ( is_front_page() ) { // Load scripts only on the homepage
        wp_enqueue_script( 'hamza-javed-header-selector', plugin_dir_url( __FILE__ ) . 'js/hamza-javed-header-selector.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_style( 'hamza-javed-header-selector', plugin_dir_url( __FILE__ ) . 'css/hamza-javed-header-selector.css' );
    }
}
add_action( 'wp_enqueue_scripts', 'hamza_javed_enqueue_scripts' );


use XTS\Modules\Header_Builder\Frontend;




// Add the popup HTML to the footer
function hamza_javed_add_popup_html() {
  
$options = get_option('header_selection_options');

$above_36_text = isset($options['header_above_36']) ? $options['header_above_36'] : 'Above 36';
$below_36_text = isset($options['header_below_36']) ? $options['header_below_36'] : 'Below 36';
$handicap_text = isset($options['header_handicap']) ? $options['header_handicap'] : 'What is Handicap';

?>
<div id="headerSelectionPopup" style="display:none;">
    <form id="headerSelectionForm">
        <h3>Select an Option:</h3>
        <div class="radio-group">
            <label>
                <input type="radio" name="header_option" value="<?php echo esc_attr($above_36_text); ?>">
                <span class="radio-label"><?php echo esc_html($above_36_text); ?></span>
            </label>
            <label>
                <input type="radio" name="header_option" value="<?php echo esc_attr($below_36_text); ?>">
                <span class="radio-label"><?php echo esc_html($below_36_text); ?></span>
            </label>
            <label>
                <input type="radio" name="header_option" value="<?php echo esc_attr($handicap_text); ?>">
                <span class="radio-label"><?php echo esc_html($handicap_text); ?></span>
            </label>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>
<?php

    }

add_action( 'wp', 'hamza_javed_add_popup_html' );
add_action('wp', 'set_custom_header_based_on_condition', 10);

function set_custom_header_based_on_condition() {
    // Check if the cookie is set
    if (isset($_COOKIE['header_option'])) {
        // Convert the cookie value to lowercase for consistent comparison
        $selected_option = strtolower(sanitize_text_field($_COOKIE['header_option']));
        
        // Define the mapping based on your conditions
        if ($selected_option === 'non golfer') {
            $header_id = 'default_header'; // Non Golfer header
        } elseif ($selected_option === 'below 36') {
            $header_id = 'header_764078'; // Under 36 header
        } elseif ($selected_option === 'above 36') {
            $header_id = 'header_589950'; // Above 36 header
        } else {
            $header_id = 'default_header'; // Fallback to Non Golfer header
        }

        
        update_option('whb_main_header', $header_id);
        
        $options = get_option('xts-woodmart-options');
        $options['default_header'] = $header_id;
        update_option('xts-woodmart-options', $options);
    }
}

// Add settings page to the admin menu
function custom_header_selection_menu() {
    add_options_page(
        'Header Selection Settings',  // Page title
        'Header Selection',           // Menu title
        'manage_options',             // Capability
        'header-selection-settings',  // Menu slug
        'custom_header_selection_page'// Function to display content
    );
}
add_action('admin_menu', 'custom_header_selection_menu');

// Display the settings page content
function custom_header_selection_page() {
    ?>
    <div class="wrap">
        <h1>Header Selection Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('header_selection_settings_group');
            do_settings_sections('header-selection-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
// Register settings and add sections/fields
function custom_header_selection_settings_init() {
    register_setting(
        'header_selection_settings_group', // Option group
        'header_selection_options'         // Option name
    );

    add_settings_section(
        'header_selection_settings_section', // ID
        'Header Selection Options',          // Title
        null,                                // Callback (optional)
        'header-selection-settings'          // Page
    );

    add_settings_field(
        'header_above_36',
        'Text for "Above 36"',
        'custom_header_selection_text_field_callback',
        'header-selection-settings',
        'header_selection_settings_section',
        array(
            'label_for' => 'header_above_36',
            'name'      => 'header_selection_options[header_above_36]',
        )
    );

    add_settings_field(
        'header_below_36',
        'Text for "Below 36"',
        'custom_header_selection_text_field_callback',
        'header-selection-settings',
        'header_selection_settings_section',
        array(
            'label_for' => 'header_below_36',
            'name'      => 'header_selection_options[header_below_36]',
        )
    );

    add_settings_field(
        'header_handicap',
        'Text for "What is Handicap"',
        'custom_header_selection_text_field_callback',
        'header-selection-settings',
        'header_selection_settings_section',
        array(
            'label_for' => 'header_handicap',
            'name'      => 'header_selection_options[header_handicap]',
        )
    );
}
add_action('admin_init', 'custom_header_selection_settings_init');

// Callback function to render the text fields
function custom_header_selection_text_field_callback($args) {
    $options = get_option('header_selection_options');
    $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '';
    ?>
    <input type="text" id="<?php echo esc_attr($args['label_for']); ?>" name="<?php echo esc_attr($args['name']); ?>" value="<?php echo esc_attr($value); ?>" class="regular-text">
    <?php
}
// Add a settings link to the plugin actions
function hamza_javed_plugin_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=header-selection-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'hamza_javed_plugin_settings_link');