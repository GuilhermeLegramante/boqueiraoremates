<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema em Manutenção | Boqueirão Remates</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e293b, #0f172a);
            color: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 2rem;
        }
        .container {
            max-width: 600px;
            background: rgba(15, 23, 42, 0.85);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: 0 8px 25px rgba(0,0,0,0.4);
        }
        h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
            font-weight: 800;
            color: #facc15;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            line-height: 1.6;
        }
        .signature {
            margin-top: 2rem;
            font-style: italic;
            font-size: 1rem;
            color: #94a3b8;
        }
        .logo {
            margin-bottom: 1.5rem;
        }
        .logo img {
            max-width: 180px;
        }
        .whatsapp {
            margin-top: 1rem;
        }
        .whatsapp a {
            display: inline-block;
            margin-top: 0.5rem;
            padding: 0.6rem 1.2rem;
            border-radius: 0.6rem;
            background: #25D366;
            color: #fff;
            font-weight: 600;
            text-decoration: none;
            transition: background 0.3s;
        }
        .whatsapp a:hover {
            background: #1ebe57;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            {{-- Se tiver logo, descomente e ajuste o caminho --}}
            {{-- <img src="{{ asset('images/boqueirao-logo.png') }}" alt="Boqueirão Remates"> --}}
        </div>
        <h1>503</h1>
        <p><strong>O sistema da Boqueirão Remates está em manutenção.</strong></p>
        <p>Estamos realizando melhorias para oferecer um serviço ainda melhor.<br>
           Por favor, volte em alguns instantes.</p>
        <div class="signature">
            Mensagem do suporte,<br>
            <strong>Guilherme Legramante</strong>
        </div>
        <div class="whatsapp">
            📲 Precisa falar comigo?<br>
            <a href="https://wa.me/5555999181805" target="_blank">
                Falar no WhatsApp
            </a>
        </div>
    </div>
</body>
</html>
