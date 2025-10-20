// Fixed session timeout implementation
(function($) {
    console.log('Session timeout fix script loaded');
    
    // Initialize the session timeout when the document is ready
    $(document).ready(function() {
        console.log('Document ready, initializing session timeout fix');
        
        // Get session timeout from meta tag or use default values
        var timeoutMinutes = 15; // default
        var metaTag = document.head.querySelector('meta[name="session-timeout-minutes"]');
        console.log('Meta tag found:', metaTag);
        
        if (metaTag && metaTag.content) {
            timeoutMinutes = parseInt(metaTag.content) || 15;
            console.log('Using timeout from meta tag:', timeoutMinutes);
        } else {
            console.log('Using default timeout:', timeoutMinutes);
        }
        
        var warnAfter = (timeoutMinutes * 60 * 1000) * 0.8; // Warn at 80% of timeout
        var redirAfter = timeoutMinutes * 60 * 1000; // Full timeout
        
        console.log('Calculated values - timeoutMinutes:', timeoutMinutes, 'warnAfter:', warnAfter, 'redirAfter:', redirAfter);
        
        // Create the dialog HTML
        var countdown = Math.floor((redirAfter - warnAfter) / 1000);
        var countdownDisplay = '<div id="session-timeout-dialog" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="sessionTimeoutModalLabel" aria-hidden="true" style="display: none;">' +
            '<div class="modal-dialog">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h4 class="modal-title">Your session is about to expire!</h4>' +
            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
            '</div>' +
            '<div class="modal-body">' +
            '<p id="countdown-message">Redirecting in <span id="countdown-display">' + countdown + '</span> seconds.</p>' +
            '<div class="progress mb-3"><div class="progress-bar bg-warning" id="countdown-bar" role="progressbar" style="width: 100%;"></div></div>' +
            '</div>' +
            '<div class="modal-footer">' +
            '<button id="session-timeout-keepalive" type="button" class="btn btn-primary" data-bs-dismiss="modal">Stay Connected</button>' +
            '<a href="/login" class="btn btn-danger">Logout</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
        
        // Add the dialog to the page
        $('body').append(countdownDisplay);
        console.log('Dialog appended to body');
        
        var warningTimer, redirectTimer;
        
        // Function to reset timers
        var resetTimers = function() {
            console.log('Resetting timers');
            clearTimeout(warningTimer);
            clearTimeout(redirectTimer);
            
            warningTimer = setTimeout(showWarning, warnAfter);
            redirectTimer = setTimeout(redirect, redirAfter);
            console.log('New timers set - warnAfter:', warnAfter, 'redirAfter:', redirAfter);
        };
        
        // Function to show warning
        var showWarning = function() {
            console.log('Showing session timeout warning');
            
            // Show the warning dialog
            if ($('#session-timeout-dialog').length > 0) {
                console.log('Dialog exists, showing it');
                
                // Try multiple methods to show the modal
                try {
                    // Method 1: Bootstrap 5
                    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                        var modalElement = document.getElementById('session-timeout-dialog');
                        var modal = bootstrap.Modal.getInstance(modalElement);
                        if (!modal) {
                            modal = new bootstrap.Modal(modalElement, {
                                backdrop: 'static',
                                keyboard: false
                            });
                        }
                        modal.show();
                        console.log('Bootstrap 5 modal shown');
                    } 
                    // Method 2: Bootstrap 4/jQuery
                    else if ($.fn.modal) {
                        $('#session-timeout-dialog').modal('show');
                        console.log('Bootstrap 4/jQuery modal shown');
                    } 
                    // Method 3: Fallback
                    else {
                        $('#session-timeout-dialog').show();
                        console.log('Fallback modal shown');
                    }
                } catch (e) {
                    console.error('Error showing modal:', e);
                    // Final fallback
                    $('#session-timeout-dialog').show();
                }
                
                // Start the countdown
                var countdownValue = Math.floor((redirAfter - warnAfter) / 1000);
                $('#countdown-display').text(countdownValue);
                
                var startTime = new Date().getTime();
                var endTime = startTime + (redirAfter - warnAfter);
                
                var updateCountdown = function() {
                    var now = new Date().getTime();
                    var remaining = Math.floor((endTime - now) / 1000);
                    
                    if (remaining >= 0) {
                        $('#countdown-display').text(remaining);
                        var percentage = (remaining / countdownValue) * 100;
                        $('#countdown-bar').css('width', percentage + '%');
                        setTimeout(updateCountdown, 1000);
                    }
                };
                
                updateCountdown();
            } else {
                console.log('Dialog does not exist!');
            }
        };
        
        // Function to redirect
        var redirect = function() {
            console.log('Redirecting due to session timeout');
            window.location.href = "/login";
        };
        
        // Set up event handlers
        $(document).on('click', '#session-timeout-keepalive', function() {
            console.log('User clicked Stay Connected');
            
            // Hide the modal using multiple methods
            try {
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    var modalElement = document.getElementById('session-timeout-dialog');
                    var modal = bootstrap.Modal.getInstance(modalElement);
                    if (modal) {
                        modal.hide();
                    }
                } else if ($.fn.modal) {
                    $('#session-timeout-dialog').modal('hide');
                } else {
                    $('#session-timeout-dialog').hide();
                }
            } catch (e) {
                console.error('Error hiding modal:', e);
                $('#session-timeout-dialog').hide();
            }
            
            resetTimers();
        });
        
        // Set up activity detection
        var activityEvents = 'click keypress scroll wheel mousewheel mousemove touchmove';
        $(document).on(activityEvents, function() {
            console.log('User activity detected, resetting timers');
            resetTimers();
        });
        
        // Initialize timers
        console.log('Initializing session timeout timers with warnAfter: ' + warnAfter + 'ms, redirAfter: ' + redirAfter + 'ms');
        resetTimers();
        console.log('Timers initialized');
    });
})(jQuery);