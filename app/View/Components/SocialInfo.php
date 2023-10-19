<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SocialInfo extends Component
{
    public $user;
    public $otherLinkArr;
    
    public function __construct($user, $otherLinkArr)
    {
        $this->user = $user;
        $this->otherLinkArr = $otherLinkArr;
    }
    
    public function render()
    {
        return view('components.social-info');
    }
}
