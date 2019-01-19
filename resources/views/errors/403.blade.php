<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Cotontchad</title>

    <link rel="stylesheet" href="{{asset('css/app.css')}}">

    <link rel="stylesheet" href="{{asset('css/style.default.css')}}" id="theme-stylesheet">


</head>
<body style="background: #eee">
 <div class="container content-error">
        <h1>404</h1>
        <p>Oops! Page inexistante </p>
        <a href="{{ URL::previous() }}" class="btn btn-primary">Retourner</a>
 </div>

</body>
</html>
