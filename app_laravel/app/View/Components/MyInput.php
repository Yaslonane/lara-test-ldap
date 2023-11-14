<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MyInput extends Component
{
    public $inputType;
    public $value;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($inputType = "text", $value = "")
    {
        $this->inputType = $inputType;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.my-input');
    }
}
