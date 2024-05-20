<header style="position: fixed; margin-top: -17%;">
    <div style="height: 100px;">
        <table style="width: 120%;">
            <tbody>
                <tr>
                    <td style="height: 100px; vertical-align: middle; width: 10%;">
                        <img src="{{ asset('img/logo.png') }}" style="width: 60px; height: 60px;">
                    </td>
                    <td style="height: 64px; width: 50%;">
                        <table>
                            <tbody>
                                <tr>
                                    <td style="font-size: 15px;">
                                        <strong>
                                            {{ $title }}
                                        </strong>
                                    </td>
                                    <td style="font-size: 12px;">
                                        OS N°:
                                    </td>
                                    <td style="font-size: 15px; border: 2px solid black; text-align:center;">
                                        <strong>
                                            {{ str_pad($order->number, 4, '0', STR_PAD_LEFT) }}
                                        </strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 11px; font-weight: bold;">Boqueirão Remates e Negócios Rurais
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 8px;">
                                        <span style="font-weight: bold;"></span> www.boqueiraoremates.com.br
                                    </td>
                            </tbody>
                        </table>
                    </td>
                    <td style="height: 64px; text-align: right;">
                        <table style="width: 160%">
                            <tbody>
                                <tr>
                                    <td style="height: 50px; text-align: right;">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url()->current() }}"
                                            alt="qrCode" style="width: 45px; height: 45px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 8px; height: 15px; text-align: right; vertical-align: top;">
                                        <span style="font-weight: bold;"></span> contato@boqueiraoremates.com.br
                                    </td>
                                </tr>
                                {{-- <tr>
                                    <td
                                        style="font-size: 8px; height: 10px; text-align: right; vertical-align: bottom;">
                                        <span style="font-weight: bold;">Impresso em:</span>
                                        {{ date('d/m/Y \à\s H:i:s') }}
                                    </td>
                                </tr> --}}
                                <tr>
                                    <td
                                        style="font-size: 8px; height: 10px; text-align: right; vertical-align: bottom;">
                                        <span style="font-weight: bold;">Emitido em:</span>
                                        {{ $order->created_at->format('d/m/Y \à\s H:i:s') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="font-size: 8px; height: 10px; text-align: right; vertical-align: bottom;">
                                        <span style="font-weight: bold;">Data da Negociação:</span>
                                        @if (isset($order->base_date))
                                            {{ date('d/m/Y', strtotime($order->base_date)) }}
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr style="margin-top: 5px; width: 147%;" />
</header>
