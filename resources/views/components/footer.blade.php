<div>
    <!-- Footer Start -->
    <div class="footer-outer">
        <div class="footer-inner footer-cont">
            @foreach ($masterMenus as $key => $menu)
                @if ($key == 'profile_status')
                    <a href="{{ url('/') }}/home/{{ $slug }}" class="active">
                        <div>
                            @if ($menu['icon_image'])
                                <img class="img-30" src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/section_icon/{{ $menu['icon_image'] }}" onError="this.onerror=null;this.src='/assets/images/icon/cart.png';" />
                            @else
                                <img class="img-30" src="{{url('/')}}/dist/theme_assets/img/icon/building.png">
                            @endif
                        </div>
                        {{ 'Profile' }}
                    </a>
                @endif
                @if ($key == 'profile_status')
                    <a href="{{ url('/') }}/company/{{ $slug }}" class="">
                        <div>
                            @if ($menu['icon_image'])
                                <img class="img-30" src="{{url('/')}}/dist/theme_assets/img/icon/building.png" />
                            @else
                                <img class="img-30" src="{{url('/')}}/dist/theme_assets/img/icon/building.png">
                            @endif
                        </div>
                        {{ 'Company' }}
                    </a>
                @endif
                {{-- @if ($key == 'service_status')
                    <a href="{{ url('/') }}/services/{{ $slug }}" class="">
                        <div>
                            @if ($menu['icon_image'])
                                <img class="img-30" src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/section_icon/{{ $menu['icon_image'] }}" onError="this.onerror=null;this.src='/assets/images/icon/cart.png';" />
                            @else
                                <img src="{{url('/')}}/dist/theme_assets/img/icon/cart.png">
                            @endif
                        </div>
                        {{ $sectionName[0]->services }}
                    </a>
                @endif
                @if ($key == 'product_status')
                    <a href="{{ url('/') }}/home/{{ $slug }}" class="">
                        <div>
                            @if ($menu['icon_image'])
                                <img class="img-30" src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/section_icon/{{ $menu['icon_image'] }}" onError="this.onerror=null;this.src='/assets/images/icon/cart.png';" />
                            @else
                                <img src="{{url('/')}}/dist/theme_assets/img/icon/cart.png">
                            @endif
                        </div>
                        {{ $sectionName[0]->products }}
                    </a>
                @endif --}}
                @if ($key == 'gallery_status')
                    <a href="{{ url('/') }}/gallery/{{ $slug }}" class="">
                        <div>
                            @if ($menu['icon_image'])
                                <img class="img-30" src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/section_icon/{{ $menu['icon_image'] }}" onError="this.onerror=null;this.src='/assets/images/icon/cart.png';" />
                            @else
                                <img class="img-30" src="{{url('/')}}/dist/theme_assets/img/icon/review.png">
                            @endif
                        </div>
                        {{ $sectionName[0]->gallery }}
                    </a>
                @endif
                @if ($key == 'client_status')
                    <a href="{{ url('/') }}/clients/{{ $slug }}" class="">
                        <div>
                            @if ($menu['icon_image'])
                                <img class="img-30" src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/section_icon/{{ $menu['icon_image'] }}" onError="this.onerror=null;this.src='/assets/images/icon/cart.png';" />
                            @else
                                <img class="img-30" src="{{url('/')}}/dist/theme_assets/img/icon/teamwork.png">
                            @endif
                        </div>
                        {{ $sectionName[0]->clients }}
                    </a>
                @endif
                {{-- @if ($key == 'team_status')
                    <a href="{{ url('/') }}/home/{{ $slug }}" class="">
                        <div>
                            @if ($menu['icon_image'])
                                <img class="img-30" src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/section_icon/{{ $menu['icon_image'] }}" onError="this.onerror=null;this.src='/assets/images/icon/cart.png';" />
                            @else
                                <img src="{{url('/')}}/dist/theme_assets/img/icon/cart.png">
                            @endif
                        </div>
                        {{ $sectionName[0]->team }}
                    </a>
                @endif --}}
                @if ($key == 'bank_status')
                    <a href="{{ url('/') }}/payments/{{ $slug }}">
                        <div>
                            @if ($menu['icon_image'])
                                <img class="img-30" src="{{ url('/') }}/account/user/uploads/{{ $user->saved_email }}/section_icon/{{ $menu['icon_image'] }}" onError="this.onerror=null;this.src='/assets/images/icon/cart.png';" />
                            @else
                                <img class="img-30" src="{{url('/')}}/dist/theme_assets/img/icon/point-of-service.png">
                            @endif
                        </div>
                        {{ $sectionName[0]->bank }}
                    </a>
                @endif
            @endforeach

            {{-- <a href="{{url('/')}}/home/{{$slug}}" class="arroba1">
                <div>
                </div>
                Profile
            </a>
            <a href="{{url('/')}}/company/{{$slug}}" class="arroba1">
                <div>
                </div>
                Company
            </a>
            <a href="{{url('/')}}/gallery/{{$slug}}" class="arroba1">
                <div>
                </div>
                Gallery
            </a>
            <a href="{{url('/')}}/clients/{{$slug}}" class="arroba1">
                <div>
                </div>
                Clients
            </a>
            <a href="{{url('/')}}/payments/{{$slug}}" class="arroba1">
                <div>
                </div>
                Payments
            </a> --}}
        </div>
    </div>
    <!-- Footer End -->
</div>
