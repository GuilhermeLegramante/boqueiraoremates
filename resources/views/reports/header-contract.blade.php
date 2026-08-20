<table style="width: 100%; border-collapse: collapse;">
    <tr>
        {{-- LOGO DO EVENTO --}}
        <td style="width: 35%; text-align: left; vertical-align: top;">
            @if (!empty($event?->banner))
                <img src="{{ public_path('storage/' . $event->banner) }}" style="max-width: 180px; max-height: 65px;">
            @endif
        </td>

        {{-- COMPRADOR / CIDADE / TÍTULO --}}
        <td style="width: 30%; text-align: center; vertical-align: top;">
            <div
                style="
                font-size: 14px;
                font-weight: bold;
                text-transform: uppercase;
                margin-bottom: 2px;
            ">
                {{ $buyer->name ?? '' }}
            </div>

            <div style="
                font-size: 10px;
                margin-bottom: 7px;
            ">
                Uruguaiana - RS
            </div>

            <div
                style="
                font-size: 11px;
                font-weight: bold;
                line-height: 1.25;
            ">
                Nota de Leilão e Contrato de Compra
                com Reserva de Domínio
            </div>
        </td>

        {{-- LOGO BOQUEIRÃO --}}
        <td style="width: 35%; text-align: right; vertical-align: top;">
            <img src="{{ public_path('img/logo completa.png') }}" style="max-width: 180px; max-height: 65px;">
        </td>
    </tr>
</table>

<div style="height: 10px;"></div>
