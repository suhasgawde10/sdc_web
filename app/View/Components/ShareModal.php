<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ShareModal extends Component
{
    public $user;
    public function __construct($user)
    {
        $this->user = $user;
    }
    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.share-modal');
    }
}
