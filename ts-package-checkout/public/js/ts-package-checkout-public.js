(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
document.addEventListener('DOMContentLoaded', function() {
    // Select the target span where you want to append the new section
    var subscriptionPriceSpan = document.querySelector('.subscription-price');
    
    // Calculate the first renewal date (e.g., 3 days from today)
    var currentDate = new Date();
    currentDate.setDate(currentDate.getDate() + 3); // Adds 3 days

    // Format the date to a readable format (e.g., September 3, 2024)
    var options = { year: 'numeric', month: 'long', day: 'numeric' };
    var renewalDate = currentDate.toLocaleDateString('en-US', options);

    // Create the new section element
    var newSection = document.createElement('div');
    newSection.innerHTML = `
        <div class="first-payment-date">
            <small>First renewal: ${renewalDate}</small>
        </div>
    `;
    
    // Append the new section after the subscription price span
    if (subscriptionPriceSpan) {
        subscriptionPriceSpan.parentNode.insertBefore(newSection, subscriptionPriceSpan.nextSibling);
    }
});

