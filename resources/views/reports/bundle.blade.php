<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'DOCUMENTOS AGRUPADOS' }}</title>
    <style>
        /* CSS de Quebra de Página para o Dompdf */
        .page-break {
            page-break-after: always;
            break-after: page;
        }

        /* Estilo da Marca d'Água (Se for Pré-visualização) */
        .watermark {
            position: fixed;
            top: 35%;
            left: 5%;
            width: 90%;
            text-align: center;
            font-size: 52px;
            font-weight: bold;
            color: rgba(200, 0, 0, 0.18);
            text-transform: uppercase;
            transform: rotate(-35deg);
            transform-origin: center center;
            z-index: 9999;
            pointer-events: none;
            letter-spacing: 4px;
        }
    </style>
</head>

<body>

    {{-- MARCA D'ÁGUA SE FOR PRÉ-VISUALIZAÇÃO --}}
    @if (!empty($isPreview) && $isPreview)
        <div class="watermark">PRÉ-VISUALIZAÇÃO</div>
    @endif

    {{-- 1. CONTRATO - 1ª VIA --}}
    <div class="doc-section">
        @include('reports.contract', array_merge(get_defined_vars(), ['via' => 1]))
    </div>

    <div class="page-break"></div>

    {{-- 2. CONTRATO - 2ª VIA --}}
    <div class="doc-section">
        @include('reports.contract', array_merge(get_defined_vars(), ['via' => 2]))
    </div>

    <div class="page-break"></div>

    {{-- 3. NOTA PROMISSÓRIA --}}
    <div class="doc-section">
        @include('reports.promissory-note', get_defined_vars())
    </div>

    <div class="page-break"></div>

    {{-- 4. REGULAMENTO --}}
    <div class="doc-section">
        @include('reports.regulation', get_defined_vars())
    </div>

</body>

</html>
