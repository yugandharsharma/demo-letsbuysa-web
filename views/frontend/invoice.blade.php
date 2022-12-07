<!DOCTYPE html>
<html>
<head> 

<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
<meta name="viewport" content="minimum-scale=1.0, maximum-scale=1.0,width=device-width, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<style type="text/css">
  .address-left p{font-size: 14px; margin: 0px;font-weight: 600;color: #3D3D3D;}
  .order-no-left h6{font-size: 16px; margin: 0px;font-weight: 600;color: #3D3D3D;padding-bottom: 20px;}
  .order-no-left h4{font-size: 20px; margin: 0px;font-weight: 700;color: #3D3D3D;}
  .col-first p{font-size: 13px; margin: 0px;font-weight: 500;color: #3D3D3D;padding-bottom: 5px;}
  .col-first h5{font-size: 15px; margin: 0px;font-weight: 700;color: #D3AC8D;padding-bottom: 20px;}
  .line-tbl thead tr th{font-size: 14px;margin: 0px;font-weight: 600;color: #50525F;}
  .line-tbl td, th {border: 1px solid #E5E5E7;padding: 10px;font-size: 14px;color: #50525F;font-weight: 500;}
  .cocooncenter-texts p{font-size: 14px;font-weight: 500;color: #50525F;margin-bottom: 10px;}
  .cocooncenter-texts h6{font-size: 14px;font-weight: 600;color: #50525F;margin: 0px;text-align: center;}

  @media (max-width: 480px) {
  }
</style>

</head>
<body marginheight="0" style="font-family: 'Open Sans', sans-serif; background-color: #F2F2F2;">
<button onclick="printdata()" type ="button" class="btn btn-primary">Print Invoice</button>
<a href="{{ url()->previous() }}"  type ="button" class="btn btn-primary">Back</a>
<div  style="width:100%;" id="printdiv">

  <table style="table-layout: fixed;border-spacing: 0;border-collapse: collapse;width: 100%;">
    <thead>
      <tr>
       <!--  <th>header</th> -->
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>
          <div class="main-bx" style="min-width: 320px;max-width: 1300px;Margin: 0 auto;padding: 20px;background: #fff;border: 1px solid #f0f0f0;margin: 40px auto;border-radius: 10px;">
            <div class="col-head">
                <table class="social_icons" role="presentation" style="table-layout: fixed; vertical-align: top; border-spacing: 0; border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt;" valign="top" width="100%" cellspacing="0" cellpadding="0">
                    <tbody class="col-first">
                      <tr>
                        <td class="address-left" style="text-align: left;">
                            <img tyle="max-width: 200px;" src="http://newletsbuy.devtechnosys.tech/assets/front-end/images/logo.svg">
                            @if((\App::getLocale() == 'en'))
                            <p>{{$global->address_en}}</p>
                            @else
                            <p>{{$global->address_ar}}</p>
                            @endif
                            <p><?php echo  __('messages.phone') ?>: {{$global->mobile}}</p>
                        </td>
                        <td class="order-no-left" style="text-align: right;">
                            <h6><?php echo  __('messages.issued') ?>: {{$order->created_at}}</h6>
                            <h4><?php echo  __('messages.orderno') ?>: {{$order->id}}</h4>
                            <!-- <a style="color: #333; text-decoration: none;" href="#"><img src="images/email.png" style="max-width: 15px; padding-right: 8px">support@yallacash.com</a> -->
                        </td>
                      </tr>
                    </tbody>
                  </table>
            </div>


            <div class="col-first" style="padding: 25px 0px;">
                 <table style="width:100%;border-collapse: collapse;margin-bottom:0px;">
                    <tbody>
                      <tr>
                        <td style="border: 1px solid #E8E8E8;padding: 10px 20px;">
                          <h5><?php echo  __('messages.billing_address') ?></h5>
                          <p>{{$address_detail->fullname}}</p>
                          <p>{{$address_detail->fulladdress}}</p>
                          <p>{{$address_detail->mobile}}</p>
                          <p>Phone: +966{{$address_detail->mobile}}</p>
                          <p>E-mail: {{$user_data->email}}</p>
                        </td>
                        <td style="border: 1px solid #E8E8E8;padding: 10px 20px; text-align: right;">
                          <h5><?php echo  __('messages.shipping_address') ?></h5>
                          <p>{{$address_detail->fullname}}</p>
                          <p>{{$address_detail->fulladdress}}</p>
                          <p>{{$address_detail->mobile}}</p>
                          <p>Phone: +966{{$address_detail->mobile}}</p>
                          <p>E-mail: {{$user_data->email}}</p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
            </div>

            <div class="col-second" style="padding: 25px 0px;">
              <table class="line-tbl" style="width:100%;border-collapse: collapse;margin-bottom:0px;">
                    <thead>
                      <tr>
                        <th><?php echo  __('messages.code') ?></th>
                        <th><?php echo  __('messages.item') ?></th>
                        <th><?php echo  __('messages.quantity') ?></th>
                        <th><?php echo  __('messages.price') ?></th>
                        <th><?php echo  __('messages.total') ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($order_details as $record)
                      <tr>
                        <td>{{$record->product_id}} </td>
                        @if((\App::getLocale() == 'en'))
                        <td>{{$record->product_name_en}}</td>
                        @else
                        <td>{{$record->product_name_ar}}</td>
                        @endif
                        <td>{{$record->quantity}}</td>
                        <td>{{$record->price}}</td>
                        <td>{{$record->price}} </td>
                      </tr>
                      @endforeach
                    </tbody>
                </table>
                <div class="totel-id-bx">
                  <table style="width: 100%;border: 1px solid #E5E5E7;border-top: none;border-collapse: collapse;">
                    <tr>
                      <td style="width: 50%;border-right: 1px solid #E5E5E7;">
                        <table style="width: 100%; font-size: 14px; font-weight: 600;color: #50525F;padding: 10px 5px;">
                          <!-- <tr>
                            <td>Customer ID</td>
                            <td>: {{$user_data->id}}</td>
                          </tr>
                          <tr>
                            <td>Order number </td>
                            <td>: {{$order->id}}</td>
                          </tr> -->
                          <!-- <tr>
                            <td>Shipping method</td>
                            <td>: Fedex - Chronopost</td>
                          </tr> -->
                        </table>
                      </td>
                      <td style="width: 50%;text-align: right;">
                        <table  style="width: 100%; font-size: 14px; font-weight: 600;color: #50525F;padding: 10px 5px;">
                          <tr>
                            <td><?php echo  __('messages.sub_total') ?></td>
                            <td>: SAR {{$order->product_total_amount}}</td>
                          </tr>
                          <tr>
                            <td><?php echo  __('messages.shipping_charge') ?></td>
                            <td>: SAR {{$order->shipping_price}}</td>
                          </tr>
                          @if($order->delivery_price !=0)
                          <tr>
                            <td><?php echo  __('messages.cash_on_delivery_price') ?></td>
                            <td>: SAR {{$order->delivery_price}}</td>
                          </tr>
                          @endif
                          <tr>
                            <td><?php echo  __('messages.discount_price') ?></td>
                            <td>: SAR {{$order->discount}}</td>
                          </tr>

                          <tr>
                            <td><?php echo  __('messages.total_price_excluded_tax') ?></td>
                            <td>: SAR {{$order->price}}</td>
                          </tr>
                          @if($order->paid_by_wallet >0)
                          <tr>
                            <td><?php echo  __('messages.paid_by_wallet') ?></td>
                            <td>: SAR {{$order->paid_by_wallet}}</td>
                          </tr>
                          @endif
                          <!-- @if($order->payment_type ==1)
                          <tr>
                            <td><?php echo  __('messages.order_paid') ?></td>
                          </tr>
                          @else

                          @if($order->paid_by_wallet >0)
                          <tr>
                            <td><?php echo  __('messages.you_have_pay') ?></td>
                            <td>: SAR {{$order->price-$order->paid_by_wallet}}</td>
                          </tr>
                          @endif
                          @endif -->
                        </table>
                      </td>
                    </tr>
                  </table>
                </div>
                <div class="cocooncenter-texts">
                  @if((\App::getLocale() == 'en'))
                  {!!$global->invoice_return_message_en!!}
                  @else
                  {!!$global->invoice_return_message_ar!!}
                  @endif
                </div>
            </div>

          </div>
        </td>
      </tr>
    </tbody>
  </table>

</div>
</body>
<script>
function printdata()
{
        var printContents = document.getElementById('printdiv').innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
}


</script>
</html>
