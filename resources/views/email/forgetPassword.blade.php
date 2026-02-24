<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #1a1a1a;
      color: #e5e5e5;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 20px auto;
      background-color: #262626;
      border: 1px solid #333;
      border-radius: 8px;
      overflow: hidden;
    }

    .header {
      background-color: #cca43b;
      padding: 30px;
      text-align: center;
    }

    .header h1 {
      color: #1a1a1a;
      margin: 0;
      font-size: 24px;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .content {
      padding: 40px;
      text-align: center;
    }

    .code-box {
      background-color: #1a1a1a;
      border: 2px dashed #cca43b;
      color: #cca43b;
      font-size: 32px;
      font-weight: bold;
      padding: 20px;
      margin: 25px 0;
      display: inline-block;
      letter-spacing: 5px;
      border-radius: 4px;
    }

    .footer {
      padding: 20px;
      text-align: center;
      font-size: 12px;
      color: #777;
      background-color: #1f1f1f;
    }

    .btn {
      background-color: #cca43b;
      color: #1a1a1a;
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 4px;
      font-weight: bold;
      display: inline-block;
      margin-top: 20px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      @if(isset($image_path) && file_exists($image_path))
      <img src="{{ $message->embed($image_path) }}" alt="{{ $name }}" style="max-width: 150px; display: block; margin: 0 auto 10px auto;">
      @endif
      <h1>{{ $name }}</h1>
    </div>
    <div class="content">
      <h2>Recuperação de Acesso</h2>
      <p>Olá! Você solicitou a redefinição de sua senha. Use o código abaixo para validar o seu acesso:</p>

      <div class="code-box">{{ $code }}</div>

      <p>Se você não solicitou essa alteração, basta ignorar este e-mail.</p>

      @if($link != 'URL NÃO ENCONTRADA')
      <a href="{{ $link }}" class="btn">Voltar para o Sistema</a>
      @endif
    </div>
    <div class="footer">
      &copy; {{ date('Y') }} {{$title}}.
    </div>
  </div>
</body>

</html>