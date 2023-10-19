<div>
    {{-- @props(['clientReviewsAvg', 'user', 'clientsReviewCount']) --}}
    <div class="header-outer row cont">
        <div class="header-left col-lg-4 col-md-4 col-4">
          {{-- asset('dist/theme_assets/img/head.jpg') --}}
          <div class="header-left-img" style="background-image: url('{{url('/')}}/account/user/uploads/{{$user->saved_email}}/profile/{{$user->img_name}}');"></div>
        </div>
        <div class="header-right col-lg-8 col-md-8 col-8">
          <div class="header-title">
            <h2>{{$user->name}} <i class="fa-sharp fa-solid fa-circle-check"></i></h2>
          </div>
          <div class="header-desc">
            <h4>{{$user->designation}}</h4>
          </div>
          <div class="header-rating">
            {!! renderStarRating($clientReviewsAvg, 5) !!}
            <span class="rate">({{$clientsReviewCount}})</span>
          </div>
        </div>
      </div>
</div>