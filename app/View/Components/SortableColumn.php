<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SortableColumn extends Component
{
    public $field;
    public $label;
    public $route;

    public function __construct($field, $label, $route = 'dashboard-analytics')
    {
        $this->field = $field;
        $this->label = $label;
        $this->route = $route;
    }

    /**
     * Create a new component instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.sortable-column');
    }
}
