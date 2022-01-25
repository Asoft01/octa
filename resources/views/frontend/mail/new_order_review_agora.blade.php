@include('frontend.mail.includes.htmlup')


<p>Hi,</p>
<p>There's a new review order.</p>

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
        <td>Due date</td>
    </tr><tr>
    <td>{!! $item !!}<br />
    {{ $symbol }}{{ $price }} {{ $currency }}</td>
    <td><strong>{{ $duedate }}</strong></td>
    </tr></tbody>
</table>

<div style="padding: 18px; border: solid 1px #ddd; margin-top: 24px;">
    <h3 style="margin-top: 8px; margin-bottom: 0px;">Video to review:</h3>
    <a href="https://cdn.agora.community/uploadReviews/{{ $video }}">{{ $video }}</a>
    <h3 style="margin-top: 16px; margin-bottom: 0px;">Note from user:</h3>
    <p>{{ $note }}</p>
</div>



@include('frontend.mail.includes.htmldown')
