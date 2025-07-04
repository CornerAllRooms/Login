
    // Show the loading screen (#status) and hide the page content (#preloader) initially
    document.getElementById('status').style.display = 'flex'; // Show loading screen
    document.getElementById('preloader').style.display = 'none'; // Hide page content

    // After 8 seconds, hide the loading screen and show the page content
    setTimeout(function () {
        document.getElementById('status').style.display = 'none'; // Hide loading screen
        document.getElementById('preloader').style.display = 'block'; // Show page content
    }, 8000); // 8000 milliseconds = 8 seconds
