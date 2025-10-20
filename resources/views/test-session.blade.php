<!DOCTYPE html>
<html>
<head>
    <title>Session Timeout Test</title>
    <meta name="session-timeout-minutes" content="{{ \App\Models\SessionSetting::getCurrentTimeout() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h1>Session Timeout Test</h1>
        <p>Current timeout setting: <span id="timeout-value">{{ \App\Models\SessionSetting::getCurrentTimeout() }}</span> minutes</p>
        <p>Warning will appear after: <span id="warn-after"></span> seconds</p>
        <p>Redirect will happen after: <span id="redirect-after"></span> seconds</p>
        <button id="reset-timers" class="btn btn-primary">Reset Timers</button>
        <button id="show-warning" class="btn btn-warning">Show Warning</button>
        
        <div id="session-timeout-dialog" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Your session is about to expire!</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="countdown-message">Redirecting in <span id="countdown-display">0</span> seconds.</p>
                        <div class="progress mb-3">
                            <div class="progress-bar bg-warning" id="countdown-bar" role="progressbar" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="session-timeout-keepalive" type="button" class="btn btn-primary" data-bs-dismiss="modal">Stay Connected</button>
                        <a href="/login" class="btn btn-danger">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            console.log('Document ready');
            
            // Get session timeout from meta tag
            var timeoutMinutes = 15; // default
            var metaTag = document.head.querySelector('meta[name="session-timeout-minutes"]');
            console.log('Meta tag found:', metaTag);
            
            if (metaTag && metaTag.content) {
                timeoutMinutes = parseInt(metaTag.content) || 15;
                console.log('Using timeout from meta tag:', timeoutMinutes);
            }
            
            var warnAfter = (timeoutMinutes * 60 * 1000) * 0.8; // Warn at 80% of timeout
            var redirAfter = timeoutMinutes * 60 * 1000; // Full timeout
            
            $('#warn-after').text(warnAfter / 1000);
            $('#redirect-after').text(redirAfter / 1000);
            
            console.log('Timeout minutes:', timeoutMinutes);
            console.log('Warn after (ms):', warnAfter);
            console.log('Redirect after (ms):', redirAfter);
            
            // Button to manually show warning
            $('#show-warning').click(function() {
                console.log('Manually showing warning');
                showWarning();
            });
            
            // Button to reset timers
            $('#reset-timers').click(function() {
                console.log('Resetting timers');
                resetTimers();
            });
            
            var warningTimer, redirectTimer;
            
            var resetTimers = function() {
                console.log('Resetting timers');
                clearTimeout(warningTimer);
                clearTimeout(redirectTimer);
                
                warningTimer = setTimeout(showWarning, warnAfter);
                redirectTimer = setTimeout(redirect, redirAfter);
                console.log('New timers set');
            };
            
            var showWarning = function() {
                console.log('Showing session timeout warning');
                // Show the warning dialog
                if ($('#session-timeout-dialog').length > 0) {
                    console.log('Dialog exists, showing it');
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
            
            var redirect = function() {
                console.log('Redirecting due to session timeout');
                alert('Session timeout - would redirect now');
            };
            
            // Set up event handlers
            $(document).on('click', '#session-timeout-keepalive', function() {
                console.log('User clicked Stay Connected');
                var modalElement = document.getElementById('session-timeout-dialog');
                var modal = bootstrap.Modal.getInstance(modalElement);
                if (modal) {
                    modal.hide();
                }
                resetTimers();
            });
            
            // Initialize timers
            resetTimers();
        });
    </script>
</body>
</html>