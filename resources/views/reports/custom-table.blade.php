@php
    $data = $getData();
    $hasHeadings = $hasHeadings();
    $headings = $getHeadings();
    $isFirstColumnUsedAsHeadings = $isFirstColumnUsedAsHeadings();
    $columns = $getColumns();
    $hasColumns = $hasColumns();
    $hasSummary = $hasSummary();

    $client = $getClient();

@endphp
<x-filament-reports::table.index class="border-t-4 border-primary-500"
    style="width: 100%;
border-bottom: 1px solid rgb(210, 210, 210);">

    <br><br>

    <h1 style="margin-left: 1%; font-size: 23px;">{{ $client->name }}</h1>

</x-filament-reports::table.index>
