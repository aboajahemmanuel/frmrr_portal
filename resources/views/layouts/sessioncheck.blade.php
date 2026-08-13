

<style>
#sessionTimeoutModal .modal-content {
    border: none;
    border-radius: 16px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    overflow: hidden;
}
#sessionTimeoutModal .modal-header {
    border-bottom: none;
    padding: 32px 32px 8px;
    text-align: center;
}
#sessionTimeoutModal .modal-title {
    font-family: 'Roboto', sans-serif;
    font-size: 22px;
    font-weight: 700;
    color: #1c2b46;
    margin-top: 15px;
}
#sessionTimeoutModal .modal-body {
    padding: 8px 32px 24px;
    text-align: center;
    color: #526484;
    font-family: 'Roboto', sans-serif;
    font-size: 14px;
    line-height: 1.6;
}
#sessionTimeoutModal .modal-footer {
    border-top: none;
    padding: 0 32px 32px;
    display: flex;
    justify-content: space-between;
    gap: 16px;
}
#sessionTimeoutModal .btn-continue {
    flex: 1;
    background: linear-gradient(135deg, #1a3a8f 0%, #0c2b70 100%);
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(26, 58, 143, 0.2);
}
#sessionTimeoutModal .btn-continue:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(26, 58, 143, 0.3);
}
#sessionTimeoutModal .btn-logout {
    flex: 1;
    background-color: #f5f6fa;
    color: #526484;
    border: 1px solid #dbdfea;
    border-radius: 8px;
    padding: 12px 24px;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.3s ease;
    cursor: pointer;
}
#sessionTimeoutModal .btn-logout:hover {
    background-color: #ffe9e9;
    color: #e85347;
    border-color: #fcc;
}
</style>

<!-- Session Timeout Modal -->
<div class="modal fade" id="sessionTimeoutModal" tabindex="-1" aria-labelledby="sessionTimeoutLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div class="text-center w-100">
            <div style="width: 60px; height: 60px; border-radius: 50%; background-color: #fef8e4; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #ad8e4f; box-shadow: 0 4px 10px rgba(213, 167, 59, 0.15);">
                <em class="icon ni ni-clock-fill" style="font-size: 32px;"></em>
            </div>
            <h5 class="modal-title" id="sessionTimeoutLabel">Session Expiring Soon</h5>
        </div>
      </div>
      <div class="modal-body">
        <p>You have been inactive for a while. To protect your security, you will be logged out automatically in:</p>
        <div style="margin: 20px 0;">
            <span style="font-size: 64px; font-weight: 800; color: #ad8e4f; line-height: 1; font-family: 'Roboto', sans-serif;" id="session-countdown">60</span>
            <div style="font-size: 11px; font-weight: 600; color: #8094ae; text-transform: uppercase; letter-spacing: 1.5px; margin-top: 8px;">Seconds remaining</div>
        </div>
        <p style="font-size: 14px; margin-bottom: 0;">Would you like to extend your session and stay signed in?</p>
      </div>
      <div class="modal-footer">
        <button type="button" id="logoutNow" class="btn-logout" onclick="event.preventDefault(); document.getElementById('logout-form-modal').submit();">Logout</button>
        <button type="button" id="continueSession" class="btn-continue">Continue Session</button>
      </div>
    </div>
  </div>
</div>
    
<!-- Hidden logout form for modal -->
<form id="logout-form-modal" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

 

    {{-- Session Timeout Script --}}
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        let sessionLifetime = {{ \App\Models\SessionSetting::getCurrentTimeout() }} * 60 * 1000; // Database session lifetime
        let warningTime = 1 * 60 * 1000; 
        let lastActivityTime = Date.now();
        let minTimeBetweenRefreshes = 30 * 1000; // 30 seconds
        let warningTimer, logoutTimer, countdownInterval;

        function hideModal() {
            try {
                if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
                    window.jQuery('#sessionTimeoutModal').modal('hide');
                } else if (window.bootstrap && window.bootstrap.Modal && typeof window.bootstrap.Modal.getInstance === 'function') {
                    const modalEl = document.getElementById('sessionTimeoutModal');
                    const modal = window.bootstrap.Modal.getInstance(modalEl) || new window.bootstrap.Modal(modalEl);
                    if (modal) modal.hide();
                } else {
                    const modalEl = document.getElementById('sessionTimeoutModal');
                    modalEl.classList.remove('show');
                    modalEl.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }
            } catch (e) {
                console.error("Error hiding modal:", e);
            }
        }

        function showModal() {
            try {
                if (window.jQuery && typeof window.jQuery.fn.modal === 'function') {
                    window.jQuery('#sessionTimeoutModal').modal('show');
                } else if (window.bootstrap && window.bootstrap.Modal) {
                    const modalEl = document.getElementById('sessionTimeoutModal');
                    const modal = (typeof window.bootstrap.Modal.getInstance === 'function' ? window.bootstrap.Modal.getInstance(modalEl) : null) || new window.bootstrap.Modal(modalEl);
                    if (modal) modal.show();
                } else {
                    const modalEl = document.getElementById('sessionTimeoutModal');
                    modalEl.classList.add('show');
                    modalEl.style.display = 'block';
                    document.body.classList.add('modal-open');
                    if (!document.querySelector('.modal-backdrop')) {
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    }
                }
            } catch (e) {
                console.error("Error showing modal:", e);
            }
        }

        function resetTimers() {
            clearTimeout(warningTimer);
            clearTimeout(logoutTimer);
            clearInterval(countdownInterval);
            hideModal();
            
            let now = Date.now();
            // Send heartbeat to server to refresh session, throttled to 30 seconds
            if (now - lastActivityTime > minTimeBetweenRefreshes) {
                lastActivityTime = now;
                fetch('{{ route("session.refresh") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    credentials: 'same-origin'
                }).then(response => {
                    if (response.ok) {
                        startTimers();
                    } else {
                        // If session refresh fails, redirect to login
                        window.location.href = '{{ route("login") }}';
                    }
                }).catch(error => {
                    console.error('Session refresh failed:', error);
                    // On error, still start timers to avoid breaking the UI
                    startTimers();
                });
            } else {
                startTimers();
            }
        }

        function startTimers() {
            warningTimer = setTimeout(showWarning, sessionLifetime - warningTime);
            logoutTimer = setTimeout(autoLogout, sessionLifetime);
        }

        function showWarning() {
            let countdown = warningTime / 1000;
            showModal();

            document.getElementById('session-countdown').textContent = countdown;

            countdownInterval = setInterval(() => {
                countdown--;
                document.getElementById('session-countdown').textContent = countdown;
                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    autoLogout();
                }
            }, 1000);
        }

        function autoLogout() {
            document.getElementById('logout-form-modal').submit();
        }

        document.getElementById('continueSession').addEventListener('click', function () {
            // Refresh the session on server and reset timers
            fetch('{{ route("session.refresh") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            }).then(response => {
                if (response.ok) {
                    // Hide modal and reset timers
                    hideModal();
                    clearTimeout(warningTimer);
                    clearTimeout(logoutTimer);
                    clearInterval(countdownInterval);
                    lastActivityTime = Date.now();
                    startTimers();
                } else {
                    // If session refresh fails, redirect to login
                    window.location.href = '{{ route("login") }}';
                }
            }).catch(error => {
                console.error('Session refresh failed:', error);
                window.location.href = '{{ route("login") }}';
            });
        });

        ['keydown', 'click', 'mousemove', 'scroll'].forEach(e => document.addEventListener(e, resetTimers));

        startTimers();
    });
    </script>

