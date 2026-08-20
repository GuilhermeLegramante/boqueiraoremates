<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recuperar senha | Boqueirão Remates</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            min-height: 100vh;
            background:
                radial-gradient(circle at top right, rgba(94, 65, 42, 0.25), transparent 35%),
                linear-gradient(135deg, #0d2b20 0%, #123d2d 55%, #0b241a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 25px;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 440px;
        }

        .card {
            background: #f8f5ef;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.35);
        }

        .header {
            background: #123d2d;
            padding: 35px 30px 30px;
            text-align: center;
            position: relative;
        }

        .header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 70px;
            height: 3px;
            background: #b58a52;
            border-radius: 10px;
        }

        .logo {
            max-width: 320px;
            max-height: 105px;
            object-fit: contain;
            margin-bottom: 18px;
        }

        .brand {
            color: #fff;
            font-size: 25px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .brand span {
            color: #c49a62;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.75);
            font-size: 13px;
            margin-top: 7px;
        }

        .content {
            padding: 35px 32px 32px;
        }

        .icon {
            width: 58px;
            height: 58px;
            margin: 0 auto 20px;
            border-radius: 50%;
            background: #e7eee9;
            color: #123d2d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
        }

        h1 {
            text-align: center;
            color: #18372b;
            font-size: 23px;
            margin-bottom: 10px;
        }

        .description {
            text-align: center;
            color: #6b6b64;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 28px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.4;
        }

        .alert-success {
            background: #e4f1e8;
            color: #1c5a3d;
            border: 1px solid #c6dfcf;
        }

        .alert-error {
            background: #f6e7e3;
            color: #8a3e32;
            border: 1px solid #e7c8c2;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            color: #39443e;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #7b6650;
            font-size: 17px;
        }

        input {
            width: 100%;
            height: 48px;
            border: 1px solid #d8d1c5;
            border-radius: 9px;
            background: #fff;
            padding: 0 14px 0 43px;
            font-size: 14px;
            color: #333;
            outline: none;
            transition: all 0.2s ease;
        }

        input:focus {
            border-color: #245c43;
            box-shadow: 0 0 0 3px rgba(36, 92, 67, 0.10);
        }

        .btn {
            width: 100%;
            height: 49px;
            border: none;
            border-radius: 9px;
            background: #174b35;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 5px 15px rgba(23, 75, 53, 0.20);
        }

        .btn:hover {
            background: #0f3928;
            transform: translateY(-1px);
            box-shadow: 0 7px 20px rgba(23, 75, 53, 0.28);
        }

        .back {
            display: block;
            text-align: center;
            margin-top: 22px;
            color: #75583d;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .back:hover {
            color: #174b35;
            text-decoration: underline;
        }

        .footer {
            text-align: center;
            margin-top: 18px;
            color: rgba(255, 255, 255, 0.55);
            font-size: 11px;
        }

        @media (max-width: 480px) {
            body {
                padding: 15px;
            }

            .content {
                padding: 30px 22px 25px;
            }

            .header {
                padding: 28px 20px 25px;
            }
        }
    </style>
</head>

<body>

    <div class="container">

        <div class="card">

            <div class="header">
                <img src="{{ asset('img/logo_completa.png') }}" class="logo">
            </div>

            <div class="content">

                <div class="icon">
                    🔑
                </div>

                <h1>Recuperar senha</h1>

                <p class="description">
                    Informe o e-mail cadastrado em sua conta.
                    Enviaremos um link para você criar uma nova senha.
                </p>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">

                    @csrf

                    <div class="form-group">

                        <label for="email">
                            E-mail
                        </label>

                        <div class="input-wrapper">

                            <span class="input-icon">✉</span>

                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                placeholder="Digite seu e-mail" required autofocus>

                        </div>

                    </div>

                    <button type="submit" class="btn">
                        Enviar link de recuperação
                    </button>

                </form>

                <a href="{{ route('login') }}" class="back">
                    ← Voltar para o login
                </a>

            </div>

        </div>

        <div class="footer">
            © {{ date('Y') }} Boqueirão Remates
        </div>

    </div>

</body>

</html>
