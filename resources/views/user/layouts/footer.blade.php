<!-- FOOTER SECTION start -->
<div class="footer" id="footer">
    <div class="content-wrap">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="footer-item">
                        <img src="https://www.wastina.com/wp-content/uploads/2018/06/wastina_logo_new.png" alt="logo bottom" class="logo-bottom">
                        <p class="mt-1 mb-5">Wastina is a registered and trademarked product of American Medical Center. Wastina is a membership-based medical benefit plan. Membership is available on an annual basis, and subject to change at renewal.</p>
                        <div class="uk24">Trusted by <strong>225.000</strong> People</div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-4">
                    <div class="footer-item">
                        <div class="footer-title">
                            Contact Us
                        </div>
                        <div class="textwidget">
                            <div class="zozo-features-list-wrapper vc-features-list margin-bottom-none">
                                <div class="features-list">
                                    <div class="features-list-inner list-text-default">
                                        <div class="list-desc" style="margin-left:28px;">
                                            <p>{{ $contactInfo->address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="zozo-features-list-wrapper vc-features-list margin-bottom-none">
                                <div class="features-list">
                                    <div class="features-list-inner list-text-default">
                                        <div class="list-desc" style="margin-left:28px;">
                                            <p>{{ $contactInfo->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="zozo-features-list-wrapper vc-features-list margin-bottom-none">
                                <div class="features-list">
                                    <div class="features-list-inner list-text-default">
                                        <div class="list-desc" style="margin-left:28px;">
                                            <p>{{ $contactInfo->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="zozo-features-list-wrapper vc-features-list margin-bottom-none">
                                <div class="features-list">
                                    <div class="features-list-inner list-text-default">
                                        <div class="list-desc" style="margin-left:28px;">
                                            <p>{{ $contactInfo->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="footer-title">
                            Follow Us
                        </div>
                        <div class="sosmed-icon bg-round d-inline-flex">
                            <a href="{{ $contactInfo->facebook }}" target="_blank"><i class="fa fa-facebook"></i></a> 
                            <a href="{{ $contactInfo->twitter }}" target="_blank"><i class="fa fa-twitter"></i></a> 
                            <a href="{{ $contactInfo->instagram }}" target="_blank"><i class="fa fa-instagram"></i></a> 
                            <a href="{{ $contactInfo->pinterest }}" target="_blank"><i class="fa fa-pinterest"></i></a> 
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-md-12 col-lg-4">
                    <div class="footer-item">
                        <div class="footer-title">
                            Working Hours
                        </div>
                        @foreach($workingHours as $item)
                            <div class="row mb-2">
                                <div class="col">
                                    <strong>{{ $item->week_day }} :</strong>
                                </div>
                                <div class="col">
                                    @if($item->open_status == 1)
                                        <div class="text-right">{{ $item->start }} - {{ $item->end }}</div>
                                    @else
                                        <div class="text-right">Closed</div>
                                    @endif
                                    
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="fcopy">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-12">
                    <p class="ftex mt-1 text-white">Copyright 2019 &copy; Designed by <span class="color-primary">Aliya_Gull</span></p> 
                </div>
            </div>
        </div>
    </div>
    
</div>
<!--./ FOOTER SECTION end -->