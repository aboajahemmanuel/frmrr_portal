<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal Disclaimer - Financial Markets Regulations & Rules Repository Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .disclaimer-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 200px;
            height: auto;
            margin-bottom: 10px;
        }
        .disclaimer-header {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
        }
        .disclaimer-content {
            line-height: 1.8;
            color: #34495e;
            font-size: 1.1rem;
        }
        .disclaimer-content p {
            margin-bottom: 20px;
            text-align: justify;
        }
        .disclaimer-content ul {
            padding-left: 0;
            list-style: none;
        }
        .disclaimer-content li {
            margin-bottom: 15px;
            padding-left: 25px;
            position: relative;
        }
        .disclaimer-content li:before {
            content: "•";
            color: #3498db;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        .disclaimer-actions {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e8f4fd;
        }
        .btn-disclaimer {
            padding: 15px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            margin: 0 15px;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }
        .btn-accept {
            background: linear-gradient(45deg, #1d326d, #0f1a3a);
            color: white;
            box-shadow: 0 4px 15px rgba(29, 50, 109, 0.4);
        }
        .btn-accept:hover {
            background: linear-gradient(45deg, #0f1a3a, #081229);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(29, 50, 109, 0.6);
            color: white;
        }
        .btn-logout {
            background: linear-gradient(45deg, #95a5a6, #7f8c8d);
            color: white;
            box-shadow: 0 4px 15px rgba(149, 165, 166, 0.4);
        }
        .btn-logout:hover {
            background: linear-gradient(45deg, #7f8c8d, #6c7b7d);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(149, 165, 166, 0.6);
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="disclaimer-container">
        <div class="logo-container">
            <img src="{{ asset('public/users/assets/FMDQ-logo.png') }}" alt="FMDQ Exchange Logo" class="logo">
        </div>
        <h1 class="disclaimer-header">Legal Disclaimer</h1>
        
        <div class="">
            <p>The content provided on this Portal is for general informational purposes only and does not constitute legal, financial, or professional advice</p>
            
            <p>While FMDQ Securities Exchange Limited ("<strong>FMDQ Exchange</strong>") endeavours to ensure the accuracy, completeness, and timeliness of the information presented, FMDQ Exchange</p>
            <ul style="list-style-type: square;">
                <li>does not warrant or guarantee accuracy, completeness, or reliability of the information</li>
                <li>shall not be held liable for any errors, omissions, or reliance placed on the information/materials available on this Portal</li>
                <li>reserves the right to modify, update, or remove any content on this Portal at its discretion and without prior notice</li>
                <li>disclaims all liability for any losses or damages, whether direct, indirect, or consequential, arising from the use of, or inability to use, the Portal or its content</li>
            </ul>
            
            <p>By accessing and using this Portal, you agree to these terms and assume full responsibility for your use of the information provided herein</p>
            
            <p>By clicking "Accept" below, you acknowledge that you have read, understood, and agree to be bound by the above terms and conditions</p>
        </div>
        
        <div class="disclaimer-actions">
            <form method="POST" action="{{ route('disclaimer.accept') }}" id="disclaimerForm" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-disclaimer btn-accept">Accept</button>
            </form>
            
            <a href="{{ route('logout') }}" class="btn btn-disclaimer btn-logout" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                Logout
            </a>
        </div>
        
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>