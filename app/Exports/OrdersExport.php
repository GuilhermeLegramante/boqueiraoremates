<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;

class OrdersExport implements FromCollection
{
    public function __construct(public $orders) {
        dd("a");
    }

    public function collection()
    {
        return $this->orders;
    }
}
