<?php

namespace App\View\Components\Forms;

use Illuminate\View\Component;

class Input extends Component
{
    public $col, $label, $placeholder, $name, $type, $group, $value, $attrs, $className;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($label, $name, $group, $placeholder = null, $type = 'text', $col = 12, $value = null, $attrs = null, $className = null)
    {
        $this->label       = $label;
        $this->name        = $name;
        $this->placeholder = $placeholder;
        $this->group       = $group;
        $this->type        = $type;
        $this->col         = $col;
        $this->value       = $value;
        $this->attrs       = $attrs;
        $this->className   = $className;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.forms.input');
    }
}
