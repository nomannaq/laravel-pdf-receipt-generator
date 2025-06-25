<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #fff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .subject {
            font-size: 24px;
            font-weight: bold;
            color: #2d3748;
            margin: 0;
        }
        .content {
            font-size: 16px;
            line-height: 1.8;
        }
        .content p {
            margin-bottom: 16px;
        }
        .content table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .content th,
        .content td {
            border: 1px solid #e2e8f0;
            padding: 12px;
            text-align: left;
        }
        .content th {
            background-color: #f1f5f9;
            font-weight: 600;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 14px;
            color: #6c757d;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1 class="subject">{{ $subject }}</h1>
        </div>
        
        <div class="content">
            {!! nl2br(e($content)) !!}
        </div>
        
        <div class="footer">
            <p>This is a preview of your email template with sample data.</p>
        </div>
    </div>
</body>
</html>
