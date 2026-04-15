<!DOCTYPE html>
<html>
<head>
    <title>Subscription Renewal Alert</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #2c3e50;">Subscription Renewal Notice</h2>
        <p>Dear {{ $subscription->user->name }},</p>
        
        <p>This is a polite reminder that your current subscription (<strong>{{ $plan->name }}</strong>) will expire in <strong>{{ $plan->notification_days }} days</strong>, on <strong>{{ \Carbon\Carbon::parse($subscription->end_date)->format('M. d, Y') }}</strong>.</p>
        
        <p>To ensure uninterrupted access to your benefits, please renew your plan as soon as possible.</p>
        
        <p>
            <a href="{{ config('app.url') }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 4px;">Renew Now</a>
        </p>

        <p>If you have any questions or require assistance, please do not hesitate to contact our support team.</p>
        
        <p>Thank you,<br>
        The Support Team</p>
    </div>
</body>
</html>
