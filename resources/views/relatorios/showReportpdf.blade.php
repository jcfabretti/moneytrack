@extends('adminlte::page')
@section('title', 'Dashboard')

@section('content_header')
    </head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Relatório</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
            /* Evita barras de rolagem desnecessárias no corpo */
        }

        .pdf-container {
            width: 100%;
            height: calc(100vh - 60px);
            /* Ajuste a altura para o cabeçalho, se tiver */
            border: none;
        }

        .header {
            padding: 10px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #e9ecef;
            text-align: center;
        }
    </style>
    </head>
@stop

@section('content')

    <body>

    <script>
        window.open("{{ $pdfUrl }}", "_blank");
    </script>


        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

@stop

@section('css')

@stop

@section('js')
    <script type="text/javascript"></script>
@stop
