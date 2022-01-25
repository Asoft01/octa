@include('frontend.mail.includes.htmlup')

<p>Hi {{ $firstname }},</p>
<p>Great news! Your review is ready for order:<br />{{ $delivery_id }}.</p>
<div style="padding: 18px; border: solid 1px #ddd; margin-top: 24px;">
    <h3 style="margin-top: 8px; margin-bottom: 16px;">How to access the review?</h3>
    <p>Log in on agora.community and go to manage your reviews in the dedicated section of the platform: <a href="https://agora.community/manage">https://agora.community/manage</a>.</p>
    <p>Thank you very much!</p>
</div>

@include('frontend.mail.includes.htmldown')