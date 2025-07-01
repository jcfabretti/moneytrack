<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
   
    <link href="/css/treeview.css" rel="stylesheet">
    <title>Document</title>
</head>

<body>
    <div class="row">
        <div class="col-md-6">
            <h3>Category List</h3>
            <ul id="tree1">
                <pre>{{ print_r($categorias->toArray(), true) }}</pre>
                @foreach($categories as $category)
                <li>
                    <i class="fa fa-plus-circle"></i>
                    {{ $category->nome }}
                    @if(count($category->children))
                    @include('planoconta.manageChild',['children' => $category->children])
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
<script src="/js/treeview.js"></script>
</html>