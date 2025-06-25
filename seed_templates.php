<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Template;

// Clear existing templates
Template::query()->delete();

// Create SMS templates
Template::create([
    'name' => 'Payment Reminder SMS',
    'type' => 'sms',
    'content' => 'Dear {{debtor_name}}, this is a reminder about your outstanding balance of €{{total_for_due_date}}. Due date: {{due_date}}. Please contact us at {{creditor_contact_person_phone}} for payment arrangements. - {{creditor_name}}',
    'description' => 'Standard SMS reminder for outstanding payments',
    'is_default' => true
]);

Template::create([
    'name' => 'Final Notice SMS',
    'type' => 'sms',
    'content' => 'FINAL NOTICE: {{debtor_name}}, your payment of €{{total_for_due_date}} is overdue. Contact {{creditor_contact_person_name}} immediately at {{creditor_contact_person_phone}} to avoid further action. - {{creditor_name}}',
    'description' => 'Final notice SMS for overdue payments',
    'is_default' => true
]);

// Create email templates
Template::create([
    'name' => 'Payment Reminder Email',
    'type' => 'email',
    'email_subject' => 'Payment Reminder - Outstanding Balance €{{total_for_due_date}}',
    'content' => 'Dear {{debtor_contact_person_name}},

We hope this email finds you well. We are writing to remind you about an outstanding balance on your account.

Account Details:
- Debtor: {{debtor_name}}
- Creditor: {{creditor_name}}
- Capital: €{{balance_capital}}
- Penalty Interest: €{{balance_penalty_interest}}
- Collection Fee: €{{balance_debt_collection_fee}}
- Total Amount Due: €{{total_for_due_date}}
- Due Date: {{due_date}}

Please arrange payment at your earliest convenience. If you have any questions or need to discuss payment arrangements, please contact our representative {{creditor_contact_person_name}} at {{creditor_contact_person_email}} or {{creditor_contact_person_phone}}.

We appreciate your prompt attention to this matter.

Best regards,
{{creditor_name}}
{{creditor_contact_person_name}}
{{creditor_contact_person_email}}
{{creditor_contact_person_phone}}',
    'description' => 'Standard email reminder for outstanding payments',
    'is_default' => true
]);

// Create PDF template
Template::create([
    'name' => 'Payment Demand Letter',
    'type' => 'pdf',
    'content' => '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            line-height: 1.6; 
            color: #333; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 40px; 
            border-bottom: 2px solid #000; 
            padding-bottom: 20px; 
        }
        .company-info { 
            margin-bottom: 30px; 
        }
        .debtor-info { 
            margin-bottom: 30px; 
            background-color: #f5f5f5; 
            padding: 15px; 
        }
        .amount-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        .amount-table th, .amount-table td { 
            border: 1px solid #ccc; 
            padding: 10px; 
            text-align: left; 
        }
        .amount-table th { 
            background-color: #f0f0f0; 
        }
        .total-row { 
            font-weight: bold; 
            background-color: #e8e8e8; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PAYMENT DEMAND</h1>
        <p><strong>Date:</strong> {{due_date}}</p>
    </div>

    <div class="company-info">
        <h3>From:</h3>
        <p><strong>{{creditor_name}}</strong><br>
        Contact: {{creditor_contact_person_name}}<br>
        Email: {{creditor_contact_person_email}}<br>
        Phone: {{creditor_contact_person_phone}}</p>
    </div>

    <div class="debtor-info">
        <h3>To:</h3>
        <p><strong>{{debtor_name}}</strong><br>
        Contact: {{debtor_contact_person_name}}</p>
    </div>

    <p>We hereby demand payment of the following outstanding amount:</p>

    <table class="amount-table">
        <tr>
            <th>Description</th>
            <th>Amount (€)</th>
        </tr>
        <tr>
            <td>Capital</td>
            <td>{{balance_capital}}</td>
        </tr>
        <tr>
            <td>Penalty Interest ({{penalty_interest_rate}}%)</td>
            <td>{{balance_penalty_interest}}</td>
        </tr>
        <tr>
            <td>Debt Collection Fee</td>
            <td>{{balance_debt_collection_fee}}</td>
        </tr>
        <tr class="total-row">
            <td><strong>TOTAL AMOUNT DUE</strong></td>
            <td><strong>{{total_for_due_date}}</strong></td>
        </tr>
    </table>

    <p><strong>Payment must be made within 14 days of receipt of this notice.</strong></p>

    <p>Failure to pay the above amount may result in legal proceedings being commenced against you, which could result in additional costs and may affect your credit rating.</p>

    <p>If you wish to discuss this matter or make payment arrangements, please contact us immediately using the details provided above.</p>
</body>
</html>',
    'description' => 'Formal payment demand letter in PDF format',
    'is_default' => true
]);

echo "Templates created successfully!\n";
echo "Total templates: " . Template::count() . "\n";
