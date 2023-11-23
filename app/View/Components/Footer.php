<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Footer extends Component
{
    public $slug;
    public $masterMenus;
    public $sectionName;
    public $user;
    
    
    public function __construct($slug, $masterMenus, $sectionName, $user)
    {
        $this->slug = $slug;
        $this->sectionName = $sectionName;
        $this->masterMenus = $masterMenus;
        $this->user = $user;
    }

    public function render()
    {
        return view('components.footer');
    }
}
