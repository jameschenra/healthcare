@component('mail::message')
<style>
    .address {
        width: 50%;
        float: left;
    }

    table.info-table {
        border-collapse: collapse;
    }

    table.info-table, .info-table th, .info-table td {
        border: 1px solid grey;
    }

    .info-table th, .info-table td {
        padding: 10px;
    }
</style>
Dear {{ $name }}

Please retain this email for your records.

Thank you for signing up for membership plan(s) at Wastina! We appreciate your business and look to provide you superior services at our participating healthcare facilities!

Please find below a summary of your plan(s)

<table class="info-table">
    <thead>
        <tr>
            <th>Member</th>
            <th>Relationship</th>
            <th>Plan Level</th>
            <th>Birthday</th>
            <th>Start Date</th>
            <th>Expiration Date</th>
            <th>Amount Paid</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>{{ $membership->user->userInfo->first_name }}</td>
            <td>{{ $membership->user->relationship }}</td>
            <td>{{ $membership->planType->name }}</td>
            <td>{{ $membership->user->userInfo->birthday }}</td>
            <td>{{ $membership->created_at }}</td>
            <td>{{ $membership->expires_in }}</td>
            <td>$ {{ $membership->total_price }}</td>
        </tr>
        <tr>
            <td>Total Paid</td>
            <td colspan="5"></td>
            <td>$ {{ $membership->total_price }}</td>
        </tr>
    </tbody>
</table>
<br />

If you have any questions regarding your membership plan, please contact us at customer@wastina.com

To complete your registration and get your membership card, please go to one of our locations below.

<br />
<div style="clear: both">
    <div class="left address">
        <p><strong>AMC#1</strong></p>
        <p>Meri Lokie Branch</p>
        <p>Addis Ababa, Ethiopia</p>
        <p>ethiopiaamc@gmail.com</p>
        <p>+251 -116 67 80 04/07/18/20</p>
    </div>
    <div class="right address">
        <p><strong>AMC#1</strong></p>
        <p>Meri Lokie Branch</p>
        <p>Addis Ababa, Ethiopia</p>
        <p>ethiopiaamc@gmail.com</p>
        <p>+251 -116 67 80 04/07/18/20</p>
    </div>  
</div>

We look forward to serving you!

Warm Regards,

Wastina Administration
@endcomponent
