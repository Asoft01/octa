@include('frontend.mail.includes.htmlup')


<p>Hi {{ $firstname }},</p>
<p>Here's the invoice for your order:</p><p>
<strong>Agora-VFX Inc.</strong><br>
7170 de Normenville<br>
Montreal, Canada<br>
H2R 2T8<br>
<a href="mailto:sales@agora.studio">sales@agora.studio</a></p>
<table>
    <tbody>
    <tr style="background-color: #e4e4e4;">
        <td>Order</td>
        <td>Date</td>
    </tr>
        <tr>
        <td>{{ $order }}</td>
        <td>{{ $orderdate}}</td>
    </tr>
    <tr style="background-color: #e4e4e4;">
        <td>Item</td>
        <td>Paid amount</td>
    </tr><tr>
    <td>{!! $item !!}</td>
    <td><strong>{{ $symbol }}{{ $price }} {{ $currency }}</strong></td>
    </tr></tbody>
</table>

<div>

    @if($meeting && $meeting['status']=='success')
        <p></p>
            <p>Meeting URL: <a href="{{$meeting['meeting_url']}}">Join Meeting</a><br>
            Time:  {{ timezone()->convertToLocal(new \Carbon\Carbon($meeting['datetime'])) }}  <br>
            Duration: {{$meeting['duration']}} minutes<br>

            Meeting Password: {{$meeting['meeting_password']}}
        </p>
    @endif
</div>
<div style="padding: 18px; border: solid 1px #ddd; margin-top: 24px;">
    <h3 style="margin-top: 8px; margin-bottom: 16px;">Now what's next?</h3>
    <p>Be patient, you will receive an email when your review is available! You can also manage your reviews in the dedicated section of the platform: <a href="https://agora.community/manage">https://agora.community/manage</a>.</p>
    <p>Thank you very much!</p>
</div>


@include('frontend.mail.includes.htmldown')
