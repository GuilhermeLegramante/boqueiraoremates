<header style="position: fixed; top: 0; left: 0; width: 100%; margin-top: -1%;">
    <table style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 15%; text-align: left; vertical-align: middle;">
                <img src="{{ asset('img/logo.png') }}" style="width: 100px; height: 100px;">
            </td>

            <td style="width: 70%; text-align: center;">
                <span style="font-size: 19px; font-weight: bold;">{{ $title }}</span><br>
                <span style="font-size: 15px; font-weight: bold;">{{ $event->name }}</span><br>
                <span style="font-size: 10px; display: block; margin-top: 5px;">Boqueirão Remates e Negócios
                    Rurais</span>
            </td>

            <td style="width: 15%; text-align: right; vertical-align: middle;">
                {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ url()->current() }}"
                    alt="QR Code" style="width: 60px; height: 60px;"><br> --}}
                <span style="font-size: 8px; display: block; margin-top: 5px;">
                    <strong>Emitido em:</strong> {{ date('d/m/Y \à\s H:i:s') }}
                </span>
            </td>
        </tr>
    </table>
    <hr style="margin-top: 10px; width: 100%; border: 1px solid #000;" />
</header>
