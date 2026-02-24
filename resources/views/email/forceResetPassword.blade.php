<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background-color: #f4f4f4;
      color: #333;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 20px auto;
      background-color: #ffffff;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .header {
      background-color: #1a1a1a;
      padding: 20px;
      text-align: center;
    }

    .content {
      padding: 40px;
    }

    .alert-box {
      background-color: #fff9e6;
      border-left: 5px solid #cca43b;
      padding: 15px;
      margin: 20px 0;
    }

    .btn {
      background-color: #1a1a1a;
      color: #ffffff !important;
      padding: 12px 25px;
      text-decoration: none;
      border-radius: 4px;
      display: inline-block;
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
      <h2 style="color: #1a1a1a;">Sua senha foi redefinida!</h2>
      <p>Um administrador resetou sua senha para garantir sua segurança.</p>

      <div class="alert-box">
        <strong>Sua nova senha temporária é:</strong><br>
        <span style="font-family: monospace; font-size: 20px; color: #d4a017;">{{ $code }}</span>
      </div>

      <p>Recomendamos que você altere essa senha assim que fizer o primeiro login.</p>

      <a href="{{ $link }}" class="btn">Fazer Login Agora</a>
    </div>
    <div class="footer">
      &copy; {{ date('Y') }} {{$title}}.
    </div>
  </div>
</body>

</html>