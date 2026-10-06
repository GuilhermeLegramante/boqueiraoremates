@extends('reports.layout')

@section('content')
    {{-- 1. CONTRATO - 1ª VIA --}}
    <div class="page-section">
        @include('reports.partials.contract-content', array_merge(get_defined_vars(), ['via' => 1]))
    </div>

    {{-- 2. CONTRATO - 2ª VIA --}}
    <div class="page-section page-break">
        @include('reports.partials.contract-content', array_merge(get_defined_vars(), ['via' => 2]))
    </div>

    {{-- 3. NOTA PROMISSÓRIA --}}
    <div class="page-section page-break">
        @include('reports.partials.promissory-note-content', get_defined_vars())
    </div>

    {{-- 4. REGULAMENTO --}}
    <div class="page-section page-break">
        @include('reports.partials.regulation-content', get_defined_vars())
    </div>
@endsection
