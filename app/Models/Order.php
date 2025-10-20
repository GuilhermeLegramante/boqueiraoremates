<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory;

    protected $appends = ['gross_parcel', 'net_value', 'buyer_comission_value', 'seller_comission_value', 'total_commission'];

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
        'entry_seller_signature_date',
        'entry_witness_signature_date',
        'entry_documentation_note',
        'output_contracts',
        'output_promissory',
        'output_register_copy',
        'output_first_parcel_comission',
        'output_sending_documentation_date',
        'output_seller_sending_documentation_way',
        'output_documentation_note',
        'output_first_parcel_comission',
        'closing_date',
        'output_sending_docs_method_id',
        'entry_sending_docs_method_id',
        'able_to_exam',
        'able_to_exam_date',
        'able_to_loading',
        'able_to_loading_date',
    ];

    protected $casts = [
        'parcel_value' => 'double',
        'first_parcel_value' => 'double',
        'gross_value' => 'double',
        'discount_percentage' => 'double',
        'reinforcement_value' => 'double',
        'buyer_commission' => 'double',
        'buyer_commission_value' => 'double',
        'seller_commission' => 'double',
        'seller_commission_value' => 'double',
        'entry_contracts' => 'boolean',
        'entry_promissory' => 'boolean',
        'entry_register_copy' => 'boolean',
        'output_contracts' => 'boolean',
        'output_promissory' => 'boolean',
        'output_register_copy' => 'boolean',
        'able_to_exam' => 'boolean',
        'able_to_loading' => 'boolean',
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
        if ($this->multiplier > 0) {
            $grossParcel = $this->gross_value / $this->multiplier;
        } else {
            $grossParcel = 0;
        }

        return $grossParcel;
    }

    public function getNetValueAttribute()
    {
        return floatval($this->gross_value) - (floatval($this->gross_value) * floatval($this->discount_percentage)) / 100;
    }

    public function getBuyerComissionValueAttribute()
    {
        return (floatval($this->gross_value) * floatval($this->buyer_commission)) / 100;
    }

    public function getSellerComissionValueAttribute()
    {
        return (floatval($this->gross_value) * floatval($this->seller_commission)) / 100;
    }

    public function getTotalCommissionAttribute()
    {
        return ((floatval($this->gross_value) * floatval($this->buyer_commission)) / 100) + ((floatval($this->gross_value) * floatval($this->seller_commission)) / 100);
    }

    protected static function booted()
    {
        static::created(function ($order) {
            if ($order->buyer_id) {
                $user = Auth::user()?->name ?? 'Sistema';

                ClientNote::create([
                    'client_id' => $order->buyer_id,
                    'user_id' => Auth::id(),
                    'content' => "🧾 Fatura nº **{$order->number}** foi criada por **{$user}**.",
                ]);
            }
        });
    }
}
