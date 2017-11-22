<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>callback - adavalue</title>

    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="css/app.css" rel="stylesheet" type="text/css">

</head>
<body>
<div class="container">
    <div class="row">
        <div class="col">

            <div class="lead">
                <h1>resource owner</h1>
                <pre>id: {{ $resource_owner->getId() }}</pre>
                <pre>name: {{ $resource_owner->getName() }}</pre>
                <pre>mail: {{ $resource_owner->getMail() }}</pre>
            </div>
        </div>

        <hr>

        <div class="lead">
            <h1>debug</h1>
            <pre>access_token: {{ $access_token }}</pre>
            <pre>code: {{ $code }}</pre>
            <pre>state: {{ $state }}</pre>
            <pre>error: {{ $error }}</pre>
            <pre>error_code: {{ $error_code }}</pre>
            <pre>error_description: {{ $error_description }}</pre>
        </div>
    </div>
</div>
</div>
</body>
</html>
