<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <!-- <link rel="icon" href="<%= BASE_URL %>favicon.ico"> -->

    <title>{{ config('app.name') }}</title>
    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
</head>
<body>
<div id="app">
</div>
<!-- <script src="js/app.js"></script> -->
<script src="{{ asset(mix('js/app.js')) }}"></script>

</body>
</html>
