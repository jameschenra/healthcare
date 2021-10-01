@extends("user.layouts.main")

@section("css")
@endsection

@section("content")
<section class="hero-wrap hero-wrap-2" style="background-color:#A9A9A9;" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
        <div class="row no-gutters slider-text align-items-center justify-content-center">
            <div class="col-md-9 ftco-animate text-center">
                <h1 class="mb-2 bread">Contact Us</h1>
            </div>
        </div>
    </div>
</section>

<br />
<section class="ftco-section ftco-no-pt ftco-no-pb contact-section">
    <div class="container">
        <div class="row">
            <div class="col-md-6 p-4 p-md-5 order-md-last bg-light">
                <form action="#">
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Your Name">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Your Email">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Subject">
                    </div>
                    <div class="form-group">
                        <textarea name="" id="" cols="30" rows="7" class="form-control" placeholder="Message"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Send Message" class="btn btn-primary py-3 px-5">
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <div id="googleMap" style="width:100%;min-height:300px; height: 100%"></div>
            </div>
        </div>
    </div>
</section>
<br />

@endsection

@section("js")
    <script>
        function myMap() {
            var mapProp= {
                center:new google.maps.LatLng(51.508742,-0.120850),
                zoom:5,
            };
            var map = new google.maps.Map(document.getElementById("googleMap"),mapProp);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD6gLulCrN4TJiuoGdwjlgzOpsZ6ehihZE&callback=myMap"></script>
@endsection