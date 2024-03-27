<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'order_status_id',
        'event_id',
        'seller_id',
        'service_note',
        'buyer_id',
        'animal_id',
        'batch',
        'parcel_value',
        'multiplier',
        'gross_value',
        'installment_formula',
        'discount_percentage',
        'due_day',
        'reinforcements_amount',
        'reinforcement_value',
        'reinforcement_parcels',
        'business_note',
        'buyer_commission',
        'buyer_commission_installments_number',
        'buyer_due_day',
        'seller_commission',
        'seller_commission_installments_number',
        'seller_due_day',
        'entry_contracts',
        'entry_promissory',
        'entry_register_copy',
        'entry_first_parcel_business',
        'entry_first_parcel_comission',
        'entry_buyer_sending_documentation_date',
        'entry_buyer_sending_documentation_way',
        'entry_contract_return_date',
        'entry_documentation_note',
        'output_contracts',
        'output_promissory',
        'output_register_copy',
        'output_seller_sending_documentation_date',
        'output_seller_sending_documentation_way',
        'output_contract_return_date',
        'output_documentation_note',
        'closing_date',
    ];

    protected $casts = [
        'parcel_value' => 'double',
        'gross_value' => 'double',
        'discount_percentage' => 'double',
        'reinforcement_value' => 'double',
        'buyer_commission' => 'double',
        'seller_commission' => 'double',
        'entry_contracts' => 'boolean',
        'entry_promissory' => 'boolean',
        'entry_register_copy' => 'boolean',
        'output_contracts' => 'boolean',
        'output_promissory' => 'boolean',
        'output_register_copy' => 'boolean',

    ];

}
