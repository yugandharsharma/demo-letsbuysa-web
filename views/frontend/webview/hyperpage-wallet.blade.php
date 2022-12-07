<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

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

    <script src="https://eu-prod.oppwa.com/v1/paymentWidgets.js?checkoutId={{ $id }}"></script>
    {{-- <script src="https://test.oppwa.com/v1/paymentWidgets.js?checkoutId={{ $id }}"></script> --}}
    <!-- Styles -->
    <style type="text/css">
        .wpwl-form-card,
        .wpwl-form-directDebit,
        .wpwl-form-onlineTransfer-EPS,
        .wpwl-form-onlineTransfer-ENTERCASH,
        .wpwl-form-onlineTransfer-GIROPAY,
        .wpwl-form-onlineTransfer-IDEAL,
        .wpwl-form-onlineTransfer-SADAD,
        .wpwl-form-onlineTransfer-SOFORTUEBERWEISUNG,
        .wpwl-form-virtualAccount-KLARNA_INVOICE,
        .wpwl-form-virtualAccount-KLARNA_INSTALLMENTS,
        .wpwl-form-virtualAccount-NETELLER,
        .wpwl-form-virtualAccount-PASTEANDPAY_V,
        .wpwl-form-virtualAccount-VSTATION_V,
        .wpwl-form-virtualAccount-CHINAUNIONPAY,
        .wpwl-form-has-inputs {
            border-radius: 5px;
            border: 1px solid #E8E8E8;
            background: #fff;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: none;
        }

        .wpwl-control {
            height: 45px;
            line-height: 45px;
            padding: 0px 15px;
            font-size: 14px;
            border-radius: 10px;
            border: 1px solid #E2E2E2;
        }

        .wpwl-label {
            padding-right: 24px;
            width: 100%;
        }

        .wpwl-label {
            padding-top: 4px;
            padding-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #50525F;
            display: block;
        }

        .wpwl-button.wpwl-button-pay {
            height: 42px;
            padding: 0px 20px;
            box-shadow: none !important;
            margin: 0;
            margin-top: 0px;
            text-transform: capitalize;
            line-height: 42px;
            display: inline-flex !important;
            align-items: center;
            font-size: 16px;
            font-weight: 600;
            border-radius: 5px;
            justify-content: center;
            margin-top: 5px;
        }

        .wpwl-button-pay {
            color: #fff;
            background-color: #3D3E48;
            border-color: #3D3E48;
        }

    </style>


</head>

<body class="antialiased">
    <form class="paymentWidgets"
        action="{{ url('hyper-page-wallet-payment/' . $id . '/' . $entityId . '/' . $user_id . '/' . $amount) }}"
        data-brands="{{ $method }}">
    </form>


</html>
