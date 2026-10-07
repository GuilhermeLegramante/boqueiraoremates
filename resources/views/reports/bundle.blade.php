@extends('reports.layout')

@section('content')
    @php
        $docs = $selectedDocs ?? ['via1', 'via2', 'promissory', 'seller_promissory', 'buyer_promissory', 'regulation'];
    @endphp

    @if (in_array('via1', $docs))
        <div class="page-section">
            @include('reports.partials.contract-content', ['via' => 1])
        </div>
    @endif

    @if (in_array('via2', $docs))
        <div class="page-break"></div>
        <div class="page-section">
            @include('reports.partials.contract-content', ['via' => 2])
        </div>
    @endif

    {{-- NOTA PROMISSÓRIA ÚNICA --}}
    @if (in_array('promissory', $docs) && count($parcels ?? []) > 0)
        <div class="page-break"></div>
        <div class="page-section">
            @include('reports.partials.promissory-note-content', [
                'promissoryTitle' => 'NOTA PROMISSÓRIA - ÚNICA',
                'activeParcels' => $parcels,
            ])
        </div>
    @endif

    {{-- NOTA PROMISSÓRIA - FATURAMENTO VENDEDOR --}}
    @if (in_array('seller_promissory', $docs) && count($sellerParcels ?? []) > 0)
        <div class="page-break"></div>
        <div class="page-section">
            @include('reports.partials.promissory-note-billing-content', [
                'promissoryTitle' => 'NOTA PROMISSÓRIA - FATURAMENTO VENDEDOR',
                'payer' => $seller,
                'activeParcels' => $sellerParcels,
            ])
        </div>
    @endif

    {{-- NOTA PROMISSÓRIA - FATURAMENTO COMPRADOR --}}
    @if (in_array('buyer_promissory', $docs) && count($buyerParcels ?? []) > 0)
        <div class="page-break"></div>
        <div class="page-section">
            @include('reports.partials.promissory-note-billing-content', [
                'promissoryTitle' => 'NOTA PROMISSÓRIA - FATURAMENTO COMPRADOR',
                'payer' => $buyer,
                'activeParcels' => $buyerParcels,
            ])
        </div>
    @endif

    @if (in_array('regulation', $docs))
        <div class="page-break"></div>
        <div class="page-section">
            @include('reports.partials.regulation-content')
        </div>
    @endif
@endsection
