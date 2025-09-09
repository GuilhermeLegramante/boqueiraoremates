<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>:: Boqueirão Remates - Santiago / RS ::</title>

    <link href="http://fonts.googleapis.com/css?family=Croissant+One" rel="stylesheet" type="text/css">

    <link href="Style/Fonts.css" rel="stylesheet">

    <link href="Style/StyleSheetnew.css?2" rel="stylesheet">

    <link href="Style/gallery.prefixed2.css" rel="stylesheet">

    <link href="Style/gallery.theme.css" rel="stylesheet">

    <link href="lgpd/css/estilopp.css" rel="stylesheet">

    <script src="Scripts/custom_modal/custom_modal.js"></script>

    <link href="Scripts/custom_modal/custom_modal.css" rel="stylesheet">

    <meta property="og:image " content="http://www.boqueiraoremates.com.br/imagens/topo/logo.png">

    <link rel="shortcut icon" href="http://www.boqueiraoremates.com.br/favicon.ico" type="image/icon">


</head>



<body>

    <!-- HEADER -->


    <div id="header"
        style="position: sticky;position: -webkit-sticky;top: 0;z-index: 9999;box-shadow: 1px 6px 20px black;">
        <div id="header_principal">
            <div id="header_logo">
                <img src="imagens/topo/logo.png">
            </div>
            <div class="font_7" id="header_telnovo">
                <img src="imagens/topo/telefones.png">
            </div>
            <div class="font_7" id="header_cidade">
                <img src="imagens/topo/cidade.png">
            </div>
            <div id="header_menu">
                <div id="header_menu_tabela">
                    <table height="32" width="600" border="0" cellspacing="0" cellpadding="0">
                        <tbody>
                            <tr valign="middle">
                                <td>
                                    <img name="menutoponovo" src="imagens/topo/menutoponovo.png" width="531"
                                        height="36" border="0" id="menutoponovo" usemap="#m_menutopo"
                                        alt="">
                                    <map name="m_menutopo" id="m_menutopo">
                                        <area shape="poly" coords="395,4,530,4,519,25,385,26"
                                            href="http://www.boqueiraoremates.com.br/leiloes3.php?idleilao=152&amp;tipoleilao=2#posicaoleilao"
                                            alt="">
                                        <area shape="poly" coords="254,5,388,4,377,25,244,25"
                                            href="http://www.boqueiraoremates.com.br/leiloes3.php?idleilao=153&amp;tipoleilao=1#posicaoleilao"
                                            alt="">
                                        <area shape="poly" coords="173,4,163,25,235,25,245,5" href="contato.php"
                                            alt="">
                                        <area shape="poly" coords="94,4,166,4,156,24,82,24" href="equipe.php"
                                            alt="">
                                        <area shape="poly" coords="11,4,1,25,73,26,84,5" href="cadastro.php"
                                            alt="">
                                    </map>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="header_leiloeiro">
                    <img src="imagens/topo/leiloeiro.png">
                </div>
            </div>
        </div>
    </div>
    <!-- FIM DO HEADER -->



    <!-- incio do conteudo (banners e agenda do site) -->

    <div id="content">

        <!-- mascara para banners do site -->


        <div id="mascara"></div>
        <div id="content_home">
            <div id="content_home_fundo_banner">
                <div id="content_home_banner_slider">
                    <div id="banners" class="gallery items-6" style="margin-left: 0 !important">






                        <figure class="item" style="">
                            <a
                                href="https://www.boqueiraoremates.com.br/leiloes3.php?idleilao=344&amp;tipoleilao=1#posicaoleilao"><img
                                    src="banners/016adce3447867c6f8ba773ec7118332.jpg" width="930"
                                    height="300"></a>
                        </figure>

                        <figure class="item" style="display: none;">
                            <a
                                href="https://www.boqueiraoremates.com.br/leiloes3.php?idleilao=341&amp;tipoleilao=1#posicaoleilao"><img
                                    src="banners/85879744ed0de8721c6f87da5c163fd2.png" width="930"
                                    height="300"></a>
                        </figure>

                        <figure class="item" style="display: none;">
                            <a
                                href="https://www.boqueiraoremates.com.br/leiloes3.php?idleilao=335&amp;tipoleilao=1#posicaoleilao"><img
                                    src="banners/5390b677c07d89020b4df267ec824e91.png" width="930"
                                    height="300"></a>
                        </figure>
                        <figure class="item" style="display: none;">
                            <a
                                href="https://www.boqueiraoremates.com.br/leiloes3.php?idleilao=316&amp;tipoleilao=1#posicaoleilao"><img
                                    src="banners/7094f10d1faa7ea2aae2567231cdffffjpeg" width="930"
                                    height="300"></a>
                        </figure>
                        <figure class="item" style="display: none;">
                            <a
                                href="https://www.boqueiraoremates.com.br/leiloes3.php?idleilao=341&amp;tipoleilao=1#posicaoleilao"><img
                                    src="banners/d41d8cd98f00b204e9800998ecf8427e.jpg" width="930"
                                    height="300"></a>
                        </figure>
                        <figure class="item" style="display: none;">
                            <a
                                href="https://www.boqueiraoremates.com.br/leiloes3.php?idleilao=342&amp;tipoleilao=1#posicaoleilao"><img
                                    src="banners/1756063307jpeg" width="930" height="300"></a>
                        </figure>
                    </div>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
                    <script>
                        // TEMPO EM SEGUNDOS
                        const tempo = 7;

                        $("#banners > figure:gt(0)").hide();

                        setInterval(function() {
                            $('#banners > figure:first')
                                .fadeOut(1000)
                                .next()
                                .fadeIn(1000)
                                .end()
                                .appendTo('#banners');
                        }, tempo * 1000);
                    </script>
                </div>
            </div>
        </div>
        <div id="content_home_banner"></div>



        <!-- fim mascara para banners do site -->





        <!-- inicio da agenda do site -->

        <div id="content_home_agenda">

            <div id="content_home_agenda_principal">

                <a name="posicaoleiloes"></a>

                <div style="height: 105px;">

                    <div id="footer_principal_agenda">

                        <img src="imagens/capa/proximos.png">

                    </div>

                </div>

                <table width="950" border="0" cellspacing="1" cellpadding="1">

                    <tbody>
                        <tr>

                            <td height="120" valign="middle" align="right"><a href="login.php#logar"><img
                                        src="imagens/login.png"></a><br>
                            </td>
                        </tr>

                        <tr>

                            <td align="center">

                                <!-- selecao leiloes por data da agenda ======= -->

                                <table width="950" border="0" cellspacing="0" cellpadding="0">

                                    <tbody>
                                        <tr height="5" valign="bottom">
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="23" valign="bottom">

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0" cellpadding="0"
                                                    background="imagens/madeira.png">

                                                    <tbody>
                                                        <tr>

                                                            <td width="550">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">&nbsp;&nbsp;1º Leilão da
                                                                            Página BOTA e VIBRA</span></b></font>

                                                            </td>

                                                            <td align="right">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">Data / Horário:
                                                                            30/08/2025</span></b></font>
                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1"> -
                                                                            20:00hs&nbsp;&nbsp;&nbsp;</span></b></font>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0"
                                                    class="prox_remates" cellpadding="0" bgcolor="#4D6766">

                                                    <tbody>
                                                        <tr valign="center">

                                                            <td width="200" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=341&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="adm/uploadslogolotes/6174afc878f1866e8349dc0c3f77e315.png"
                                                                        width="180" height="144"></a>

                                                            </td>

                                                            <td width="420" valign="top" align="justify">

                                                                <font color="#FFFFFF" size="3"><br><span
                                                                        class="style1">26 lotes - 50 parcelas – Éguas
                                                                        domadas, potrancas, potrancos e castrados
                                                                        domados. Em paralelo ao II Duelo da Página BOTA
                                                                        e VIBRA, no Parque do Sindicato Rural de Santo
                                                                        Antônio das Missões (RS) – Premiação de 10 Mil
                                                                        Reais – Transmissão: BOQUEIRÃO WEB.</span>
                                                                </font>

                                                            </td>

                                                            <td width="20" align="justify">&nbsp;</td>

                                                            <td width="155" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=341&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="imagens/prelance.png" width="150"
                                                                        height="144"></a>

                                                            </td>

                                                            <td width="155" valign="center" align="center">

                                                                <a href="adm/documents/8e89959bca6bd53b81fd7742222022bd.pdf"
                                                                    target="_blank"><img src="imagens/regremate.png"
                                                                        width="150" height="144"></a>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="20" valign="bottom"></tr>
                                    </tbody>
                                </table>
                                <table width="950" border="0" cellspacing="0" cellpadding="0">

                                    <tbody>
                                        <tr height="5" valign="bottom">
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="23" valign="bottom">

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0" cellpadding="0"
                                                    background="imagens/madeira.png">

                                                    <tbody>
                                                        <tr>

                                                            <td width="550">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">&nbsp;&nbsp;2º Leilão
                                                                            Cabanha COSTA VERDE MAR</span></b></font>

                                                            </td>

                                                            <td align="right">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">Data / Horário:
                                                                            06/09/2025</span></b></font>
                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1"> -
                                                                            20:30hs&nbsp;&nbsp;&nbsp;</span></b></font>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0"
                                                    class="prox_remates" cellpadding="0" bgcolor="#4D6766">

                                                    <tbody>
                                                        <tr valign="center">

                                                            <td width="200" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=342&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="adm/uploadslogolotes/d04dcf73121a60b76051d7d651cc6a37.png"
                                                                        width="180" height="144"></a>

                                                            </td>

                                                            <td width="420" valign="top" align="justify">

                                                                <font color="#FFFFFF" size="3"><br><span
                                                                        class="style1">Uma das Cabanhas mais
                                                                        tradicionais na criação de cavalo para Laço no
                                                                        estado de Santa Catarina e também no Sul do
                                                                        Brasil, fazendo seu segundo Leilão, com uma
                                                                        excelente premiação numa Prova de Laço, ainda no
                                                                        ano de 2026 – Leilão presencial em paralelo ao
                                                                        Rodeio do CTG Silva Neto, em Canelinha (SC) - 25
                                                                        Lotes – 50 Parcelas – Transmis.: BOQUEIRÃO
                                                                        WEB.</span></font>

                                                            </td>

                                                            <td width="20" align="justify">&nbsp;</td>

                                                            <td width="155" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=342&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="imagens/prelance.png" width="150"
                                                                        height="144"></a>

                                                            </td>

                                                            <td width="155" valign="center" align="center">

                                                                <a href="adm/documents/72d8b23494aced662fb51766871de0aa.pdf"
                                                                    target="_blank"><img src="imagens/regremate.png"
                                                                        width="150" height="144"></a>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="20" valign="bottom"></tr>
                                    </tbody>
                                </table>
                                <table width="950" border="0" cellspacing="0" cellpadding="0">

                                    <tbody>
                                        <tr height="5" valign="bottom">
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="23" valign="bottom">

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0" cellpadding="0"
                                                    background="imagens/madeira.png">

                                                    <tbody>
                                                        <tr>

                                                            <td width="550">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">&nbsp;&nbsp;5º Leilão
                                                                            Virtual CRIOULOS DAS COXILHAS</span></b>
                                                                </font>

                                                            </td>

                                                            <td align="right">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">Data / Horário:
                                                                            09/09/2025</span></b></font>
                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1"> -
                                                                            20:00hs&nbsp;&nbsp;&nbsp;</span></b></font>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0"
                                                    class="prox_remates" cellpadding="0" bgcolor="#4D6766">

                                                    <tbody>
                                                        <tr valign="center">

                                                            <td width="200" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=344&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="adm/uploadslogolotes/a5e0ea550ff9d066fdbd40c6bc17d16c.png"
                                                                        width="180" height="144"></a>

                                                            </td>

                                                            <td width="420" valign="top" align="justify">

                                                                <font color="#FFFFFF" size="3"><br><span
                                                                        class="style1">Uma variada oferta com
                                                                        morfologia e função que cabem em qualquer
                                                                        Criatório – 30 lotes – 50 parcelas – À venda:
                                                                        Reprodutor, Cotas de um excelente potranco,
                                                                        Éguas domadas, Éguas de cria 2 em 1 e 3 em 1,
                                                                        Potrancas, Potrancos e Castrado de montaria –
                                                                        Transmissão: PROGRAMA CAVALOS e BOQUEIRÃO
                                                                        WEB.</span></font>

                                                            </td>

                                                            <td width="20" align="justify">&nbsp;</td>

                                                            <td width="155" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=344&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="imagens/prelance.png" width="150"
                                                                        height="144"></a>

                                                            </td>

                                                            <td width="155" valign="center" align="center">

                                                                <a href="adm/documents/94d9a483e06da2a081516925acebeb62.pdf"
                                                                    target="_blank"><img src="imagens/regremate.png"
                                                                        width="150" height="144"></a>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="20" valign="bottom"></tr>
                                    </tbody>
                                </table>
                                <table width="950" border="0" cellspacing="0" cellpadding="0">

                                    <tbody>
                                        <tr height="5" valign="bottom">
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="23" valign="bottom">

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0" cellpadding="0"
                                                    background="imagens/madeira.png">

                                                    <tbody>
                                                        <tr>

                                                            <td width="550">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">&nbsp;&nbsp;Venda Permanente
                                                                            de PÔNEIS </span></b></font>

                                                            </td>

                                                            <td align="right">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">Data / Horário:
                                                                            30/09/2025</span></b></font>
                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1"> -
                                                                            21:00hs&nbsp;&nbsp;&nbsp;</span></b></font>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0"
                                                    class="prox_remates" cellpadding="0" bgcolor="#4D6766">

                                                    <tbody>
                                                        <tr valign="center">

                                                            <td width="200" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=335&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="adm/uploadslogolotes/613e40711633fa2ff005dc8757ee94bf.png"
                                                                        width="180" height="144"></a>

                                                            </td>

                                                            <td width="420" valign="top" align="justify">

                                                                <font color="#FFFFFF" size="3"><br><span
                                                                        class="style1">Venda permanente de PÔNEIS - À
                                                                        venda: Reprodutores, Machos domados, fêmeas
                                                                        domadas, machos amanunciados e fêmeas domadas.
                                                                        Todos Lotes com lance alvo. Preços e condições
                                                                        de pagamento nos comentários de cada lote ou
                                                                        direto com nossa Equipe Comercial - Consultas e
                                                                        propostas pelo fone 55.9 9733.1395</span></font>

                                                            </td>

                                                            <td width="20" align="justify">&nbsp;</td>

                                                            <td width="155" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=335&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="imagens/prelance.png" width="150"
                                                                        height="144"></a>

                                                            </td>

                                                            <td width="155" valign="center" align="center">

                                                                <a href="adm/documents/613e40711633fa2ff005dc8757ee94bf"
                                                                    target="_blank"><img src="imagens/regremate.png"
                                                                        width="150" height="144"></a>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="20" valign="bottom"></tr>
                                    </tbody>
                                </table>
                                <table width="950" border="0" cellspacing="0" cellpadding="0">

                                    <tbody>
                                        <tr height="5" valign="bottom">
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="23" valign="bottom">

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0" cellpadding="0"
                                                    background="imagens/madeira.png">

                                                    <tbody>
                                                        <tr>

                                                            <td width="550">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">&nbsp;&nbsp;GADO GERAL -
                                                                            Venda direta BOQUEIRÃO REMATES </span></b>
                                                                </font>

                                                            </td>

                                                            <td align="right">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">Data / Horário:
                                                                            31/12/2025</span></b></font>
                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1"> -
                                                                            00:00hs&nbsp;&nbsp;&nbsp;</span></b></font>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0"
                                                    class="prox_remates" cellpadding="0" bgcolor="#4D6766">

                                                    <tbody>
                                                        <tr valign="center">

                                                            <td width="200" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=152&amp;tipoleilao=2#posicaoleilao"><img
                                                                        src="adm/uploadslogolotes/38a5f5a4a582be76375ffb531aaae6fd.png"
                                                                        width="180" height="144"></a>

                                                            </td>

                                                            <td width="420" valign="top" align="justify">

                                                                <font color="#FFFFFF" size="3"><br><span
                                                                        class="style1">Venda direta GADO GERAL - Lotes
                                                                        de várias categorias - Gado apartado e revisado
                                                                        pelas equipes Boqueirão Remates e Dueto de Campo
                                                                        Assessoria Veterinária - Condições de pagamento
                                                                        nos comentários de cada lote.</span></font>

                                                            </td>

                                                            <td width="20" align="justify">&nbsp;</td>

                                                            <td width="155" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=152&amp;tipoleilao=2#posicaoleilao"><img
                                                                        src="imagens/prelance.png" width="150"
                                                                        height="144"></a>

                                                            </td>

                                                            <td width="155" valign="center" align="center">

                                                                <a href="adm/documents/86117486f438641973fdbdd80446a733"
                                                                    target="_blank"><img src="imagens/regremate.png"
                                                                        width="150" height="144"></a>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="20" valign="bottom"></tr>
                                    </tbody>
                                </table>
                                <table width="950" border="0" cellspacing="0" cellpadding="0">

                                    <tbody>
                                        <tr height="5" valign="bottom">
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="23" valign="bottom">

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0" cellpadding="0"
                                                    background="imagens/madeira.png">

                                                    <tbody>
                                                        <tr>

                                                            <td width="550">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">&nbsp;&nbsp;EQUINOS - Venda
                                                                            direta BOQUEIRÃO REMATES - 2025</span></b>
                                                                </font>

                                                            </td>

                                                            <td align="right">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">Data / Horário:
                                                                            31/12/2025</span></b></font>
                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1"> -
                                                                            23:59hs&nbsp;&nbsp;&nbsp;</span></b></font>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0"
                                                    class="prox_remates" cellpadding="0" bgcolor="#4D6766">

                                                    <tbody>
                                                        <tr valign="center">

                                                            <td width="200" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=308&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="adm/uploadslogolotes/28c2707af31968647e8a033a19365269.png"
                                                                        width="180" height="144"></a>

                                                            </td>

                                                            <td width="420" valign="top" align="justify">

                                                                <font color="#FFFFFF" size="3"><br><span
                                                                        class="style1">Venda direta de EQUINOS - À
                                                                        venda: Reprodutores (Garanhões e Cotas e/ou
                                                                        direito de uso) Éguas domadas, Éguas de cria,
                                                                        potrancas, potrancos e castrados domados. Todos
                                                                        Lotes com lance alvo. Preços e condições de
                                                                        pagamento nos comentários de cada lote ou direto
                                                                        com nossa Equipe Comercial - Consultas e
                                                                        propostas pelo fone 55.9 9733.1395</span></font>

                                                            </td>

                                                            <td width="20" align="justify">&nbsp;</td>

                                                            <td width="155" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=308&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="imagens/prelance.png" width="150"
                                                                        height="144"></a>

                                                            </td>

                                                            <td width="155" valign="center" align="center">

                                                                <a href="adm/documents/0882a43606d9e38df0a7dcfdb0747121"
                                                                    target="_blank"><img src="imagens/regremate.png"
                                                                        width="150" height="144"></a>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="20" valign="bottom"></tr>
                                    </tbody>
                                </table>
                                <table width="950" border="0" cellspacing="0" cellpadding="0">

                                    <tbody>
                                        <tr height="5" valign="bottom">
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="23" valign="bottom">

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0" cellpadding="0"
                                                    background="imagens/madeira.png">

                                                    <tbody>
                                                        <tr>

                                                            <td width="550">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">&nbsp;&nbsp;Venda Permanente
                                                                            de COBERTURAS - Ciclo 2025/2026</span></b>
                                                                </font>

                                                            </td>

                                                            <td align="right">

                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1">Data / Horário:
                                                                            31/12/2025</span></b></font>
                                                                <font color="#FFFFFF" size="4"><b><span
                                                                            class="style1"> -
                                                                            23:59hs&nbsp;&nbsp;&nbsp;</span></b></font>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>

                                            <td colspan="4">

                                                <table width="950" border="0" cellspacing="0"
                                                    class="prox_remates" cellpadding="0" bgcolor="#4D6766">

                                                    <tbody>
                                                        <tr valign="center">

                                                            <td width="200" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=316&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="adm/uploadslogolotes/b5e7350a89afdfbdff0b4bbac2e40790jpeg"
                                                                        width="180" height="144"></a>

                                                            </td>

                                                            <td width="420" valign="top" align="justify">

                                                                <font color="#FFFFFF" size="3"><br><span
                                                                        class="style1">VENDA PERMANENTE DE COBERTURAS –
                                                                        Raça Crioula – TODOS LOTES COM LANCE ALVO –
                                                                        Coberturas de Pais comprovados – Ciclo 20252026
                                                                        – Uma variada oferta de coberturas com enorme
                                                                        consistência genética.</span></font>

                                                            </td>

                                                            <td width="20" align="justify">&nbsp;</td>

                                                            <td width="155" valign="center" align="center">

                                                                <a
                                                                    href="leiloes3.php?idleilao=316&amp;tipoleilao=1#posicaoleilao"><img
                                                                        src="imagens/prelance.png" width="150"
                                                                        height="144"></a>

                                                            </td>

                                                            <td width="155" valign="center" align="center">

                                                                <a href="adm/documents/85bf6f7df48c016b63750b890aa8ccc6.pdf"
                                                                    target="_blank"><img src="imagens/regremate.png"
                                                                        width="150" height="144"></a>

                                                            </td>

                                                        </tr>

                                                    </tbody>
                                                </table>

                                            </td>

                                        </tr>

                                        <tr>
                                            <td colspan="4"></td>
                                        </tr>

                                        <tr height="20" valign="bottom"></tr>
                                    </tbody>
                                </table>
                                <!-- fim da seleção leiloes por data da agenda ======= -->

                            </td>
                        </tr>
                        <tr>

                            <td>&nbsp;</td>

                        </tr>

                    </tbody>
                </table>

                <div style="height: 200px;">&nbsp;</div>

            </div>

        </div>

        <!-- fim da agenda do site -->

    </div>

    <!-- fim do conteudo (banners e agenda do site) -->





    <!-- FOOTER -->


    <div id="footer">
        <div id="footer_principal">
            <div style="height: 220px;">
                <div id="footer_principal_logo_br">
                    <img src="imagens/footer/logobr.png">
                </div>
                <div id="footer_principal_contatos">
                    <img src="imagens/footer/contatos.png">
                </div>
                <div id="footer_principal_cbrum">
                    <img src="imagens/footer/logocbrum.png">
                </div>
            </div>
        </div>
    </div>
    <!-- FIM DO FOOTER -->

    <!-- LGPD -->

    <div class="cookies-msg" id="cookies-msg">
        <div class="cookies-txt">
            <p>Ao continuar navegando no site, você concorda com os nossos <a href="lgpd/termoscondicoesuso.html"
                    target="_blank">termos de uso</a>, <a href="lgpd/politicaprivacidade.html"
                    target="_blank">política de privacidade</a> e condições e armazenamento de cookies em seu
                dispositivo para aprimorar a navegação do site, analisar o uso do site e auxiliar em nossos esforços de
                marketing.</p>
            <div class="saibamais"><a href="lgpd/politicacookies.html" target="_blank">Saiba mais</a></div>
            <div class="cookies-btn">
                <button onclick="aceito()">Aceito</button>
            </div>
        </div>
    </div>
    <script src="lgpd/js/cookies_boqueirao.js"></script>

    <!-- LGPD -->

</body>

</html>
