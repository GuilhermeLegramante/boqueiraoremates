<?php

namespace EightyNine\Reports\Components\Body;

use App\Models\Client;
use Closure;
use EightyNine\Reports\Components\Component;
use Illuminate\Support\Collection;

class CustomTable extends Component
{
    use Concerns\HasColumns;
    use Concerns\HasHeadings;

    /**
     * @var view-string
     */
    protected string $view = 'reports.custom-table';

    protected Collection $data;

    public function __construct()
    {
        
    }

    public function data(Closure $dataClosure): static
    {
        $this->data = $this->evaluate($dataClosure);

        return $this;
    }

    public function getClient(): Client
    {
        if(count($this->getFilters()) > 0){
            return Client::where('id', $this->getFilters()["client"])->get()->first();
        }
        return new Client();
    }


    public function getData(): Collection
    {
        return $this->data;
    }

    public static function make(): static
    {
        $static = app(static::class);

        return $static;
    }

    public function getFilters()
    {
        return reports()->getFilterState();
    }
}
