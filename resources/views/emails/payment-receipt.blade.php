<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #479c18; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; background: #f8f9fa; }
        .footer { text-align: center; padding: 20px; color: #6c757d; font-size: 0.9rem; }
        .receipt-details { background: white; padding: 20px; border-radius: 5px; }
        .detail-row { display: flex; justify-content: space-between; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Payment Receipt</h2>
        </div>
        
        <div class="content">
            <p>Dear {{ $user->name }},</p>
            <p>Thank you for your registration payment. Here is your payment receipt:</p>
            
            <div class="receipt-details">
                <div class="detail-row">
                    <strong>Transaction Reference:</strong>
                    <span>{{ $payment->transaction_reference }}</span>
                </div>
                <div class="detail-row">
                    <strong>Payment Date:</strong>
                    <span>{{ $payment->paid_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="detail-row">
                    <strong>Amount:</strong>
                    <span>₦{{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="detail-row">
                    <strong>Payment Method:</strong>
                    <span>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                </div>
                <div class="detail-row">
                    <strong>Status:</strong>
                    <span style="color: #28a745;">{{ ucfirst($payment->status) }}</span>
                </div>
            </div>
            
            <p>Your registration is now active until {{ $payment->expires_at->format('d M Y') }}.</p>
            
            <p>If you have any questions, please contact our support team.</p>
            
            <p>Best regards,<br>Your Service Team</p>
        </div>
        
        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>