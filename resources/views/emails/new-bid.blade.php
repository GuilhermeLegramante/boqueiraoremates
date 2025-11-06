<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Lance Recebido</title>
    <style>
        body {
            font-family: "Helvetica Neue", Arial, sans-serif;
            background-color: #f5f7fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background-color: #1a2e40;
            color: #f8d14e;
            text-align: center;
            padding: 30px 20px;
        }

        .header img {
            width: 60px;
            height: auto;
            margin-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .content {
            padding: 30px;
            color: #333;
            line-height: 1.6;
        }

        .content h2 {
            color: #1a2e40;
            font-size: 20px;
            margin-bottom: 15px;
        }

        .content p {
            margin: 8px 0;
        }

        .highlight {
            color: #1a2e40;
            font-weight: 600;
        }

        .footer {
            background-color: #f0f2f5;
            text-align: center;
            font-size: 12px;
            color: #777;
            padding: 15px;
        }

        /* 🔹 Botão com contraste aprimorado */
        .button {
            display: inline-block;
            background-color: #f8d14e;
            color: #1a2e40;
            padding: 12px 24px;
            margin-top: 20px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 700;
            letter-spacing: 0.3px;
            transition: background-color 0.2s, color 0.2s;
        }

        .button:hover {
            background-color: #e6be3a;
            color: #0e1a27;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🐎 Novo Lance Recebido!</h1>
        </div>

        <div class="content">
            <h2>Detalhes do Lance</h2>

            <p><span class="highlight">Usuário:</span> {{ $user->name }}</p>
            <p><span class="highlight">E-mail:</span> {{ $user->email }}</p>

            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">

            <p><span class="highlight">Evento:</span> {{ $event->name }}</p>
            <p><span class="highlight">Animal:</span> {{ $animal->name }}</p>
            <p><span class="highlight">Valor do Lance:</span>
                <strong>R$ {{ number_format($amount, 2, ',', '.') }}</strong>
            </p>

            <a href="{{ url('/admin') }}" class="button">🔍 Ver no Painel Administrativo</a>
        </div>

        <div class="footer">
            <p>Este é um e-mail automático do sistema de leilões <strong>Boqueirão Remates</strong>.</p>
            <p>Não é necessário responder.</p>
        </div>
    </div>
</body>

</html>
