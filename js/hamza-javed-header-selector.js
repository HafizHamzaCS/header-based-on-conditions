document.addEventListener("DOMContentLoaded", function() {
    // Display the popup only if the cookie is not set
    if (!getCookie("header_option")) {
        document.getElementById("headerSelectionPopup").style.display = "block";
    }
    document.getElementById("headerSelectionForm").addEventListener("submit", function(event) {
        event.preventDefault();
        var selectedOption = document.querySelector('input[name="header_option"]:checked').value;
        setCookie("header_option", selectedOption, 1); // Store the selection in a cookie for 1 day
        location.reload(); // Reload the page to apply the header selection
    });
});

// Function to set a cookie
function setCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

// Function to get a cookie by name
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}


document.addEventListener("DOMContentLoaded", function() {
    if (!getCookie("header_option")) {
        document.getElementById("headerSelectionPopup").style.display = "block";
    } else {
        console.log("Current header_option cookie value: " + getCookie("header_option"));
    }

    document.getElementById("headerSelectionForm").addEventListener("submit", function(event) {
        event.preventDefault();
        var selectedOption = document.querySelector('input[name="header_option"]:checked').value;
        setCookie("header_option", selectedOption, 1);
        console.log("Setting header_option cookie to: " + selectedOption);
        location.reload();
    });
});
