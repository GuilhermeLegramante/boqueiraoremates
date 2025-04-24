<td style="text-align: right; white-space: nowrap; padding: 0; height: 10px; {{ isset($td_css) ? $td_css : '' }}">
    <table style="width: 100%; margin: 0; padding: 0;">
        <tr>
            <td style="text-align: left; width: auto; padding: 0; margin: 0; height: 10px;">R$</td>
            <td style="text-align: right; padding: 0; margin: 0; height: 10px;">
                {{ number_format($money_value ?? 0, 2, ',', '.') }}
            </td>
        </tr>
    </table>
</td>
