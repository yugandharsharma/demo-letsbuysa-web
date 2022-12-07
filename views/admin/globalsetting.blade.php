@include('includes-file.header')
@include('includes-file.sidebar')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Global Settings</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Global Settings</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="globalsetting" action="{{ route('global_settings') }}" enctype="multipart/form-data"
                            method="post">
                            @csrf
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label"> Title(English)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('title_en') is-invalid @enderror"
                                        maxlength="50" id="title_en" name="title_en" value="@if (!empty($global_settings->title_en)){{ $global_settings->title_en }}@endif">
                                </div>
                            </div>
                            @error('title_en')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Title(Arabic)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('title_ar') is-invalid @enderror"
                                        maxlength="50" id="title_ar" name="title_ar" value="@if (!empty($global_settings->title_ar)){{ $global_settings->title_ar }}@endif">
                                </div>
                            </div>
                            @error('title_ar')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror"
                                        maxlength="70" id="email" name="email" value="@if (!empty($global_settings->email)){{ $global_settings->email }}@endif">
                                </div>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Support Email</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('supportemail') is-invalid @enderror"
                                        maxlength="70" id="supportemail" name="supportemail"
                                        value="@if (!empty($global_settings->supportemail)){{ $global_settings->supportemail }}@endif">
                                </div>
                            </div>
                            @error('supportemail')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Copyright(English)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('copyright_en') is-invalid @enderror"
                                        maxlength="150" id="copyright_en" name="copyright_en"
                                        value="@if (!empty($global_settings->copyright_en)){{ $global_settings->copyright_en }}@endif">
                                </div>
                            </div>
                            @error('copyright_en')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Copyright(Arabic)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('copyright_ar') is-invalid @enderror"
                                        maxlength="150" id="copyright_ar" name="copyright_ar"
                                        value="@if (!empty($global_settings->copyright_ar)){{ $global_settings->copyright_ar }}@endif">
                                </div>
                            </div>
                            @error('copyright_ar')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">App Store Link</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('appstoreurl') is-invalid @enderror"
                                        maxlength="150" id="appstoreurl" name="appstoreurl"
                                        value="@if (!empty($global_settings->appstoreurl)){{ $global_settings->appstoreurl }}@endif">
                                </div>
                            </div>
                            @error('appstoreurl')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Play Store Link</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('playstoreurl') is-invalid @enderror"
                                        maxlength="150" id="playstoreurl" name="playstoreurl"
                                        value="@if (!empty($global_settings->playstoreurl)){{ $global_settings->playstoreurl }}@endif">
                                </div>
                            </div>
                            @error('playstoreurl')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Mobile Number</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control @error('mobile') is-invalid @enderror"
                                        maxlength="15" id="mobile" name="mobile" value="@if (!empty($global_settings->mobile)){{ $global_settings->mobile }}@endif">
                                </div>
                            </div>
                            @error('mobile')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Minimum Amount For Shipping</label>
                                <div class="col-sm-10">
                                    <input type="number"
                                        class="form-control @error('min_amount_shipping') is-invalid @enderror"
                                        maxlength="15" id="min_amount_shipping" name="min_amount_shipping"
                                        value="@if (!empty($global_settings->min_amount_shipping)){{ $global_settings->min_amount_shipping }}@endif">
                                </div>
                            </div>
                            @error('min_amount_shipping')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Charge For Shipping</label>
                                <div class="col-sm-10">
                                    <input type="number"
                                        class="form-control @error('shipping_charge') is-invalid @enderror"
                                        maxlength="15" id="shipping_charge" name="shipping_charge"
                                        value="@if (!empty($global_settings->shipping_charge)){{ $global_settings->shipping_charge }}@endif">
                                </div>
                            </div>
                            @error('shipping_charge')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <!-- <div class="form-group row">
                  <label for="input-1" class="col-sm-2 col-form-label">Minimum Amount For Delivery</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control @error('min_amount_delivery') is-invalid @enderror" maxlength="15" id="min_amount_delivery" name="min_amount_delivery" value="@if (!empty($global_settings->min_amount_delivery)){{ $global_settings->min_amount_delivery }}@endif">
                  </div>
                </div>
                 @error('min_amount_delivery')
                                                                                                        <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                                                                                        <strong>{{ $message }}</strong>
                                                                                                        </span>
                @enderror -->

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Charge For Delivery</label>
                                <div class="col-sm-10">
                                    <input type="number"
                                        class="form-control @error('delivery_charge') is-invalid @enderror"
                                        maxlength="15" id="delivery_charge" name="delivery_charge"
                                        value="@if (!empty($global_settings->delivery_charge)){{ $global_settings->delivery_charge }}@endif">
                                </div>
                            </div>
                            @error('delivery_charge')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror


                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Minimum Reward Points To
                                    Redeem</label>
                                <div class="col-sm-10">
                                    <input type="number"
                                        class="form-control @error('minimum_reward_point') is-invalid @enderror"
                                        maxlength="15" id="minimum_reward_point" name="minimum_reward_point"
                                        value="@if (!empty($global_settings->minimum_reward_point)){{ $global_settings->minimum_reward_point }}@endif">
                                </div>
                            </div>
                            @error('minimum_reward_point')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Facebook</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('facebook') is-invalid @enderror"
                                        maxlength="100" id="facebook" name="facebook" value="@if (!empty($global_settings->facebook)){{ $global_settings->facebook }}@endif">
                                </div>
                            </div>
                            @error('facebook')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Twitter</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('twitter') is-invalid @enderror"
                                        maxlength="100" id="twitter" name="twitter" value="@if (!empty($global_settings->twitter)){{ $global_settings->twitter }}@endif">
                                </div>
                            </div>
                            @error('twitter')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Instagram</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                                        maxlength="100" id="instagram" name="instagram"
                                        value="@if (!empty($global_settings->instagram)){{ $global_settings->instagram }}@endif">
                                </div>
                            </div>
                            @error('instagram')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label"> Address(English)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('address_en') is-invalid @enderror"
                                        maxlength="250" id="address_en" name="address_en"
                                        value="@if (!empty($global_settings->address_en)){{ $global_settings->address_en }}@endif">
                                </div>
                            </div>
                            @error('address_en')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Address(Arabic)</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('address_ar') is-invalid @enderror"
                                        maxlength="250" id="address_ar" name="address_ar"
                                        value="@if (!empty($global_settings->address_ar)){{ $global_settings->address_ar }}@endif">
                                </div>
                            </div>
                            @error('address_ar')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Footer Image(English)</label>
                                <div class="col-sm-10">
                                    <input type="file"
                                        class="form-control @error('footer_icon_en') is-invalid @enderror"
                                        maxlength="150" id="footer_icon_en" name="footer_icon_en" accept="image/*">
                                    <img src="{{ url('/') }}/public/images/globalsetting/{{ $global_settings->footer_icon_en }}"
                                        alt="User Image" width="150">
                                </div>
                            </div>
                            @error('footer_icon_en')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Footer Image(Arabic)</label>
                                <div class="col-sm-10">
                                    <input type="file"
                                        class="form-control @error('footer_icon_ar') is-invalid @enderror"
                                        maxlength="150" id="footer_icon_ar" name="footer_icon_ar" accept="image/*">
                                    <img src="{{ url('/') }}/public/images/globalsetting/{{ $global_settings->footer_icon_ar }}"
                                        alt="User Image" width="150">
                                </div>
                            </div>
                            @error('footer_icon_ar')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Popup Image(English)</label>
                                <div class="col-sm-10">
                                    <input type="file"
                                        class="form-control @error('popup_image_en') is-invalid @enderror"
                                        maxlength="150" id="popup_image_en" name="popup_image_en" accept="image/*">
                                    <img src="{{ url('/') }}/public/images/globalsetting/{{ $global_settings->popup_image_en }}"
                                        alt="User Image" width="150">
                                </div>
                            </div>
                            @error('popup_image_en')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Popup Image(Arabic)</label>
                                <div class="col-sm-10">
                                    <input type="file"
                                        class="form-control @error('popup_image_ar') is-invalid @enderror"
                                        maxlength="150" id="popup_image_ar" name="popup_image_ar" accept="image/*">
                                    <img src="{{ url('/') }}/public/images/globalsetting/{{ $global_settings->popup_image_ar }}"
                                        alt="User Image" width="150">
                                </div>
                            </div>
                            @error('popup_image_ar')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Popup Url</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('popup_url') is-invalid @enderror"
                                        maxlength="250" id="popup_url" name="popup_url"
                                        value="@if (!empty($global_settings->popup_url)){{ $global_settings->popup_url }}@endif">
                                </div>
                            </div>
                            @error('popup_url')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Popup Category For App</label>
                                <div class="col-sm-10">
                                    <input type="number"
                                        class="form-control @error('pop_up_category_id') is-invalid @enderror"
                                        maxlength="250" id="pop_up_category_id" name="pop_up_category_id"
                                        value="@if (!empty($global_settings->pop_up_category_id)){{ $global_settings->pop_up_category_id }}@endif">
                                </div>
                            </div>
                            @error('pop_up_category_id')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Popup Sub-Category For App</label>
                                <div class="col-sm-10">
                                    <input type="number"
                                        class="form-control @error('pop_up_subcategory_id') is-invalid @enderror"
                                        maxlength="250" id="pop_up_subcategory_id" name="pop_up_subcategory_id"
                                        value="@if (!empty($global_settings->pop_up_subcategory_id)){{ $global_settings->pop_up_subcategory_id }}@endif">
                                </div>
                            </div>
                            @error('pop_up_subcategory_id')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Popup Image Status</label>
                                <div class="col-sm-10">
                                    <select class="form-control @error('popup_status') is-invalid @enderror"
                                        id="popup_status" name="popup_status">
                                        <option value="1" <?php if ($global_settings->popup_status == '1') {
    echo 'selected';
} ?>>Enabled</option>
                                        <option value="0" <?php if ($global_settings->popup_status == '0') {
    echo 'selected';
} ?>>Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Gift Voucher Image</label>
                                <div class="col-sm-10">
                                    <input type="file"
                                        class="form-control @error('gift_voucher_image') is-invalid @enderror"
                                        maxlength="150" id="gift_voucher_image" name="gift_voucher_image"
                                        accept="image/*">
                                    <img src="{{ url('/') }}/public/images/globalsetting/{{ isset($global_settings->gift_voucher_image) == true ? $global_settings->gift_voucher_image : '' }}"
                                        alt="User Image" width="150">
                                </div>
                            </div>
                            @error('gift_voucher_image')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Tap Payments</label>
                                <div class="col-sm-10">
                                    <select class="form-control @error('tappayment') is-invalid @enderror"
                                        id="tappayment" name="tappayment">
                                        <option value="1" <?php if ($global_settings->tappayment == '1') {
    echo 'selected';
} ?>>Enabled</option>
                                        <option value="0" <?php if ($global_settings->tappayment == '0') {
    echo 'selected';
} ?>>Disabled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Apple pay</label>
                                <div class="col-sm-10">
                                    <select class="form-control @error('applepay') is-invalid @enderror" id="applepay"
                                        name="applepay">
                                        <option value="1" <?php if ($global_settings->applepay == '1') {
    echo 'selected';
} ?>>Enabled</option>
                                        <option value="0" <?php if ($global_settings->applepay == '0') {
    echo 'selected';
} ?>>Disabled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Bank Transfer</label>
                                <div class="col-sm-10">
                                    <select class="form-control @error('bank_transfer') is-invalid @enderror"
                                        id="bank_transfer" name="bank_transfer">
                                        <option value="1" <?php if ($global_settings->bank_transfer == '1') {
    echo 'selected';
} ?>>Enabled</option>
                                        <option value="0" <?php if ($global_settings->bank_transfer == '0') {
    echo 'selected';
} ?>>Disabled</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Cash On Delivery</label>
                                <div class="col-sm-10">
                                    <select class="form-control @error('codpayment') is-invalid @enderror"
                                        id="codpayment" name="codpayment">
                                        <option value="1" <?php if ($global_settings->codpayment == '1') {
    echo 'selected';
} ?>>Enabled</option>
                                        <option value="0" <?php if ($global_settings->codpayment == '0') {
    echo 'selected';
} ?>>Disabled</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Cash On Delivery Country</label>
                                <div class="col-sm-10">
                                    <select class="form-control multiple-select" multiple="multiple"
                                        name="codCountry[]">

                                        <option value="Abu Dhabi"
                                            {{ in_array('Abu Dhabi', $codCountries) == true ? 'selected' : '' }}>Abu
                                            Dhabi
                                        </option>
                                        <option value="Sharjah"
                                            {{ in_array('Sharjah', $codCountries) == true ? 'selected' : '' }}>
                                            Sharjah</option>
                                        <option value="Ajman"
                                            {{ in_array('Ajman', $codCountries) == true ? 'selected' : '' }}>Ajman
                                        </option>
                                        <option value="Fujairah"
                                            {{ in_array('Fujairah', $codCountries) == true ? 'selected' : '' }}>
                                            Fujairah</option>
                                        <option value="Ghayathi"
                                            {{ in_array('Ghayathi', $codCountries) == true ? 'selected' : '' }}>
                                            Ghayathi</option>

                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="input-4" class="col-sm-2 col-form-label">Mega sale Banner</label>
                                <div class="col-sm-10">
                                    <input type="file"
                                        class="form-control @error('mega_sale_banner') is-invalid @enderror"
                                        maxlength="150" id="mega_sale_banner" name="mega_sale_banner" accept="image/*">
                                    <img src="{{ url('/') }}/public/images/globalsetting/{{ $global_settings->mega_sale_banner }}"
                                        alt="User Image" width="150">
                                </div>
                            </div>
                            @error('mega_sale_banner')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Invoice Return
                                    Message(English)</label>
                                <div class="col-sm-10">
                                    <textarea class="textarea"
                                        class="form-control @error('invoice_return_message_en') is-invalid @enderror"
                                        placeholder=" Invoice Return Message" id='editor3'
                                        name="invoice_return_message_en"
                                        style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;">{{ $global_settings->invoice_return_message_en }}</textarea>
                                </div>
                            </div>
                            @error('invoice_return_message_en')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Invoice Return
                                    Message(Arabic)</label>
                                <div class="col-sm-10">
                                    <textarea class="textarea"
                                        class="form-control @error('invoice_return_message_ar') is-invalid @enderror"
                                        placeholder=" Invoice Return Message" id='editor4'
                                        name="invoice_return_message_ar"
                                        style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;">{{ $global_settings->invoice_return_message_ar }}</textarea>
                                </div>
                            </div>
                            @error('invoice_return_message_ar')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Footer
                                    Description(English)</label>
                                <div class="col-sm-10">
                                    <textarea class="textarea"
                                        class="form-control @error('footer_description_en') is-invalid @enderror"
                                        placeholder=" Description" id='editor1' name="footer_description_en"
                                        style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;">{{ $global_settings->footer_description_en }}</textarea>
                                </div>
                            </div>
                            @error('footer_description_en')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Footer Description(Arabic)</label>
                                <div class="col-sm-10">
                                    <textarea class="textarea"
                                        class="form-control @error('footer_description_ar') is-invalid @enderror"
                                        placeholder=" Description" id='editor2' name="footer_description_ar"
                                        style="width: 100%; height: 300px; font-size: 14px; line-height: 20px; border: 1px solid #dddddd; padding: 10px;">{{ $global_settings->footer_description_ar }}</textarea>
                                </div>
                            </div>
                            @error('footer_description_ar')
                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror


                            <div class="form-footer">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i>
                                    SAVE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes-file.footer')

@if (session()->has('success'))
    <script>
        round_success_noti_record_update();
    </script>
@endif
<script>
    CKEDITOR.replace('editor1');
    CKEDITOR.replace('editor2');
    CKEDITOR.replace('editor3');
    CKEDITOR.replace('editor4');
    $(document).ready(function() {
        $('.single-select').select2();

        $('.multiple-select').select2();

        //multiselect start

        $('#my_multi_select1').multiSelect();
        $('#my_multi_select2').multiSelect({
            selectableOptgroup: true
        });
    });
</script>
</body>

</html>
