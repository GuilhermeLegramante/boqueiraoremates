<header style="position: fixed; margin-top: -15%;">
    <div style="height: 100px;">
        <table style="width: 120%;">
            <tbody>
                <tr>
                    <td style="height: 100px; vertical-align: middle; width: 10%;">
                        <img src="{{ asset('img/logo.png') }}" style="width: 100px; height: 100px;">
                    </td>
                    <td style="height: 64px; width: 50%;">  
                        <table>
                            <tbody>
                                <tr>
                                    <td style="font-size: 23px; font-weight: bold;">{{ $title }}</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 17px; font-weight: bold;">Boqueirão Remates e Negócios Rurais</td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; padding-top: 10px;">
                                        {{-- <span style="font-weight: bold;">CNPJ:</span> 00.000.000/0001-00 --}}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px;">
                                        <span style="font-weight: bold;"></span> www.boqueiraoremates.com.br
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="height: 64px; text-align: right;">
                        <table style="width: 100%">
                            <tbody>
                                <tr>
                                    <td style="height: 50px; text-align: right;">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{url()->current()}}"
                                            alt="qrCode" style="width: 60px; height: 60px;">
                                    </td>
                                </tr>
                                <tr>
                                    <td style="font-size: 12px; height: 15px; text-align: right; vertical-align: top;">
                                        <span style="font-weight: bold;"></span> contato@boqueiraoremates.com.br
                                    </td>
                                </tr>
                                <tr>
                                    <td
                                        style="font-size: 12px; height: 10px; text-align: right; vertical-align: bottom;">
                                        <span style="font-weight: bold;">Emitido em:</span> {{ date('d/m/Y \à\s H:i:s') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <hr style="margin-top: 5px; width: 120%;" />
</header>