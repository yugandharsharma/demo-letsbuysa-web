<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <script type="text/javascript" src="{{ asset('assets/front-end/js/jquery-3.3.1.min.js') }}"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
        integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
        integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
        integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous">
    </script>
    <input type="hidden" id="hyperid" value="{{ session()->get('data') }}">
    <script>
        if ($('#hyperid').val() == "") {
            window.history.back()
        }
    </script>
    <script src="https://oppwa.com/v1/paymentWidgets.js?checkoutId={{ session()->get('data') }}">
    </script>
    <!-- Styles -->
</head>

<body class="antialiased">
    <form action="{{ url('hyperpay_payment/' . session()->get('data') . '/' . session()->get('entityId')) }}"
        class="paymentWidgets" data-brands=" {{ session()->get('types') }}">
    </form>

</body>


</html>
