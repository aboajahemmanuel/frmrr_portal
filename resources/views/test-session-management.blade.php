@extends('layouts.auth')

@section('title', 'Session Management Test')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Session Management Test Page</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Session Information</h5>
                            <div id="session-info" class="mb-3">
                                <p><strong>Session Lifetime:</strong> {{ config('session.lifetime') }} minutes</p>
                                <p><strong>Current Time:</strong> <span id="current-time"></span></p>
                                <p><strong>Last Activity:</strong> <span id="last-activity">{{ session('last_activity', 'Not set') }}</span></p>
                                <p><strong>Session ID:</strong> {{ session()->getId() }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5>Test Controls</h5>
                            <div class="mb-3">
                                <button id="refresh-session" class="btn btn-primary mb-2">Refresh Session</button>
                                <button id="check-session" class="btn btn-info mb-2">Check Session Status</button>
                                <button id="simulate-activity" class="btn btn-success mb-2">Simulate Activity</button>
                            </div>
                            <div id="test-results" class="alert alert-info">
                                <strong>Test Results:</strong>
                                <div id="results-content">Click buttons above to test session functionality</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Activity Log</h5>
                            <div id="activity-log" class="border p-3" style="height: 200px; overflow-y: auto; background-color: #f8f9fa;">
                                <div class="text-muted">Activity will be logged here...</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.sessioncheck')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const refreshBtn = document.getElementById('refresh-session');
    const checkBtn = document.getElementById('check-session');
    const simulateBtn = document.getElementById('simulate-activity');
    const resultsContent = document.getElementById('results-content');
    const activityLog = document.getElementById('activity-log');
    const currentTimeSpan = document.getElementById('current-time');
    const lastActivitySpan = document.getElementById('last-activity');

    // Update current time every second
    function updateTime() {
        currentTimeSpan.textContent = new Date().toLocaleString();
    }
    setInterval(updateTime, 1000);
    updateTime();

    // Log activity function
    function logActivity(message, type = 'info') {
        const timestamp = new Date().toLocaleTimeString();
        const logEntry = document.createElement('div');
        logEntry.className = `text-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'dark'}`;
        logEntry.innerHTML = `<small>[${timestamp}]</small> ${message}`;
        activityLog.appendChild(logEntry);
        activityLog.scrollTop = activityLog.scrollHeight;
    }

    // Test session refresh
    refreshBtn.addEventListener('click', function() {
        logActivity('Testing session refresh...', 'info');
        
        fetch('{{ route("session.refresh") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultsContent.innerHTML = `
                    <strong>Session Refresh: SUCCESS</strong><br>
                    Message: ${data.message}<br>
                    Timestamp: ${data.timestamp}
                `;
                logActivity('Session refreshed successfully', 'success');
                lastActivitySpan.textContent = data.timestamp;
            } else {
                resultsContent.innerHTML = `<strong>Session Refresh: FAILED</strong><br>Message: ${data.message}`;
                logActivity('Session refresh failed', 'error');
            }
        })
        .catch(error => {
            resultsContent.innerHTML = `<strong>Session Refresh: ERROR</strong><br>Error: ${error.message}`;
            logActivity(`Session refresh error: ${error.message}`, 'error');
        });
    });

    // Test session check
    checkBtn.addEventListener('click', function() {
        logActivity('Checking session status...', 'info');
        
        fetch('{{ route("session.check") }}', {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                resultsContent.innerHTML = `
                    <strong>Session Check: SUCCESS</strong><br>
                    Authenticated: ${data.authenticated ? 'Yes' : 'No'}<br>
                    Timestamp: ${data.timestamp}
                `;
                logActivity('Session is valid and active', 'success');
            } else {
                resultsContent.innerHTML = `<strong>Session Check: FAILED</strong><br>Authenticated: No`;
                logActivity('Session is invalid or expired', 'error');
            }
        })
        .catch(error => {
            resultsContent.innerHTML = `<strong>Session Check: ERROR</strong><br>Error: ${error.message}`;
            logActivity(`Session check error: ${error.message}`, 'error');
        });
    });

    // Simulate activity
    simulateBtn.addEventListener('click', function() {
        logActivity('Simulating user activity...', 'info');
        
        // Trigger the activity tracking by making a simple request
        fetch(window.location.href, {
            method: 'GET',
            credentials: 'same-origin'
        })
        .then(() => {
            logActivity('User activity simulated - session should be refreshed', 'success');
            resultsContent.innerHTML = '<strong>Activity Simulation: SUCCESS</strong><br>Page request made to trigger session activity tracking';
        })
        .catch(error => {
            logActivity(`Activity simulation error: ${error.message}`, 'error');
        });
    });

    // Log initial load
    logActivity('Session management test page loaded', 'info');
});
</script>
@endsection
