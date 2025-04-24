<div class='section'>
    <table class="parcels-table">
        <thead>
            <tr class="">
                <th>
                    <strong>
                        Ord.
                    </strong>
                </th>
                <th>
                    <strong>
                        Dt do Venc.
                    </strong>
                </th>
                <th>
                    <strong>
                        Valor
                    </strong>
                </th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 0; $i < 15; $i++)
                <tr>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ $order->parcels->toArray()[$i]['number'] }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ date('d/m/Y', strtotime($order->parcels->toArray()[$i]['date'])) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td style="text-align: right; white-space: nowrap; padding: 0; height: 10px;">
                        @if (isset($order->parcels->toArray()[$i]))
                            <table style="width: 100%; margin: 0; padding: 0;">
                                <tr>
                                    <td style="text-align: left; width: auto; padding: 0; margin: 0; height: 10px;">R$
                                    </td>
                                    <td style="text-align: right; padding: 0; margin: 0; height: 10px;">
                                        {{ number_format($order->parcels->toArray()[$i]['value'] ?? 0, 2, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>

<div class='section'>
    <table class="parcels-table">
        <thead>
            <tr class="">
                <th>
                    <strong>
                        Ord.
                    </strong>
                </th>
                <th>
                    <strong>
                        Dt do Venc.
                    </strong>
                </th>
                <th>
                    <strong>
                        Valor
                    </strong>
                </th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 15; $i < 30; $i++)
                <tr>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ $order->parcels->toArray()[$i]['number'] }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ date('d/m/Y', strtotime($order->parcels->toArray()[$i]['date'])) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ 'R$ ' . number_format($order->parcels->toArray()[$i]['value'], 2, ',', '.') }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>

<div class='section'>
    <table class="parcels-table">
        <thead>
            <tr class="">
                <th>
                    <strong>
                        Ord.
                    </strong>
                </th>
                <th>
                    <strong>
                        Dt do Venc.
                    </strong>
                </th>
                <th>
                    <strong>
                        Valor
                    </strong>
                </th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 30; $i < 45; $i++)
                <tr>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ $order->parcels->toArray()[$i]['number'] }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ date('d/m/Y', strtotime($order->parcels->toArray()[$i]['date'])) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ 'R$ ' . number_format($order->parcels->toArray()[$i]['value'], 2, ',', '.') }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>

<div class='section'>
    <table class="parcels-table">
        <thead>
            <tr class="">
                <th>
                    <strong>
                        Ord.
                    </strong>
                </th>
                <th>
                    <strong>
                        Dt do Venc.
                    </strong>
                </th>
                <th>
                    <strong>
                        Valor
                    </strong>
                </th>
            </tr>
        </thead>
        <tbody>
            @for ($i = 45; $i < 60; $i++)
                <tr>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ $order->parcels->toArray()[$i]['number'] }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ date('d/m/Y', strtotime($order->parcels->toArray()[$i]['date'])) }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                    <td>
                        @if (isset($order->parcels->toArray()[$i]))
                            {{ 'R$ ' . number_format($order->parcels->toArray()[$i]['value'], 2, ',', '.') }}
                        @else
                            &nbsp;
                        @endif
                    </td>
                </tr>
            @endfor
        </tbody>
    </table>
</div>
