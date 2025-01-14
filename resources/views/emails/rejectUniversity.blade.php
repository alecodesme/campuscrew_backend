<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Application Status</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #ffffff; margin: 0; padding: 0;">

    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <!-- Header -->
        <div style="background-color: #ECEBE4; padding: 20px; text-align: center;">
            <img height="100" width="78" src="https://iili.io/2ESGjtt.png" alt="University Logo" />
            <h1 style="color: black; font-size: 24px; margin: 0;">Application Update</h1>
        </div>

        <!-- Body -->
        <div style="padding: 20px;">
            <p style="font-size: 18px; color: #374151; margin-bottom: 20px;">
                Dear Applicant,
            </p>
            <p style="font-size: 16px; color: #4b5563; margin-bottom: 20px;">
                We regret to inform you that your application to <strong>{{ $data['university'] }}</strong> has not been accepted at this time.
            </p>
            <p style="font-size: 16px; color: #4b5563; margin-bottom: 20px;">
                Please find below the observation from the review team:
            </p>
            <blockquote style="font-size: 16px; color: #1f2937; background-color: #f3f4f6; padding: 12px 16px; border-left: 4px solid #f87171; margin: 20px 0;">
                {{ $data['observation'] }}
            </blockquote>
            <p style="font-size: 16px; color: #4b5563; margin-bottom: 20px;">
                We encourage you to reapply in the future or explore other opportunities. Thank you for considering <strong>{{ $data['university'] }}</strong>.
            </p>
            <a href="http://localhost:8080/auth/sign-in" style="display: inline-block; background-color: black; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 4px; font-size: 16px; text-align: center;">
                Visit Our Website
            </a>
        </div>

        <!-- Footer -->
        <div style="background-color: #f3f4f6; padding: 10px; text-align: center;">
            <p style="font-size: 12px; color: #6b7280; margin: 0;">
                © {{ date('Y') }} {{ $data['university'] }}. All rights reserved.
            </p>
        </div>
    </div>

</body>

</html>