<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Comprobante de Pedido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            width: 90%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
        }
        .total {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¡Gracias por tu compra!</h1>
        <p>Hola, <strong>{{ $cliente->nombres }} {{ $cliente->apellidos }}</strong>,</p>
        <p>Nos complace informarte que tu pedido con el ID <strong>{{ $pedido->idPedido }}</strong> ha sido iniciado exitosamente.</p>
        <p class="total">Total de tu pedido: Q. {{ $pedido->total }} </p>
        <p>Pronto estaremos procesando tu pedido. Si tienes alguna duda, no dudes en contactarnos.</p>
        <div class="footer">
            <p>Paper Geometry - ¡Armamos Buenos Momentos!</p>
            <p>https//:papergeometry.site</p>
        </div>
    </div>
</body>
</html>
