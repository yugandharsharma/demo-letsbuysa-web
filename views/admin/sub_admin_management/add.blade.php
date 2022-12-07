@include('includes-file.header')
@include('includes-file.sidebar')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Sub Admin Management Add</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('sub_admin_management_list')}}">Sub Admin Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sub Admin Management Add</li>
                </ol>
            </div>
            <div class="col-sm-3">
                <a href="{{route('sub_admin_management_list')}}">
                    <button type="button" class="btn btn-outline-info btn-lg btn-round waves-effect waves-light m-1">Back</button>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form id="addsubadmin" action="{{route('sub_admin_management_add')}}" method="post">
                            @csrf

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{old('name')}}" maxlength="50" id="name" name="name" >
                                </div>
                            </div>
                            @error('name')
                            <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}" maxlength="50" id="email" name="email" >
                                </div>
                            </div>
                            @error('email')
                            <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Mobile Number</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror" value="{{old('mobile')}}" maxlength="50" id="mobile" name="mobile" >
                                </div>
                            </div>
                            @error('mobile')
                            <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                            <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Permission</label>
                                <table id="list" class="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Module Name</th>
                                        <th>Add</th>
                                        <th>Edit</th>
                                        <th>Delete</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input class = "@error('module_name') is-invalid @enderror" type="checkbox" id="category" name="module_name[]" value="category" onclick="permissionCheckboxToggle('category')"/>
                                                        <label for="category">Category</label>
                                                    </div>
                                                </div>
                                                @error('module_name')
                                                <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                                <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_category" name="add[]" value="category" disabled/>
                                                        <label for="add_category"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_category" name="edit[]" value="category" disabled/>
                                                        <label for="edit_category"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_category" name="delete[]" value="category" disabled/>
                                                        <label for="delete_category"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="sub_category" name="module_name[]" value="sub_category" onclick="permissionCheckboxToggle('sub_category')"/>
                                                        <label for="sub_category">Sub Category</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_sub_category" name="add[]" value="sub_category" disabled/>
                                                        <label for="add_sub_category"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_sub_category" name="edit[]" value="sub_category" disabled/>
                                                        <label for="edit_sub_category"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_sub_category" name="delete[]" value="sub_category" disabled/>
                                                        <label for="delete_sub_category"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="global_setting" name="module_name[]" value="global_setting" onclick="permissionCheckboxToggle('global_setting')"/>
                                                        <label for="global_setting">Global Setting</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_global_setting" name="add[]" value="global_setting" disabled/>
                                                        <label for="add_global_setting"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_global_setting" name="edit[]" value="global_setting" disabled/>
                                                        <label for="edit_global_setting"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_global_setting" name="delete[]" value="global_setting" disabled/>
                                                        <label for="delete_global_setting"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="products" name="module_name[]" value="products" onclick="permissionCheckboxToggle('products')"/>
                                                        <label for="products">Products</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_products" name="add[]" value="products" disabled/>
                                                        <label for="add_products"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_products" name="edit[]" value="products" disabled/>
                                                        <label for="edit_products"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_products" name="delete[]" value="products" disabled/>
                                                        <label for="delete_products"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="product_delivery_features" name="module_name[]" value="product_delivery_features" onclick="permissionCheckboxToggle('product_delivery_features')"/>
                                                        <label for="product_delivery_features">Product Delivery Features</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_product_delivery_features" name="add[]" value="product_delivery_features" disabled/>
                                                        <label for="add_product_delivery_features"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_product_delivery_features" name="edit[]" value="product_delivery_features" disabled/>
                                                        <label for="edit_product_delivery_features"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_product_delivery_features" name="delete[]" value="product_delivery_features" disabled/>
                                                        <label for="delete_product_delivery_features"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="email_template" name="module_name[]" value="email_template" onclick="permissionCheckboxToggle('email_template')"/>
                                                        <label for="email_template">Email Template</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_email_template" name="add[]" value="email_template" disabled/>
                                                        <label for="add_email_template"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_email_template" name="edit[]" value="email_template" disabled/>
                                                        <label for="edit_email_template"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_email_template" name="delete[]" value="email_template" disabled/>
                                                        <label for="delete_email_template"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="order_status" name="module_name[]" value="order_status" onclick="permissionCheckboxToggle('order_status')"/>
                                                        <label for="order_status">Order Status</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_order_status" name="add[]" value="order_status" disabled/>
                                                        <label for="add_order_status"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_order_status" name="edit[]" value="order_status" disabled/>
                                                        <label for="edit_order_status"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_order_status" name="delete[]" value="order_status" disabled/>
                                                        <label for="delete_order_status"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="attributes" name="module_name[]" value="attributes" onclick="permissionCheckboxToggle('attributes')"/>
                                                        <label for="attributes">Attributes</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_attributes" name="add[]" value="attributes" disabled/>
                                                        <label for="add_attributes"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_attributes" name="edit[]" value="attributes" disabled/>
                                                        <label for="edit_attributes"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_attributes" name="delete[]" value="attributes" disabled/>
                                                        <label for="delete_attributes"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="banners" name="module_name[]" value="banners" onclick="permissionCheckboxToggle('banners')"/>
                                                        <label for="banners">Banners</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_banners" name="add[]" value="banners" disabled/>
                                                        <label for="add_banners"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_banners" name="edit[]" value="banners" disabled/>
                                                        <label for="edit_banners"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_banners" name="delete[]" value="banners" disabled/>
                                                        <label for="delete_banners"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="side_banner" name="module_name[]" value="side_banner" onclick="permissionCheckboxToggle('side_banner')"/>
                                                        <label for="side_banner">Side Banner</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_side_banner" name="add[]" value="side_banner" disabled/>
                                                        <label for="add_side_banner"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_side_banner" name="edit[]" value="side_banner" disabled/>
                                                        <label for="edit_side_banner"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_side_banner" name="delete[]" value="side_banner" disabled/>
                                                        <label for="delete_side_banner"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="offer_banners" name="module_name[]" value="offer_banners" onclick="permissionCheckboxToggle('offer_banners')"/>
                                                        <label for="offer_banners">Offer Banners</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_offer_banners" name="add[]" value="offer_banners" disabled/>
                                                        <label for="add_offer_banners"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_offer_banners" name="edit[]" value="offer_banners" disabled/>
                                                        <label for="edit_offer_banners"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_offer_banners" name="delete[]" value="offer_banners" disabled/>
                                                        <label for="delete_offer_banners"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="hot_deals" name="module_name[]" value="hot_deals" onclick="permissionCheckboxToggle('hot_deals')"/>
                                                        <label for="hot_deals">Hot Deals</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_hot_deals" name="add[]" value="hot_deals" disabled/>
                                                        <label for="add_hot_deals"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_hot_deals" name="edit[]" value="hot_deals" disabled/>
                                                        <label for="edit_hot_deals"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_hot_deals" name="delete[]" value="hot_deals" disabled/>
                                                        <label for="delete_hot_deals"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="top_brands" name="module_name[]" value="top_brands" onclick="permissionCheckboxToggle('top_brands')"/>
                                                        <label for="top_brands">Top Brands</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_top_brands" name="add[]" value="top_brands" disabled/>
                                                        <label for="add_top_brands"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_top_brands" name="edit[]" value="top_brands" disabled/>
                                                        <label for="edit_top_brands"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_top_brands" name="delete[]" value="top_brands" disabled/>
                                                        <label for="delete_top_brands"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="colors" name="module_name[]" value="colors" onclick="permissionCheckboxToggle('colors')"/>
                                                        <label for="colors">Colors</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_colors" name="add[]" value="colors" disabled/>
                                                        <label for="add_colors"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_colors" name="edit[]" value="colors" disabled/>
                                                        <label for="edit_colors"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_colors" name="delete[]" value="colors" disabled/>
                                                        <label for="delete_colors"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="orders" name="module_name[]" value="orders" onclick="permissionCheckboxToggle('orders')"/>
                                                        <label for="orders">Orders</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_orders" name="add[]" value="orders" disabled/>
                                                        <label for="add_orders"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_orders" name="edit[]" value="orders" disabled/>
                                                        <label for="edit_orders"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_orders" name="delete[]" value="orders" disabled/>
                                                        <label for="delete_orders"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="customer" name="module_name[]" value="customer" onclick="permissionCheckboxToggle('customer')"/>
                                                        <label for="customer">Customer</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_customer" name="add[]" value="customer" disabled/>
                                                        <label for="add_customer"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_customer" name="edit[]" value="customer" disabled/>
                                                        <label for="edit_customer"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_customer" name="delete[]" value="customer" disabled/>
                                                        <label for="delete_customer"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="customer_group" name="module_name[]" value="customer_group" onclick="permissionCheckboxToggle('customer_group')"/>
                                                        <label for="customer_group">Customer Group</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_customer_group" name="add[]" value="customer_group" disabled/>
                                                        <label for="add_customer_group"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_customer_group" name="edit[]" value="customer_group" disabled/>
                                                        <label for="edit_customer_group"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_customer_group" name="delete[]" value="customer_group" disabled/>
                                                        <label for="delete_customer_group"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="coupon" name="module_name[]" value="coupon" onclick="permissionCheckboxToggle('coupon')"/>
                                                        <label for="coupon">Coupon</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_coupon" name="add[]" value="coupon" disabled/>
                                                        <label for="add_coupon"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_coupon" name="edit[]" value="coupon" disabled/>
                                                        <label for="edit_coupon"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_coupon" name="delete[]" value="coupon" disabled/>
                                                        <label for="delete_coupon"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="about_us" name="module_name[]" value="about_us" onclick="permissionCheckboxToggle('about_us')"/>
                                                        <label for="about_us">About Us</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_about_us" name="add[]" value="about_us" disabled/>
                                                        <label for="add_about_us"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_about_us" name="edit[]" value="about_us" disabled/>
                                                        <label for="edit_about_us"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_about_us" name="delete[]" value="about_us" disabled/>
                                                        <label for="delete_about_us"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="privacy_policy" name="module_name[]" value="privacy_policy" onclick="permissionCheckboxToggle('privacy_policy')"/>
                                                        <label for="privacy_policy">Privacy Policy</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_privacy_policy" name="add[]" value="privacy_policy" disabled/>
                                                        <label for="add_privacy_policy"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_privacy_policy" name="edit[]" value="privacy_policy" disabled/>
                                                        <label for="edit_privacy_policy"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_privacy_policy" name="delete[]" value="privacy_policy" disabled/>
                                                        <label for="delete_privacy_policy"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="terms_conditions" name="module_name[]" value="terms_conditions" onclick="permissionCheckboxToggle('terms_conditions')"/>
                                                        <label for="terms_conditions">Terms & Conditions</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_terms_conditions" name="add[]" value="terms_conditions" disabled/>
                                                        <label for="add_terms_conditions"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_terms_conditions" name="edit[]" value="terms_conditions" disabled/>
                                                        <label for="edit_terms_conditions"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_terms_conditions" name="delete[]" value="terms_conditions" disabled/>
                                                        <label for="delete_terms_conditions"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="bank_account_payment" name="module_name[]" value="bank_account_payment" onclick="permissionCheckboxToggle('bank_account_payment')"/>
                                                        <label for="bank_account_payment">Bank Account And Payment</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_bank_account_payment" name="add[]" value="bank_account_payment" disabled/>
                                                        <label for="add_bank_account_payment"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_bank_account_payment" name="edit[]" value="bank_account_payment" disabled/>
                                                        <label for="edit_bank_account_payment"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_bank_account_payment" name="delete[]" value="bank_account_payment" disabled/>
                                                        <label for="delete_bank_account_payment"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="shipping_delivery" name="module_name[]" value="shipping_delivery" onclick="permissionCheckboxToggle('shipping_delivery')"/>
                                                        <label for="shipping_delivery">Shipping Delivery</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_shipping_delivery" name="add[]" value="shipping_delivery" disabled/>
                                                        <label for="add_shipping_delivery"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_shipping_delivery" name="edit[]" value="shipping_delivery" disabled/>
                                                        <label for="edit_shipping_delivery"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_shipping_delivery" name="delete[]" value="shipping_delivery" disabled/>
                                                        <label for="delete_shipping_delivery"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="return" name="module_name[]" value="return" onclick="permissionCheckboxToggle('return')"/>
                                                        <label for="return">Return</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_return" name="add[]" value="return" disabled/>
                                                        <label for="add_return"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_return" name="edit[]" value="return" disabled/>
                                                        <label for="edit_return"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_return" name="delete[]" value="return" disabled/>
                                                        <label for="delete_return"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="transaction" name="module_name[]" value="transaction" onclick="permissionCheckboxToggle('transaction')"/>
                                                        <label for="transaction">Transaction</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_transaction" name="add[]" value="transaction" disabled/>
                                                        <label for="add_transaction"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_transaction" name="edit[]" value="transaction" disabled/>
                                                        <label for="edit_transaction"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_transaction" name="delete[]" value="transaction" disabled/>
                                                        <label for="delete_transaction"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="mail" name="module_name[]" value="mail" onclick="permissionCheckboxToggle('mail')"/>
                                                        <label for="mail">Mail</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_mail" name="add[]" value="mail" disabled/>
                                                        <label for="add_mail"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_mail" name="edit[]" value="mail" disabled/>
                                                        <label for="edit_mail"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_mail" name="delete[]" value="mail" disabled/>
                                                        <label for="delete_mail"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="product_reviews" name="module_name[]" value="product_reviews" onclick="permissionCheckboxToggle('product_reviews')"/>
                                                        <label for="product_reviews">Product Reviews</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_product_reviews" name="add[]" value="product_reviews" disabled/>
                                                        <label for="add_product_reviews"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_product_reviews" name="edit[]" value="product_reviews" disabled/>
                                                        <label for="edit_product_reviews"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_product_reviews" name="delete[]" value="product_reviews" disabled/>
                                                        <label for="delete_product_reviews"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>

                                         <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="subadmin" name="module_name[]" value="subadmin" onclick="permissionCheckboxToggle('subadmin')"/>
                                                        <label for="subadmin">Sub Admin</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_subadmin" name="add[]" value="subadmin" disabled/>
                                                        <label for="add_subadmin"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_subadmin" name="edit[]" value="subadmin" disabled/>
                                                        <label for="edit_subadmin"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_subadmin" name="delete[]" value="subadmin" disabled/>
                                                        <label for="delete_subadmin"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="content_management" name="module_name[]" value="content_management" onclick="permissionCheckboxToggle('content_management')"/>
                                                        <label for="content_management">Content Management</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_content_management" name="add[]" value="content_management" disabled/>
                                                        <label for="add_content_management"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_content_management" name="edit[]" value="content_management" disabled/>
                                                        <label for="edit_content_management"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_content_management" name="delete[]" value="content_management" disabled/>
                                                        <label for="delete_content_management"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="dashboard" name="module_name[]" value="dashboard" onclick="permissionCheckboxToggle('dashboard')"/>
                                                        <label for="dashboard">Dashboard</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_dashboard" name="add[]" value="dashboard" disabled/>
                                                        <label for="add_dashboard"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_dashboard" name="edit[]" value="dashboard" disabled/>
                                                        <label for="edit_dashboard"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_dashboard" name="delete[]" value="dashboard" disabled/>
                                                        <label for="delete_dashboard"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="options" name="module_name[]" value="options" onclick="permissionCheckboxToggle('options')"/>
                                                        <label for="options">Options</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_options" name="add[]" value="options" disabled/>
                                                        <label for="add_options"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_options" name="edit[]" value="options" disabled/>
                                                        <label for="edit_options"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_options" name="delete[]" value="options" disabled/>
                                                        <label for="delete_options"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="hot_today" name="module_name[]" value="hot_today" onclick="permissionCheckboxToggle('hot_today')"/>
                                                        <label for="hot_today">Hot Today</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_hot_today" name="add[]" value="hot_today" disabled/>
                                                        <label for="add_hot_today"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_hot_today" name="edit[]" value="hot_today" disabled/>
                                                        <label for="edit_hot_today"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_hot_today" name="delete[]" value="hot_today" disabled/>
                                                        <label for="delete_hot_today"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="brands" name="module_name[]" value="brands" onclick="permissionCheckboxToggle('brands')"/>
                                                        <label for="brands">Brands</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_brands" name="add[]" value="brands" disabled/>
                                                        <label for="add_brands"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_brands" name="edit[]" value="brands" disabled/>
                                                        <label for="edit_brands"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_brands" name="delete[]" value="brands" disabled/>
                                                        <label for="delete_brands"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>

                                          <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="help" name="module_name[]" value="help" onclick="permissionCheckboxToggle('help')"/>
                                                        <label for="help">Help Section</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_help" name="add[]" value="help" disabled/>
                                                        <label for="add_help"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_help" name="edit[]" value="help" disabled/>
                                                        <label for="edit_help"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_help" name="delete[]" value="help" disabled/>
                                                        <label for="delete_help"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>

                                          <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="gift" name="module_name[]" value="gift" onclick="permissionCheckboxToggle('gift')"/>
                                                        <label for="gift">Gift Voucher</label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-info">
                                                        <input type="checkbox" id="add_gift" name="add[]" value="gift" disabled/>
                                                        <label for="add_gift"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-success">
                                                        <input type="checkbox" id="edit_gift" name="edit[]" value="gift" disabled/>
                                                        <label for="edit_gift"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-danger">
                                                        <input type="checkbox" id="delete_gift" name="delete[]" value="gift" disabled/>
                                                        <label for="delete_gift"></label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="newsletter" name="module_name[]" value="newsletter" onclick="permissionCheckboxToggle('newsletter')"/>
                                                        <label for="newsletter">Newsletter</label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="notification" name="notification[]" value="notification" onclick="permissionCheckboxToggle('notification')"/>
                                                        <label for="notification">Notification</label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="order_report" name="module_name[]" value="order_report" onclick="permissionCheckboxToggle('order_report')"/>
                                                        <label for="order_report">Order Report</label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="user_report" name="module_name[]" value="user_report" onclick="permissionCheckboxToggle('user_report')"/>
                                                        <label for="user_report">User Report</label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>
                                        

                                    </tbody>
                                </table>
                            </div>


                            <div class="form-footer">
                                <a href ="{{route('sub_admin_management_list')}}" class="btn btn-danger"><i class="fa fa-times"></i> CANCEL</a>
                                <button type="submit" class="btn btn-success"><i class="fa fa-check-square-o"></i> SAVE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('includes-file.footer')

@if(session()->has('success'))
    <script>
        round_success_noti_record_create();
    </script>
@endif
<script>
    $('#editor1').summernote({
        height: 400,
        tabsize: 2
    });
    $('#editor2').summernote({
        height: 400,
        tabsize: 2
    });
</script>
<script type="text/javascript">
    function permissionCheckboxToggle(module_id) {

        if ($('#'+module_id).is(":checked")) {

            $('#add_'+module_id).prop('disabled',false);
            $('#edit_'+module_id).prop('disabled',false);
            $('#delete_'+module_id).prop('disabled',false);
        }
        else {
            $('#add_'+module_id).prop({'disabled':true,'checked':false});
            $('#edit_'+module_id).prop({'disabled':true,'checked':false});
            $('#delete_'+module_id).prop({'disabled':true,'checked':false});
        }

    }
</script>
</body>
</html>

