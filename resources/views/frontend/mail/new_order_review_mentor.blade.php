@include('frontend.mail.includes.htmlup')


<p>Hi {{ $firstname }},</p>
<p>You have a new order for a review!</p>

<table>
    <tbody>
    <tr style="background-color: #e4e4e4;">
        <td>Order</td>
        <td>Order date</td>
    </tr>
        <tr>
        <td>{{ $order }}</td>
        <td>{{ $orderdate}}</td>
    </tr>
    <tr style="background-color: #e4e4e4;">
        <td>Item</td>
        <td>Due date</td>
    </tr><tr>
    <td>{!! $item !!}</td>
    <td><strong>{{ $duedate }}</strong></td>
    </tr></tbody>
</table>
<div style="padding: 18px; border: solid 1px #ddd; margin-top: 24px;">
    <h3 style="margin-top: 8px; margin-bottom: 0px;">Video to review:</h3>
    <a href="{{ $video }}">{{ $video }}</a>
    <h3 style="margin-top: 16px; margin-bottom: 0px;">Note from user:</h3>
    <p>{{ $note }}</p>
    @if($meeting && $meeting['status']=='success')
        <p></p>
        <p>Meeting URL: <a href="{{$meeting['meeting_url']}}">Join Meeting</a><br>
        Meeting Password: {{$meeting['meeting_password']}}
        </p>
    @endif
</div>


@include('frontend.mail.includes.htmldown')
