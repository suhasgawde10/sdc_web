<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Header extends Component
{
    public $user;
    public $clientsReviewCount;
    public $clientReviewsAvg;
    public function __construct($user, $clientsReviewCount, $clientReviewsAvg)
    {
        $this->user = $user;
        $this->clientsReviewCount = $clientsReviewCount;
        $this->clientReviewsAvg = $clientReviewsAvg;
    }

    
    public function render()
    {
        return view('components.header');
    }
}
