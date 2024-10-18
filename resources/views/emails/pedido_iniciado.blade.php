<!DOCTYPE html>
<html>
<head>
    <title>Pedido Iniciado</title>
</head>
<body>
    <h1>Hola, {{ $cliente->nombres }} {{ $cliente->apellidos }}</h1>
    <p>Tu pedido con el ID {{ $pedido->idPedido }} ha sido iniciado.</p>
    <p>Total: {{ $pedido->total }}</p>
    <p>Gracias por tu compra.</p>
</body>
</html>
