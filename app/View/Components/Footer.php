<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Footer extends Component
{
    public $slug;
    public $masterMenus;
    public $sectionName;
    
    public function __construct($slug, $masterMenus, $sectionName)
    {
        $this->slug = $slug;
        $this->sectionName = $sectionName;
        $this->masterMenus = $masterMenus;
    }

    public function render()
    {
        return view('components.footer');
    }
}
