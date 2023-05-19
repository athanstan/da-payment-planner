<!DOCTYPE html>
<html>
<head>
    <title>Email Report</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 0; font-size: 16px; color: #333; }
        .container { padding: 20px; }
        h1, h2 { color: #444; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 15px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #4CAF50; color: white; }
        .highlight { font-weight: bold; color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>DTek Networking Email Report</h1>
        <p>Dear Support Team,</p>
        <p>This is the automated report for the hosting expiration notifications sent to our clients. Below, you will find the details of the emails that were sent and those that were not sent.</p>

        <h2>Emails Sent</h2>
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($emailsSent as $email)
                    <tr>
                        <td>{{ $email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h2>Emails Not Sent</h2>
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($emailsNotSent as $email => $reason)
                    <tr>
                        <td>{{ $email }}</td>
                        <td class="highlight">{{ $reason }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>If there are any issues or concerns regarding these notifications,
            please don't hesitate to get in touch with the responsible team.</p>
        <p>Best Regards,</p>
        <p>Da Payment Planner</p>
    </div>
</body>
</html>
