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

        wp_enqueue_style( 'hamza-javed-header-selector', plugin_dir_url( __FILE__ ) . 'css/hamza-javed-header-selector.css' );
    }

add_action( 'wp_enqueue_scripts', 'hamza_javed_enqueue_scripts' );


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
     // Dropdown for cookie duration
    add_settings_field(
        'cookie_duration',
        'Cookie Duration',
        'custom_header_selection_cookie_duration_callback',
        'header-selection-settings',
        'header_selection_settings_section',
        array(
            'label_for' => 'cookie_duration',
            'name'      => 'header_selection_options[cookie_duration]',
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
// Callback function to render the cookie duration dropdown
function custom_header_selection_cookie_duration_callback($args) {
    $options = get_option('header_selection_options');
    $value = isset($options[$args['label_for']]) ? $options[$args['label_for']] : '86400'; // Default to 1 day (86400 seconds)
    ?>
    <select id="<?php echo esc_attr($args['label_for']); ?>" name="<?php echo esc_attr($args['name']); ?>">
        <option value="3600" <?php selected($value, '3600'); ?>>1 Hour</option>
        <option value="10800" <?php selected($value, '10800'); ?>>3 Hours</option>
        <option value="86400" <?php selected($value, '86400'); ?>>1 Day</option>
        <option value="604800" <?php selected($value, '604800'); ?>>1 Week</option>
        <option value="1209600" <?php selected($value, '1209600'); ?>>2 Weeks</option>
        <option value="2592000" <?php selected($value, '2592000'); ?>>1 Month</option>
    </select>
    <?php
}
// Add a settings link to the plugin actions
function hamza_javed_plugin_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=header-selection-settings">Settings</a>';
    array_unshift($links, $settings_link);
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'hamza_javed_plugin_settings_link');

use XTS\Modules\Header_Builder\Frontend;
// Add the popup HTML to the footer
function hamza_javed_add_popup_html() {
  
$options = get_option('header_selection_options');

$above_36_text = isset($options['header_above_36']) ? $options['header_above_36'] : 'Above 36';
$below_36_text = isset($options['header_below_36']) ? $options['header_below_36'] : 'Below 36';
$handicap_text = isset($options['header_handicap']) ? $options['header_handicap'] : 'What is Handicap';

?>
<div id="headerSelectionPopup">
    <button type="button" id="closePopupButton" style="position: absolute;top: 10px;right: 10px;border: none;font-size: 20px;cursor: pointer;background: black;">&times;</button>

    <form id="headerSelectionForm">
        <h3>Kies een Golf Niveau </h3>
        <div class="radio-group">

             <label>
                <input type="radio" name="header_option" value="header_764078">
                <input type="hidden" name="homepage_id" value="16755">
                <span class="radio-label"><?php echo esc_html($below_36_text); ?></span>
            </label>
             <label>
                <input type="radio" name="header_option" value="header_589950">
                <input type="hidden" name="homepage_id" value="16825">
                <span class="radio-label"><?php echo esc_html($above_36_text); ?></span>
            </label>
            <label>
                <input type="radio" name="header_option" value="default_header">
                <input type="hidden" name="homepage_id" value="16936">

                <span class="radio-label"><?php echo esc_html($handicap_text); ?></span>
            </label>
            
            
        </div>
        <button type="submit">Doorgaan</button>
        

    </form>
    <hr style="    border: 3px solid black !important;
    height: 5px !important;
    width: 100% !important;
    background-color: black !important;
    margin-top: 14px !important;
    margin-bottom: 0 !important;
    opacity: 1 !important;
    width: 100% !important;
    max-width: 100% !important;">



     <a href="https://thegolflab.nl/zakelijke-optie/" ><button class="zakelijkebtn">Zakelijke Mogelijkheden</button></a>
</div>
 <script>
        console.log('Popup HTML is rendered');
    </script>
<?php
    }
add_shortcode('header_selection_form', 'hamza_javed_add_popup_html');
// add_action('wp', 'set_custom_header_based_on_condition', 10);

function set_custom_header_and_homepage_based_on_condition() {
    if (isset($_COOKIE['header_option']) && isset($_COOKIE['homepage_id'])) {
        // Sanitize and get the selected header option from the cookie
        $selected_option = strtolower(sanitize_text_field($_COOKIE['header_option']));
        $homepage_id = intval($_COOKIE['homepage_id']); // Ensure the homepage ID is an integer

        // Define the mapping based on your conditions
        if ($selected_option === 'default_header') {
            $header_id = 'default_header'; // Non Golfer header
        } elseif ($selected_option === 'header_764078') {
            $header_id = 'header_764078'; // Under 36 header
        } elseif ($selected_option === 'header_589950') {
            $header_id = 'header_589950'; // Above 36 header
        } else {
            $header_id = 'default_header'; // Fallback to Non Golfer header
        }

        // Update the header option based on the cookie value
        update_option('whb_main_header', $header_id);

        // Update theme options if needed
        $options = get_option('xts-woodmart-options');
        $options['default_header'] = $header_id;
        update_option('xts-woodmart-options', $options);

        // Update the homepage to the selected page
        if (get_option('page_on_front') != $homepage_id) {
            update_option('page_on_front', $homepage_id);
            update_option('show_on_front', 'page');
        }
    }
}
add_action('init', 'set_custom_header_and_homepage_based_on_condition');



function enqueue_dynamic_header_selector_script() {
    ?>
<script type="text/javascript">

        jQuery(document).ready(function($) {
            console.log("DOM fully loaded and parsed");

            // Function to get a cookie value
            function getCookie(name) {
                var nameEQ = name + "=";
                var ca = document.cookie.split(';');
                for (var i = 0; i < ca.length; i++) {
                    var c = ca[i].trim();
                    if (c.indexOf(nameEQ) == 0) {
                        return c.substring(nameEQ.length, c.length);
                    }
                }
                return null;
            }

            // Function to delete a cookie
            function deleteCookie(name) {
                document.cookie = name + '=; Max-Age=-99999999; path=/';
            }

            // Check if the cookies are set
            var headerOptionCookie = getCookie("header_option");
            var homepageIdCookie = getCookie("homepage_id");

            // Get the form popup element
            var formPopup = $("#headerSelectionPopup");

            // Show the popup if cookies are not set
            if (!headerOptionCookie || !homepageIdCookie) {
                $('#closePopupButton').hide();
                if (formPopup.length) {
                    formPopup.show();
                }
            }

            // Add click event listener to the trigger element
            var triggerElement = $("#trigger-header-form .elementor-button");
            if (triggerElement.length) {
                triggerElement.on("click touchend", function(event) {
                    event.preventDefault(); // Prevent default behavior (e.g., following a link)
                    if (formPopup.length) {
                        formPopup.show(); // Show the popup form
                        console.log("Form popup triggered");
                    } else {
                        console.error("Form popup element not found");
                    }
                });
            }

            // Handle form submission
            var headerSelectionForm = $("#headerSelectionForm");
            if (headerSelectionForm.length) {
                headerSelectionForm.on("submit", function(event) {
                    event.preventDefault();
                    console.log("Form submitted");

                    var selectedOption = $('input[name="header_option"]:checked');
                    if (selectedOption.length) {
                        var selectedHomepageId = selectedOption.next().val();

                        // Delete old cookies
                        deleteCookie("header_option");
                        deleteCookie("homepage_id");

                        // Get cookie duration from backend (placeholder value for this example)
                        var cookieDuration = 86400; // 1 Day duration in seconds

                        // Set new cookies
                        setCookie("header_option", selectedOption.val(), cookieDuration);
                        setCookie("homepage_id", selectedHomepageId, cookieDuration);

                        // Redirect to homepage with timestamp
                        const currentTime = new Date().getTime(); // Current timestamp
                        const newURL = "/?c=" + currentTime;
                        window.location.href = newURL;
                        console.log("Redirecting to homepage with current timestamp...");
                    } else {
                        console.error("No header option selected");
                    }
                });
            }

            // Function to set a cookie
            function setCookie(name, value, seconds) {
                var expires = "";
                if (seconds) {
                    var date = new Date();
                    date.setTime(date.getTime() + (seconds * 1000));
                    expires = "; expires=" + date.toUTCString();
                }
                document.cookie = name + "=" + (value || "") + expires + "; path=/";
                console.log("Cookie set:", name, "=", value, "Expires in:", seconds, "seconds");
            }

            // Close the popup when the close button is clicked
            $("#closePopupButton").on("click", function() {
                formPopup.hide();
                console.log("Popup closed by user");
            });
        });
</script>


    <?php
}
add_action('wp_footer', 'hamza_javed_add_popup_html', 10); // Priority 10
add_action('wp_footer', 'enqueue_dynamic_header_selector_script', 20); // Priority 20

// Add form handling logic
function handle_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['header_option'])) {
        // Check if form is submitted and header option is selected
        $selected_option = sanitize_text_field($_POST['header_option']); // Sanitize input
        $homepage_id = isset($_POST['homepage_id']) ? intval($_POST['homepage_id']) : null;

        // Get the current timestamp
        $currentTime = time();

        // Redirect to the homepage or any other page with the timestamp
        wp_redirect(home_url('/?c=' . $currentTime));
        exit; // Ensure no further processing
    }
}
add_action('template_redirect', 'handle_form_submission');

