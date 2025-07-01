@extends('adminlte::page')
@php
    use Illuminate\Support\Facades\Auth;
@endphp
@section('title', 'Dashboard')

@section('content_header')

@stop

@section('content')
    <style>

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            border: 1px solid black;
            padding: 10px;
        }

        .icon-cell {
            width: 50px; /* Set width to fit icons */
            text-align: center;
        }

        .rect {
            width: 50px;
            height: 50px;
        }

        .wide-cell {
            width: 150px; /* Wider cells for the first 3 cells in the second row */
        }

        .small-cell {
            width: 50px; /* Smaller cells for the remaining cells */
        }
    </style>
</head>
</head>
<body>
    <table>
        <tr>
            <td colspan="9">
                <h4>First Line</h4>
            </td>
        </tr>
        <tr>
            <td class="wide-cell">
                <div class="rect">Icon 1</div>
            </td>
            <td class="wide-cell">
                <div class="rect">Icon 2</div>
            </td>
            <td class="wide-cell">
                <div class="rect">Icon 3</div>
            </td>
            <td class="small-cell"><div class="rect"></div></td>
            <td class="small-cell"><div class="rect"></div></td>
            <td class="small-cell"><div class="rect"></div></td>
            <td class="small-cell"><div class="rect"></div></td>
            <td class="small-cell"><div class="rect"></div></td>
            <td class="small-cell"><div class="rect"></div></td>
        </tr>
    </table>
</body>

</head>
   
      <body>
                        <!-- *********** LIST BODY ********* -->
                    <tbody>
                        @foreach ($parceiros as $parceiro)
                        <body>
                            <table>
                                <tr>
                                    <td colspan="9">
                                        <h2>{{ $parceiro->nome }}</h2>
                                        <h2>{{ $parceiro->nome }}</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="wide-cell">
                                        <div class="rect">
                                            <a href="#"> <img src="{{ asset('images/icons8-bell-48.png') }}" alt="Bell Icon">
                                            </a>
                                        </div>
                                    </td>
                                    <td class="wide-cell">
                                        <div class="rect">Icon 2</div>
                                    </td>
                                    <td class="wide-cell">
                                        <div class="rect">Icon 3</div>
                                    </td>
                                    <td class="small-cell">{{ $parceiro->natJur }}<div class="rect"></div></td>
                                    <td class="small-cell">{{ $parceiro->tipoCliente}}<div class="rect"></div></td>
                                    <td class="small-cell">{{ $parceiro->localidade }}<div class="rect"></div></td>
                                     <td class="small-cell"><div class="rect"></div></td>
                                    <td class="small-cell"><div class="rect"></div></td>
                                    <td class="small-cell"><div class="rect"></div></td>
                                </tr>
                            </table>
                        </body>

                       @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </body>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" > 

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

@stop

@section('js')
    <script type="text/javascript">
       // console.log('running delete...')
      // console.log('running delete...')

    </script>
    @stop