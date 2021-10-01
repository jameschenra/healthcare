@php
    $effectDate = Carbon\Carbon::parse($user->membership->created_at)->format('m/d/Y') 
        . ' - ' . Carbon\Carbon::parse($user->membership->expires_in)->format('m/d/Y');
@endphp
<!doctype html>
<html>
    <head>
        <style>
            html, body {
                width: 635px;
                height: 375px;
                margin: 0;
                padding: 0;
            }

            .card-container {
                width: 100%;
                height: 100%;
                position: relative;
            }

            .card-container .img-bg {
                width: 100%;
                height: 100%;
            }

            .img-photo {
                width: 196px;
                height: 196px;
                position: absolute;
                left: 422px;
                top: 33px;
            }

            .img-qrcode {
                width: 60px;
                height: 60px;
                position: absolute;
                left: 547px;
                top: 295px;
            }

            .text-info {
                position: absolute;
                color: #707070;
                font-size: 20px;
                font-weight: 700;
            }

            .text-id {
                left: 100px;
                top: 136px;
            }

            .text-name {
                left: 160px;
                top: 161px;
            }

            .text-birthday {
                left: 178px;
                top: 187px;
            }

            .text-date {
                left: 182px;
                top: 323px;
                color: #fbfbfb;
            }
            
        </style>
    </head>
    <body>
        <div class="card-container" id="card-container">
            <img class="img-bg" src="{{ asset('public/files/users/card-bg.png') }}" alt="background"/>
            <img class="img-photo" src="{{ asset('public/files/users/' . $user->id . '/' . $user->userInfo->photo) }}" alt="photo"/>
            <img class="img-qrcode" src="{{ asset('public/files/users/' . $user->id . '/qrcode.png') }}" alt="qrcode"/>
            <span class="text-info text-id">{{ $user->membership->membership_number }}</span>
            <span class="text-info text-name">{{ $user->userInfo->first_name }} {{ $user->userInfo->last_name }}</span>
            <span class="text-info text-birthday">{{ $user->userInfo->birthday }}</span>
            <span class="text-info text-date">{{ $effectDate }}</span>
        </div>
        <button type="button" style="width: 100px; height: 40px; margin-left: 150px;" onclick="printDiv()">Print</button>

        <script>
            function printDiv() {
                var printContents = document.getElementById('card-container').innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;
            }
        </script>
    </body>
</html>