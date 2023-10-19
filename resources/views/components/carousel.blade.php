<div>
    <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach ($images as $key => $image)
                <li data-target="#carouselExampleIndicators" data-slide-to="{{ $key }}" @if ($key === 0) class="active" @endif></li>
            @endforeach
        </ol>
        <div class="carousel-inner">
            @foreach ($images as $key => $image)
                <div class="carousel-item @if ($key === 0) active @endif">
                    <img class="d-block w-100" src="{{ $image['url'] }}" alt="Cover Image">
                </div>
            @endforeach
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
  
</div>
