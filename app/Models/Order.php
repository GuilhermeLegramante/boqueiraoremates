<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $appends = ['gross_parcel'];

    protected $fillable = [
        'number',
        'base_date',
        'order_status_id',
        'event_id',
        'seller_id',
        'service_note',
        'buyer_id',
        'animal_id',
        'batch',
        'parcel_value',
        'first_parcel_value',
        'multiplier',
        'gross_value',
        'payment_way_id',
        'discount_percentage',
        'due_day',
        'first_due_date',
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
        'sending_docs_method_id',
        'entry_contract_return_date',
        'entry_documentation_note',
        'output_contracts',
        'output_promissory',
        'output_register_copy',
        'output_first_parcel_date',
        'output_sending_documentation_date',
        'output_seller_sending_documentation_way',
        'output_documentation_note',
        'closing_date',
        'output_sending_docs_method_id',
        'entry_sending_docs_method_id'
    ];

    protected $casts = [
        'parcel_value' => 'double',
        'first_parcel_value' => 'double',
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

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'seller_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'buyer_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function animal(): BelongsTo
    {
        return $this->belongsTo(Animal::class);
    }

    public function paymentWay(): BelongsTo
    {
        return $this->belongsTo(PaymentWay::class);
    }

    public function parcels(): HasMany
    {
        return $this->hasMany(Parcel::class);
    }

    public function buyerParcels(): HasMany
    {
        return $this->hasMany(BuyerParcel::class);
    }

    public function sellerParcels(): HasMany
    {
        return $this->hasMany(SellerParcel::class);
    }

    public function entrySendingDocsMethod(): BelongsTo
    {
        return $this->belongsTo(SendingDocsMethod::class, 'entry_sending_docs_method_id');
    }

    public function outputSendingDocsMethod(): BelongsTo
    {
        return $this->belongsTo(SendingDocsMethod::class, 'output_sending_docs_method_id');
    }

    public function getGrossParcelAttribute()
    {
        if($this->multiplier > 0) {
            $grossParcel = $this->gross_value / $this->multiplier;
        } else {
            $grossParcel = 0;
        }

        return $grossParcel;
    }
}
