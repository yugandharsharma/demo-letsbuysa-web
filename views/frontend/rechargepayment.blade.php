<html>
<head>
    <title>goSell Demo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <link rel="shortcut icon" href="https://goSellJSLib.b-cdn.net/v1.6.0/imgs/tap-favicon.ico" />
    <link href="https://goSellJSLib.b-cdn.net/v1.6.0/css/gosell.css" rel="stylesheet" />
</head>
<body>
    <script type="text/javascript" src="https://goSellJSLib.b-cdn.net/v1.6.0/js/gosell.js"></script>

    <div id="root"></div>
<script>
window.onload = function() {
  goSell.openPaymentPage()
};
</script>
    <script>

    goSell.config({
      containerID:"root",
      gateway:{
        publicKey:"pk_live_pybwfTNxBUVIPAj0v5YGKqgr",
        merchantId: null,
        language:"en",
        contactInfo:true,
        supportedCurrencies:"all",
        supportedPaymentMethods: "all",
        saveCardOption:false,
        customerCards: true,
        notifications:'standard',
        callback:(response) => {
            console.log('response', response);
        },
        onClose: () => {
            console.log("onClose Event");
        },
        backgroundImg: {
          url: 'imgURL',
          opacity: '0.5'
        },
        labels:{
            cardNumber:"Card Number",
            expirationDate:"MM/YY",
            cvv:"CVV",
            cardHolder:"Name on Card",
            actionButton:"Pay"
        },
        style: {
            base: {
              color: '#535353',
              lineHeight: '18px',
              fontFamily: 'sans-serif',
              fontSmoothing: 'antialiased',
              fontSize: '16px',
              '::placeholder': {
                color: 'rgba(0, 0, 0, 0.26)',
                fontSize:'15px'
              }
            },
            invalid: {
              color: 'red',
              iconColor: '#fa755a '
            }
        }
      },
      customer:{
        id:null,
        first_name: '<?= $user_data->name?>',
        email: '<?= $user_data->email?>',
        phone: {
            number: '<?= $user_data->mobile?>'
        }
      },
      order:{
        amount: <?= $amount?>,
        currency:"SAR",
        items:[],
        shipping:null,
        taxes: null
      },
     transaction:{
       mode: 'charge',
       charge:{
          saveCard: false,
          threeDSecure: true,
          description: "Test Description",
          statement_descriptor: "Sample",
          reference:{
            transaction: "txn_"+Math.floor(100000 + Math.random() * 900000),
            order: "recharge_"+Math.floor(100000 + Math.random() * 900000)
          },
          metadata:{},
          receipt:{
            email: false,
            sms: true
          },
          redirect: "<?= url('/rechargepayment') ?>",
          post: null,
        }
     }
    });

    </script>

</body>
</html>
