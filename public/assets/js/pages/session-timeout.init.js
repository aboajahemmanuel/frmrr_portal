// Simple session timeout implementation
(function($) {
    var sessionTimeout = function(options) {
        console.log('Initializing sessionTimeout with options:', options);
        
        var defaults = {
            keepAliveUrl: "pages-starter.html",
            logoutButton: "Logout",
            logoutUrl: "pages-login.html",
            redirUrl: "pages-lock-screen.html",
            warnAfter: 900000, // 15 minutes
            redirAfter: 1200000, // 20 minutes
            countdownMessage: "Redirecting in {timer} seconds.",
            countdownBar: true
        };
        
        var settings = $.extend(defaults, options);
        console.log('Final settings:', settings);
        
        var countdown = Math.floor((settings.redirAfter - settings.warnAfter) / 1000);
        var countdownDisplay = '<div id="session-timeout-dialog" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">' +
            '<div class="modal-dialog">' +
            '<div class="modal-content">' +
            '<div class="modal-header">' +
            '<h4 class="modal-title">Your session is about to expire!</h4>' +
            '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
            '</div>' +
            '<div class="modal-body">' +
            '<p id="countdown-message">Redirecting in <span id="countdown-display">' + countdown + '</span> seconds.</p>' +
            (settings.countdownBar ? '<div class="progress mb-3"><div class="progress-bar bg-warning" id="countdown-bar" role="progressbar" style="width: 100%;"></div></div>' : '') +
            '</div>' +
            '<div class="modal-footer">' +
            '<button id="session-timeout-keepalive" type="button" class="btn btn-primary" data-bs-dismiss="modal">Stay Connected</button>' +
            '<a href="' + settings.logoutUrl + '" class="btn btn-danger">' + settings.logoutButton + '</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
        
        // Add the dialog to the page
        $('body').append(countdownDisplay);
        console.log('Dialog appended to body');
        
        var keepAlive = function() {
            console.log('Keep alive called');
            // Here you would typically make an AJAX call to keep the session alive
            // For now, we'll just reset the timers
            resetTimers();
        };
        
        var resetTimers = function() {
            console.log('Resetting timers');
            clearTimeout(warningTimer);
            clearTimeout(redirectTimer);
            
            warningTimer = setTimeout(showWarning, settings.warnAfter);
            redirectTimer = setTimeout(redirect, settings.redirAfter);
            console.log('New timers set - warnAfter:', settings.warnAfter, 'redirAfter:', settings.redirAfter);
        };
        
        var showWarning = function() {
            console.log('Showing session timeout warning');
            // Show the warning dialog
            if ($('#session-timeout-dialog').length > 0) {
                console.log('Dialog exists, showing it');
                // Try both Bootstrap 4 and Bootstrap 5 modal methods
                if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                    // Bootstrap 5
                    var modalElement = document.getElementById('session-timeout-dialog');
                    var modal = bootstrap.Modal.getInstance(modalElement);
                    if (!modal) {
                        modal = new bootstrap.Modal(modalElement, {
                            backdrop: 'static',
                            keyboard: false
                        });
                    }
                    modal.show();
                } else if ($.fn.modal) {
                    // Bootstrap 4 or jQuery modal
                    $('#session-timeout-dialog').modal('show');
                } else {
                    // Fallback: show with plain JavaScript
                    console.log('No modal library found, using fallback');
                    $('#session-timeout-dialog').show();
                }
            } else {
                console.log('Dialog does not exist!');
            }
            
            // Start the countdown
            var countdownValue = Math.floor((settings.redirAfter - settings.warnAfter) / 1000);
            $('#countdown-display').text(countdownValue);
            
            if (settings.countdownBar) {
                var startTime = new Date().getTime();
                var endTime = startTime + (settings.redirAfter - settings.warnAfter);
                
                var updateCountdown = function() {
                    var now = new Date().getTime();
                    var remaining = Math.floor((endTime - now) / 1000);
                    
                    if (remaining >= 0) {
                        $('#countdown-display').text(remaining);
                        
                        if (settings.countdownBar) {
                            var percentage = (remaining / countdownValue) * 100;
                            $('#countdown-bar').css('width', percentage + '%');
                        }
                        
                        setTimeout(updateCountdown, 1000);
                    }
                };
                
                updateCountdown();
            }
        };
        
        var redirect = function() {
            console.log('Redirecting due to session timeout to:', settings.redirUrl);
            window.location.href = settings.redirUrl;
        };
        
        // Set up event handlers
        $(document).on('click', '#session-timeout-keepalive', function() {
            console.log('User clicked Stay Connected');
            // Try both Bootstrap 4 and Bootstrap 5 modal methods
            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                // Bootstrap 5
                var modalElement = document.getElementById('session-timeout-dialog');
                var modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
            } else if ($.fn.modal) {
                // Bootstrap 4 or jQuery modal
                $('#session-timeout-dialog').modal('hide');
            } else {
                // Fallback: hide with plain JavaScript
                $('#session-timeout-dialog').hide();
            }
            keepAlive();
        });
        
        // Set up activity detection
        var activityEvents = 'click keypress scroll wheel mousewheel mousemove touchmove';
        $(document).on(activityEvents, function() {
            console.log('User activity detected, resetting timers');
            resetTimers();
        });
        
        // Initialize timers
        console.log('Initializing session timeout timers with warnAfter: ' + settings.warnAfter + 'ms, redirAfter: ' + settings.redirAfter + 'ms');
        var warningTimer = setTimeout(showWarning, settings.warnAfter);
        var redirectTimer = setTimeout(redirect, settings.redirAfter);
        console.log('Timers initialized');
        
        // Return public methods
        return {
            resetTimers: resetTimers
        };
    };
    
    $.sessionTimeout = sessionTimeout;
    
    // Initialize the session timeout when the document is ready
    $(document).ready(function() {
        console.log('Document ready, initializing session timeout');
        
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
        
        // Ensure Bootstrap is loaded before initializing
        var checkBootstrap = function() {
            if (typeof $.fn.modal !== 'undefined' || (typeof bootstrap !== 'undefined' && bootstrap.Modal)) {
                console.log('Bootstrap modal is available, initializing session timeout');
                $.sessionTimeout({
                    keepAliveUrl: "pages-starter.html",
                    logoutButton: "Logout",
                    logoutUrl: "pages-login.html",
                    redirUrl: "pages-lock-screen.html",
                    warnAfter: warnAfter,
                    redirAfter: redirAfter,
                    countdownMessage: "Redirecting in {timer} seconds."
                });
                return true;
            }
            return false;
        };
        
        if (!checkBootstrap()) {
            console.log('Bootstrap modal is not available, waiting for it to load');
            // Wait for Bootstrap to load
            var checkBootstrapInterval = setInterval(function() {
                if (checkBootstrap()) {
                    console.log('Bootstrap modal is now available, initializing session timeout');
                    clearInterval(checkBootstrapInterval);
                }
            }, 100);
            
            // Set a timeout to stop checking after 5 seconds
            setTimeout(function() {
                clearInterval(checkBootstrapInterval);
                console.log('Timed out waiting for Bootstrap modal');
                // Initialize anyway with default values
                $.sessionTimeout({
                    keepAliveUrl: "pages-starter.html",
                    logoutButton: "Logout",
                    logoutUrl: "pages-login.html",
                    redirUrl: "pages-lock-screen.html",
                    warnAfter: warnAfter,
                    redirAfter: redirAfter,
                    countdownMessage: "Redirecting in {timer} seconds."
                });
            }, 5000);
        }
    });
})(jQuery);