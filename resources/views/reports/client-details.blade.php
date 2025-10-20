@extends('reports.page')

@section('header')
    @include('reports.header')
@endsection

@section('content')
    <h1 style="margin-left: 1%; font-size: 23px;">{{ $client->name }}</h1>
    <p style="margin-left: 1%; margin-top: -1%"><strong>Cadastrado em:
        </strong>{{ date('d/m/Y', strtotime($client->created_at)) }} <strong> Atualizado em:
        </strong>{{ date('d/m/Y \à\s H:i:s', strtotime($client->updated_at)) }}</p>
    <br>
    <h1 style="margin-left: 1%;">Informações Pessoais</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Nome:</strong> {{ $client->name }}</td>
                <td class="collumn-right"><strong>E-mail:</strong> {{ $client->email }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Data de Nascimento:</strong>
                    @if (isset($client->birth_date))
                        {{ date('d/m/Y', strtotime($client->birth_date)) }}
                    @endif
                </td>
                <td class="collumn-right"><strong>Gênero:</strong>
                    @if ($client->gender == 'male')
                        MASCULINO
                    @endif
                    @if ($client->gender == 'female')
                        FEMININO
                    @endif
                </td>
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Profissão:</strong> {{ $client->occupation }}</td>
                <td class="collumn-right"><strong>Observação:</strong> {{ $client->note_occupation }}</td>
            </tr>

            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Estabelecimento:</strong> {{ $client->establishment }}</td>
                <td class="collumn-right"><strong>Renda:</strong> {{ 'R$ ' . number_format($client->income, 2, ',', '.') }}
                </td>
                {{-- <td class="collumn-right" style="text-align: right; white-space: nowrap; padding: 0; height: 10px;">
                    <table style="width: 100%; margin: 0; padding: 0;">
                        <tr>
                            <strong>Renda:</strong>
                            <td style="text-align: left; width: auto; padding: 0; margin: 0; height: 10px;">R$</td>
                            <td style="text-align: right; padding: 0; margin: 0; height: 10px;">
                                {{ number_format($client->income ?? 0, 2, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                </td> --}}
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>CPF ou CNPJ:</strong> {{ $client->cpf_cnpj }}</td>
                <td class="collumn-right"><strong>RG:</strong> {{ $client->rg }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Inscrição Estadual:</strong> {{ $client->inscricaoestadual }}</td>
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Nome da Mãe:</strong> {{ $client->mother }}</td>
                <td class="collumn-right"><strong>Nome do Pai:</strong> {{ $client->father }}</td>
            </tr>
        </tbody>
    </table>


    <br>
    <h1 style="margin-left: 1%;">Contato</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Whatsapp:</strong> {{ $client->whatsapp }}</td>
                <td class="collumn-right"><strong>Celular:</strong> {{ $client->cel_phone }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Telefone Comercial:</strong> {{ $client->business_phone }}</td>
                <td class="collumn-right"><strong>Telefone Residencial:</strong> {{ $client->home_phone }}</td>
            </tr>
        </tbody>
    </table>

    <br>
    <h1 style="margin-left: 1%;">Endereço</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>CEP:</strong> {{ $client->address->postal_code }}</td>
                <td class="collumn-right"><strong>Logradouro (Rua, Av.):</strong> {{ $client->address->street }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Nº:</strong> {{ $client->address->number }}</td>
                <td class="collumn-right"><strong>Complemento:</strong> {{ $client->address->complement }}</td>
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Referência:</strong> {{ $client->address->reference }}</td>
                <td class="collumn-right"><strong>Bairro:</strong> {{ $client->address->district }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Cidade:</strong> {{ $client->address->city }}</td>
                <td class="collumn-right"><strong>Estado:</strong> {{ $client->address->state }}</td>
            </tr>
        </tbody>
    </table>

    <br>
    <h1 style="margin-left: 1%;">Informações Bancárias</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Banco:</strong>
                    @if (isset($client->bank))
                        {{ $client->bank->name }}
                    @endif
                </td>
                <td class="collumn-right"><strong>Agência:</strong> {{ $client->bank_agency }}</td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Conta:</strong> {{ $client->current_account }}</td>
                <td class="collumn-right"></td>
            </tr>
        </tbody>
    </table>

    <br>
    <h1 style="margin-left: 1%;">Informações Adicionais</h1>
    <table>
        <tbody>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Situação:</strong>
                    @if ($client->situation == 'able')
                        HABIITADO
                    @endif
                    @if ($client->situation == 'disabled')
                        INABILITADO
                    @endif
                    @if ($client->situation == 'inactive')
                        INATIVO
                    @endif
                </td>
                <td class="collumn-right"></td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Canal de Inclusão:</strong>
                    @if ($client->register_origin == 'marketing')
                        DIVULGAÇÃO
                    @endif
                    @if ($client->register_origin == 'local')
                        RECINTO
                    @endif
                    @if ($client->register_origin == 'site')
                        SITE
                    @endif
                </td>
                <td class="collumn-right"></td>
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Perfil:</strong>
                    @if ($client->profile == 'purchase')
                        COMPRA
                    @endif
                    @if ($client->profile == 'sale')
                        VENDA
                    @endif
                    @if ($client->profile == 'both')
                        COMPRA E VENDA
                    @endif
                </td>
                <td class="collumn-right"></td>
            </tr>
            <tr class="" style="font-size: 13px;">
                <td class="collumn-left"><strong>Cadastro em Leiloeira:</strong>
                    {{ $client->has_register_in_another_auctioneer == '1' ? 'SIM' : 'NÃO' }}</td>
                <td class="collumn-right"><strong>Leiloeira(s):</strong> {{ $client->auctioneer }}</td>
            </tr>
            <tr class="bg-light" style="font-size: 13px;">
                <td class="collumn-left"><strong>Estabelecimento:</strong> {{ $client->estabelecimento }}</td>
                <td class="collumn-right">
                    <strong>Perfil: </strong>
                    @switch($client->perfil)
                        @case('C')
                            COMPRA
                        @break

                        @case('V')
                            VENDA
                        @break

                        @case('CV')
                            COMPRA E VENDA
                        @endswitch
                    </td>
                </tr>
                {{-- Seção de Anotações --}}
                <tr style="font-size: 13px;">
                    <td class="collumn-left" colspan="2" style="padding-top: 10px;">
                        <strong>Anotações:</strong>
                        <div
                            style="margin-top: 6px; border: 1px solid #ccc; border-radius: 6px; padding: 8px; background: #f9f9f9;">
                            @if ($client->notes->isEmpty())
                                <em>Sem anotações registradas.</em>
                            @else
                                <table style="width: 100%; border-collapse: collapse;">
                                    <tbody>
                                        @foreach ($client->notes as $note)
                                            <tr style="border-bottom: 1px solid #e0e0e0;">
                                                <td style="padding: 6px 4px;">
                                                    <div style="font-size: 12px; line-height: 1.4;">
                                                        {{ $note->content }}
                                                    </div>
                                                    <div style="font-size: 11px; color: #666; margin-top: 2px;">
                                                        — {{ optional($note->user)->name ?? 'Sistema' }},
                                                        {{ $note->created_at->format('d/m/Y H:i') }}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    @endsection

    @section('footer')
        @include('reports.footer')
    @endsection
