<?php

namespace App\View\Components\Filament;

use Illuminate\View\Component;

class WidgetCard extends Component
{
    public string $color;
    public string $icon;
    public $count;
    public string $label;
    public ?string $url;

    public function __construct(string $color, string $icon, $count, string $label, ?string $url = null)
    {
        $this->color = $color;
        $this->icon = $icon;
        $this->count = $count;
        $this->label = $label;
        $this->url = $url;
    }

    public function render()
    {
        return view('components.filament.widget-card');
    }
}
