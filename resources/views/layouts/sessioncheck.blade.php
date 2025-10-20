

    <!-- Session Timeout Modal -->
    <div class="modal fade" id="sessionTimeoutModal" tabindex="-1" aria-labelledby="sessionTimeoutLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-warning">
            <h5 class="modal-title" id="sessionTimeoutLabel">Session Expiring Soon</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            Your session will expire in <span id="session-countdown">60</span> seconds.
            Would you like to stay signed in?
          </div>
          <div class="modal-footer">
            <button type="button" id="logoutNow" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form-modal').submit();">Logout</button>
            <button type="button" id="continueSession" class="btn btn-primary">Continue Session</button>
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
        let sessionLifetime = {{ config('session.lifetime') }} * 60 * 1000; // Laravel session lifetime
        let warningTime = 1 * 60 * 1000; 
        let warningTimer, logoutTimer, countdownInterval;

        function resetTimers() {
            clearTimeout(warningTimer);
            clearTimeout(logoutTimer);
            clearInterval(countdownInterval);
            const modal = bootstrap.Modal.getInstance(document.getElementById('sessionTimeoutModal'));
            if (modal) modal.hide();
            startTimers();
        }

        function startTimers() {
            warningTimer = setTimeout(showWarning, sessionLifetime - warningTime);
            logoutTimer = setTimeout(autoLogout, sessionLifetime);
        }

        function showWarning() {
            let countdown = warningTime / 1000;
            const modalElement = document.getElementById('sessionTimeoutModal');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();

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
            window.location.href = '{{ route("logout") }}';
        }

        document.getElementById('continueSession').addEventListener('click', function () {
            location.reload();
        });

        ['keydown', 'click'].forEach(e => document.addEventListener(e, resetTimers));

        startTimers();
    });
    </script>

