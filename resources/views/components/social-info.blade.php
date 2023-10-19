<div>
    <!-- Social Media Start -->
    <div class="social-outer cont">
        <div class="social-inner">
            <a href="https://wa.me/{{$user->whatsapp_no}}"><i class="fa-brands fa-square-whatsapp"></i></a>
            <a href="{{$user->instagram}}"><i class="fa-brands fa-square-instagram"></i></a>
            <a href="{{$user->linked_in}}"> <i class="fa-brands fa-linkedin"></i></a>
            <a href="{{$user->facebook}}"><i class="fa-brands fa-facebook"></i></a>
            <a href="mailto:{{$user->saved_email}}"><i class="fa-solid fa-envelope"></i></a>
            <a href="{{$user->youtube}}}"><i class="fa-brands fa-youtube"></i></a>
            <a href="{{$user->twitter}}"><i class="fa-brands fa-twitter"></i></a>
            
            @foreach ($otherLinkArr as $otherLink)
                <a href="{{$otherLink['link_url']}}">
                    {{-- <i class="fa-brands fa-twitter"></i> --}}
                    <img src="{{url('/')}}/account/assets/img/business-icon/{{$otherLink['icon_name']['domainX']}}.png" class="img-20" alt="">
                    
                </a>
            @endforeach
        </div>
    </div>
    <!-- Social Media End -->

    <!-- Info Section Start -->
    <div class="info-outer cont">
        <div class="info-inner">
            <div class="info-call">
                <a href="tel:+{{$user->altr_contact_no}}"><i class="fa-solid fa-phone"></i> Call</a>
            </div>
            <div class="info-direction">
                <a href="{{$user->map_link}}"><i class="fa-sharp fa-solid fa-location-dot"></i> Direction</a>
            </div>
            <div class="info-web">
                <a href="{{$user->website_url}}"><i class="fa-solid fa-globe"></i> Website</a>
            </div>
        </div>
    </div>
    <!-- Info Section End -->
    <!-- Send Section Start -->
    <div class="send-outer cont">
        <div class="send-inner">
            <a href="{{url('/')}}/save-contact/{{$user->id}}" class="btn text-white">Save Contact</a>
            <div class="send-right" data-toggle="modal" data-target="#flyoutmodal">
                <i class="fa-solid fa-paper-plane"></i>
            </div>
        </div>
    </div>
    <!-- Send Section End -->
</div>
