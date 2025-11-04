# Session Management Verification Guide

## Overview
This guide helps you verify that the session timeout issue has been resolved and sessions now properly extend when users are active.

## Testing Steps

### 1. Basic Functionality Test
1. **Login** to your application
2. **Navigate** to `/test-session-management` (requires authentication)
3. **Observe** the session information displayed:
   - Session lifetime (should match your database setting)
   - Current session ID
   - Last activity timestamp

### 2. Session Refresh Test
1. On the test page, click **"Refresh Session"** button
2. **Verify** you see "Session Refresh: SUCCESS" message
3. **Check** that the last activity timestamp updates
4. **Confirm** no logout occurs

### 3. Session Check Test
1. Click **"Check Session Status"** button
2. **Verify** you see "Session Check: SUCCESS" with "Authenticated: Yes"
3. **Confirm** the session is recognized as valid

### 4. Activity Simulation Test
1. Click **"Simulate Activity"** button
2. **Verify** the activity is logged successfully
3. **Check** that this counts as user activity for session extension

### 5. Real-World Activity Test
1. **Set** session timeout to a short duration (2-5 minutes) via admin panel
2. **Perform** normal activities on the site:
   - Navigate between pages
   - Click buttons
   - Type in forms
   - Scroll pages
3. **Observe** that the session timeout warning appears before expiration
4. **Continue** using the site actively
5. **Verify** that sessions don't expire while you're active

### 6. Session Timeout Warning Test
1. **Stop** all activity and wait
2. **Observe** the session timeout warning modal appears 1 minute before expiration
3. **Click** "Continue Session" button
4. **Verify** the session extends and modal closes
5. **Confirm** you remain logged in

### 7. Automatic Logout Test
1. **Stop** all activity and wait
2. **Let** the countdown reach zero without clicking "Continue Session"
3. **Verify** automatic logout occurs
4. **Confirm** you're redirected to login page

## Expected Behavior

### ✅ What Should Work Now:
- Sessions extend automatically when users are active
- Session timeout warnings appear before expiration
- "Continue Session" button properly refreshes server session
- Users can work continuously without interruption
- Session activity is tracked on every request
- Session IDs regenerate periodically for security

### ❌ Previous Issues (Now Fixed):
- Sessions expiring despite user activity
- Client-side timers not syncing with server
- "Continue Session" only reloading page without extending session
- No server-side session refresh mechanism

## Monitoring Session Activity

### Check Session Files (if using file driver):
```bash
# Navigate to session storage directory
cd storage/framework/sessions

# List recent session files
ls -la

# Check session file content (replace with actual session ID)
cat sess_[session_id]
```

### Check Database Sessions (if using database driver):
```sql
-- View active sessions
SELECT * FROM sessions WHERE last_activity > UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 30 MINUTE));

-- View session activity for specific user
SELECT * FROM sessions WHERE user_id = [user_id];
```

### Monitor Application Logs:
```bash
# Check Laravel logs for session-related entries
tail -f storage/logs/laravel.log | grep -i session
```

## Configuration Verification

### 1. Check Session Settings:
- **Database**: Verify `session_settings` table has correct timeout values
- **Config**: Ensure `config/session.php` uses database-driven timeout
- **Environment**: Check `.env` file for session configuration

### 2. Verify Middleware Registration:
- **File**: `app/Http/Kernel.php`
- **Location**: `SessionActivityMiddleware` should be in web middleware group
- **Position**: After `StartSession` middleware

### 3. Check Routes:
- **Refresh**: `POST /session/refresh` should be accessible
- **Check**: `GET /session/check` should be accessible
- **Test Page**: `/test-session-management` should require authentication

## Troubleshooting

### If Sessions Still Expire:
1. Check browser console for JavaScript errors
2. Verify CSRF token is included in AJAX requests
3. Ensure session routes are properly registered
4. Check middleware is loaded in correct order
5. Verify database session settings are being applied

### If Refresh Fails:
1. Check network tab for failed AJAX requests
2. Verify CSRF token is valid
3. Ensure user is authenticated
4. Check server logs for errors

### If Activity Not Tracked:
1. Verify `SessionActivityMiddleware` is registered
2. Check middleware is in web group
3. Ensure middleware runs after session start
4. Verify `last_activity` session key is being set

## Performance Considerations

- Session regeneration occurs every 5 minutes for security
- Activity tracking adds minimal overhead per request
- Session garbage collection runs with 2% probability
- AJAX session refresh is lightweight and fast

## Security Features

- Session ID regeneration prevents session fixation
- Activity tracking helps detect inactive sessions
- Proper session invalidation on logout
- CSRF protection on all session endpoints
