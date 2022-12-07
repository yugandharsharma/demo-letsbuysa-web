@include('includes-file.header')
@include('includes-file.sidebar')
<div class="clearfix"></div>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Sub Admin Management Edit</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{route('sub_admin_management_list')}}">Sub Admin Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Sub Admin Management Edit</li>
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
                        <form id="addsubadmin" action="{{ route('sub_admin_management_edit', base64_encode($edit->id))}}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label for="input-1" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{$edit->name ?? ''}}" maxlength="50" id="name" name="name" >
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
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" value="{{$edit->email ?? ''}}" maxlength="50" id="email" name="email" >
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
                                    <input type="text" class="form-control @error('mobile') is-invalid @enderror" value="{{$edit->mobile ?? ''}}" maxlength="50" id="mobile" name="mobile" >
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
                                                    <input class = "@error('module_name') is-invalid @enderror" type="checkbox" id="category" name="module_name[]" value="category" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('category', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('category')"/>
                                                    <label for="category">Category</label>
                                                    @error('module_name')
                                                    <span class="invalid-feedback" style="color: red; display:block; padding-left:260px">
                                                    <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_category" name="add[]" value="category" {{isset($permissions['category']) && $permissions['category']['add']=='1' ? 'checked' : ''}}/>
                                                    <label for="add_category"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_category" name="edit[]" value="category" {{isset($permissions['category']) && $permissions['category']['edit']=='1' ? 'checked' : ''}}/>
                                                    <label for="edit_category"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_category" name="delete[]" value="category" {{isset($permissions['category']) && $permissions['category']['delete']=='1' ? 'checked' : ''}}/>
                                                    <label for="delete_category"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="sub_category" name="module_name[]" value="sub_category" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('sub_category', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('sub_category')"/>
                                                    <label for="sub_category">Sub Category</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_sub_category" name="add[]" value="sub_category" {{isset($permissions['sub_category']) && $permissions['sub_category']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_sub_category"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_sub_category" name="edit[]" value="sub_category" {{isset($permissions['sub_category']) && $permissions['sub_category']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_sub_category"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_sub_category" name="delete[]" value="sub_category" {{isset($permissions['sub_category']) && $permissions['sub_category']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_sub_category"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="global_setting" name="module_name[]" value="global_setting" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('global_setting', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('global_setting')"/>
                                                    <label for="global_setting">Global Setting</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_global_setting" name="add[]" value="global_setting" {{isset($permissions['global_setting']) && $permissions['global_setting']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_global_setting"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_global_setting" name="edit[]" value="global_setting" {{isset($permissions['global_setting']) && $permissions['global_setting']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_global_setting"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_global_setting" name="delete[]" value="global_setting" {{isset($permissions['global_setting']) && $permissions['global_setting']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_global_setting"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="products" name="module_name[]" value="products" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])) {{in_array('products', $permissions['module_name']) ? 'checked' : ''}} @endif onclick="permissionCheckboxToggle('products')"/>
                                                    <label for="products">Products</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_products" name="add[]" value="products" {{isset($permissions['products']) && $permissions['products']['add']=='1' ? 'checked' : ''}}/>
                                                    <label for="add_products"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_products" name="edit[]" value="products" {{isset($permissions['products']) && $permissions['products']['edit']=='1' ? 'checked' : ''}}/>
                                                    <label for="edit_products"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_products" name="delete[]" value="products" {{isset($permissions['products']) && $permissions['products']['delete']=='1' ? 'checked' : ''}}/>
                                                    <label for="delete_products"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="product_delivery_features" name="module_name[]" value="product_delivery_features" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('product_delivery_features', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('product_delivery_features')"/>
                                                    <label for="product_delivery_features">Product Delivery Features</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_product_delivery_features" name="add[]" value="product_delivery_features" {{isset($permissions['product_delivery_features']) && $permissions['product_delivery_features']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_product_delivery_features"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_product_delivery_features" name="edit[]" value="product_delivery_features" {{isset($permissions['product_delivery_features']) && $permissions['product_delivery_features']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_product_delivery_features"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_product_delivery_features" name="delete[]" value="product_delivery_features" {{isset($permissions['product_delivery_features']) && $permissions['product_delivery_features']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_product_delivery_features"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="email_template" name="module_name[]" value="email_template" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('email_template', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('email_template')"/>
                                                    <label for="email_template">Email Template</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_email_template" name="add[]" value="email_template" {{isset($permissions['email_template']) && $permissions['email_template']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_email_template"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_email_template" name="edit[]" value="email_template" {{isset($permissions['email_template']) && $permissions['email_template']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_email_template"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_email_template" name="delete[]" value="email_template" {{isset($permissions['email_template']) && $permissions['email_template']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_email_template"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="order_status" name="module_name[]" value="order_status" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('order_status', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('order_status')"/>
                                                    <label for="order_status">Order Status</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_order_status" name="add[]" value="order_status" {{isset($permissions['order_status']) && $permissions['order_status']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_order_status"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_order_status" name="edit[]" value="order_status" {{isset($permissions['order_status']) && $permissions['order_status']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_order_status"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_order_status" name="delete[]" value="order_status" {{isset($permissions['order_status']) && $permissions['order_status']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_order_status"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="attributes" name="module_name[]" value="attributes" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('attributes', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('attributes')"/>
                                                    <label for="attributes">Attributes</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_attributes" name="add[]" value="attributes" {{isset($permissions['attributes']) && $permissions['attributes']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_attributes"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_attributes" name="edit[]" value="attributes" {{isset($permissions['attributes']) && $permissions['attributes']['edit']=='1' ? 'checked' : ''}}/>
                                                    <label for="edit_attributes"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_attributes" name="delete[]" value="attributes" {{isset($permissions['attributes']) && $permissions['attributes']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_attributes"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="banners" name="module_name[]" value="banners" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('banners', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('banners')"/>
                                                    <label for="banners">Banners</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_banners" name="add[]" value="banners" {{isset($permissions['banners']) && $permissions['banners']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_banners"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_banners" name="edit[]" value="banners" {{isset($permissions['banners']) && $permissions['banners']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_banners"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_banners" name="delete[]" value="banners" {{isset($permissions['banners']) && $permissions['banners']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_banners"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="side_banner" name="module_name[]" value="side_banner" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('side_banner', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('side_banner')"/>
                                                    <label for="side_banner">Side Banner</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_side_banner" name="add[]" value="side_banner" {{isset($permissions['side_banner']) && $permissions['side_banner']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_side_banner"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_side_banner" name="edit[]" value="side_banner" {{isset($permissions['side_banner']) && $permissions['side_banner']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_side_banner"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_side_banner" name="delete[]" value="side_banner" {{isset($permissions['side_banner']) && $permissions['side_banner']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_side_banner"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="offer_banners" name="module_name[]" value="offer_banners" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('offer_banners', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('offer_banners')"/>
                                                    <label for="offer_banners">Offer Banners</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_offer_banners" name="add[]" value="offer_banners" {{isset($permissions['offer_banners']) && $permissions['offer_banners']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_offer_banners"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_offer_banners" name="edit[]" value="offer_banners" {{isset($permissions['offer_banners']) && $permissions['offer_banners']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_offer_banners"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_offer_banners" name="delete[]" value="offer_banners" {{isset($permissions['offer_banners']) && $permissions['offer_banners']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_offer_banners"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="hot_deals" name="module_name[]" value="hot_deals" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('hot_deals', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('hot_deals')"/>
                                                    <label for="hot_deals">Hot Deals</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_hot_deals" name="add[]" value="hot_deals" {{isset($permissions['hot_deals']) && $permissions['hot_deals']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_hot_deals"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_hot_deals" name="edit[]" value="hot_deals" {{isset($permissions['hot_deals']) && $permissions['hot_deals']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_hot_deals"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_hot_deals" name="delete[]" value="hot_deals" {{isset($permissions['hot_deals']) && $permissions['hot_deals']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_hot_deals"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="top_brands" name="module_name[]" value="top_brands" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('top_brands', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('top_brands')"/>
                                                    <label for="top_brands">Top Brands</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_top_brands" name="add[]" value="top_brands" {{isset($permissions['top_brands']) && $permissions['top_brands']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_top_brands"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_top_brands" name="edit[]" value="top_brands" {{isset($permissions['top_brands']) && $permissions['top_brands']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_top_brands"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_top_brands" name="delete[]" value="top_brands" {{isset($permissions['top_brands']) && $permissions['top_brands']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_top_brands"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="colors" name="module_name[]" value="colors" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('colors', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('colors')"/>
                                                    <label for="colors">Colors</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_colors" name="add[]" value="colors" {{isset($permissions['colors']) && $permissions['colors']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_colors"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_colors" name="edit[]" value="colors" {{isset($permissions['colors']) && $permissions['colors']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_colors"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_colors" name="delete[]" value="colors" {{isset($permissions['colors']) && $permissions['colors']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_colors"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="orders" name="module_name[]" value="orders" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('orders', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('orders')"/>
                                                    <label for="orders">Orders</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_orders" name="add[]" value="orders" {{isset($permissions['orders']) && $permissions['orders']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_orders"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_orders" name="edit[]" value="orders" {{isset($permissions['orders']) && $permissions['orders']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_orders"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_orders" name="delete[]" value="orders" {{isset($permissions['orders']) && $permissions['orders']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_orders"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="customer" name="module_name[]" value="customer" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('customer', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('customer')"/>
                                                    <label for="customer">Customer</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_customer" name="add[]" value="customer" {{isset($permissions['customer']) && $permissions['customer']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_customer"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_customer" name="edit[]" value="customer" {{isset($permissions['customer']) && $permissions['customer']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_customer"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_customer" name="delete[]" value="customer" {{isset($permissions['customer']) && $permissions['customer']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_customer"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="customer_group" name="module_name[]" value="customer_group" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('customer_group', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('customer_group')"/>
                                                    <label for="customer_group">Customer Group</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_customer_group" name="add[]" value="customer_group" {{isset($permissions['customer_group']) && $permissions['customer_group']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_customer_group"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_customer_group" name="edit[]" value="customer_group" {{isset($permissions['customer_group']) && $permissions['customer_group']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_customer_group"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_customer_group" name="delete[]" value="customer_group" {{isset($permissions['customer_group']) && $permissions['customer_group']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_customer_group"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="coupon" name="module_name[]" value="coupon" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('coupon', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('coupon')"/>
                                                    <label for="coupon">Coupon</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_coupon" name="add[]" value="coupon" {{isset($permissions['coupon']) && $permissions['coupon']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_coupon"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_coupon" name="edit[]" value="coupon" {{isset($permissions['coupon']) && $permissions['coupon']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_coupon"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_coupon" name="delete[]" value="coupon" {{isset($permissions['coupon']) && $permissions['coupon']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_coupon"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="about_us" name="module_name[]" value="about_us" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('about_us', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('about_us')"/>
                                                    <label for="about_us">About Us</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_about_us" name="add[]" value="about_us" {{isset($permissions['about_us']) && $permissions['about_us']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_about_us"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_about_us" name="edit[]" value="about_us" {{isset($permissions['about_us']) && $permissions['about_us']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_about_us"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_about_us" name="delete[]" value="about_us" {{isset($permissions['about_us']) && $permissions['about_us']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_about_us"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="privacy_policy" name="module_name[]" value="privacy_policy" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('privacy_policy', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('privacy_policy')"/>
                                                    <label for="privacy_policy">Privacy Policy</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_privacy_policy" name="add[]" value="privacy_policy" {{isset($permissions['privacy_policy']) && $permissions['privacy_policy']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_privacy_policy"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_privacy_policy" name="edit[]" value="privacy_policy" {{isset($permissions['privacy_policy']) && $permissions['privacy_policy']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_privacy_policy"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_privacy_policy" name="delete[]" value="privacy_policy" {{isset($permissions['privacy_policy']) && $permissions['privacy_policy']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_privacy_policy"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="terms_conditions" name="module_name[]" value="terms_conditions" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('terms_conditions', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('terms_conditions')"/>
                                                    <label for="terms_conditions">Terms & Conditions</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_terms_conditions" name="add[]" value="terms_conditions" {{isset($permissions['terms_conditions']) && $permissions['terms_conditions']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_terms_conditions"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_terms_conditions" name="edit[]" value="terms_conditions" {{isset($permissions['terms_conditions']) && $permissions['terms_conditions']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_terms_conditions"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_terms_conditions" name="delete[]" value="terms_conditions" {{isset($permissions['terms_conditions']) && $permissions['terms_conditions']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_terms_conditions"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="bank_account_payment" name="module_name[]" value="bank_account_payment" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('bank_account_payment', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('bank_account_payment')"/>
                                                    <label for="bank_account_payment">Bank Account And Payment</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_bank_account_payment" name="add[]" value="bank_account_payment" {{isset($permissions['bank_account_payment']) && $permissions['bank_account_payment']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_bank_account_payment"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_bank_account_payment" name="edit[]" value="bank_account_payment" {{isset($permissions['bank_account_payment']) && $permissions['bank_account_payment']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_bank_account_payment"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_bank_account_payment" name="delete[]" value="bank_account_payment" {{isset($permissions['bank_account_payment']) && $permissions['bank_account_payment']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_bank_account_payment"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="shipping_delivery" name="module_name[]" value="shipping_delivery" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('shipping_delivery', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('shipping_delivery')"/>
                                                    <label for="shipping_delivery">Shipping Delivery</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_shipping_delivery" name="add[]" value="shipping_delivery" {{isset($permissions['shipping_delivery']) && $permissions['shipping_delivery']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_shipping_delivery"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_shipping_delivery" name="edit[]" value="shipping_delivery" {{isset($permissions['shipping_delivery']) && $permissions['shipping_delivery']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_shipping_delivery"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_shipping_delivery" name="delete[]" value="shipping_delivery" {{isset($permissions['shipping_delivery']) && $permissions['shipping_delivery']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_shipping_delivery"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="return" name="module_name[]" value="return" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('return', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('return')"/>
                                                    <label for="return">Return</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_return" name="add[]" value="return" {{isset($permissions['return']) && $permissions['return']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_return"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_return" name="edit[]" value="return" {{isset($permissions['return']) && $permissions['return']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_return"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_return" name="delete[]" value="return" {{isset($permissions['return']) && $permissions['return']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_return"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="transaction" name="module_name[]" value="transaction" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('transaction', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('transaction')"/>
                                                    <label for="transaction">Transaction</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_transaction" name="add[]" value="transaction" {{isset($permissions['transaction']) && $permissions['transaction']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_transaction"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_transaction" name="edit[]" value="transaction" {{isset($permissions['transaction']) && $permissions['transaction']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_transaction"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_transaction" name="delete[]" value="transaction" {{isset($permissions['transaction']) && $permissions['transaction']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_transaction"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="mail" name="module_name[]" value="mail" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('mail', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('mail')"/>
                                                    <label for="mail">Mail</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_mail" name="add[]" value="mail" {{isset($permissions['mail']) && $permissions['mail']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_mail"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_mail" name="edit[]" value="mail" {{isset($permissions['mail']) && $permissions['mail']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_mail"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_mail" name="delete[]" value="mail" {{isset($permissions['mail']) && $permissions['mail']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_mail"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="product_reviews" name="module_name[]" value="product_reviews" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('product_reviews', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('product_reviews')"/>
                                                    <label for="product_reviews">Product Reviews</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_product_reviews" name="add[]" value="product_reviews" {{isset($permissions['product_reviews']) && $permissions['product_reviews']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_product_reviews"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_product_reviews" name="edit[]" value="product_reviews" {{isset($permissions['product_reviews']) && $permissions['product_reviews']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_product_reviews"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_product_reviews" name="delete[]" value="product_reviews" {{isset($permissions['product_reviews']) && $permissions['product_reviews']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_product_reviews"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>

                                     <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="subadmin" name="module_name[]" value="subadmin" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('subadmin', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('subadmin')"/>
                                                    <label for="subadmin">Sub Admin</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_subadmin" name="add[]" value="subadmin" {{isset($permissions['subadmin']) && $permissions['subadmin']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_subadmin"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_subadmin" name="edit[]" value="subadmin" {{isset($permissions['subadmin']) && $permissions['subadmin']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_subadmin"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_subadmin" name="delete[]" value="subadmin" {{isset($permissions['subadmin']) && $permissions['subadmin']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_subadmin"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="content_management" name="module_name[]" value="content_management" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('content_management', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('content_management')"/>
                                                    <label for="content_management">Content Management</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_content_management" name="add[]" value="content_management" {{isset($permissions['content_management']) && $permissions['content_management']['add']=='1' ? 'checked' : ''}} />
                                                    <label for="add_content_management"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_content_management" name="edit[]" value="content_management" {{isset($permissions['content_management']) && $permissions['content_management']['edit']=='1' ? 'checked' : ''}} />
                                                    <label for="edit_content_management"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_content_management" name="delete[]" value="content_management" {{isset($permissions['content_management']) && $permissions['content_management']['delete']=='1' ? 'checked' : ''}} />
                                                    <label for="delete_content_management"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="dashboard" name="module_name[]" value="dashboard" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('dashboard', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('dashboard')"/>
                                                    <label for="dashboard">Dashboard</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_dashboard" name="add[]" value="dashboard" {{isset($permissions['dashboard']) && $permissions['dashboard']['add']=='1' ? 'checked' : ''}}/>
                                                    <label for="add_dashboard"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_dashboard" name="edit[]" value="dashboard" {{isset($permissions['dashboard']) && $permissions['dashboard']['edit']=='1' ? 'checked' : ''}}/>
                                                    <label for="edit_dashboard"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_dashboard" name="delete[]" value="dashboard" {{isset($permissions['dashboard']) && $permissions['dashboard']['delete']=='1' ? 'checked' : ''}}/>
                                                    <label for="delete_dashboard"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="options" name="module_name[]" value="options" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('options', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('options')"/>
                                                    <label for="options">Options</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_options" name="add[]" value="options" {{isset($permissions['options']) && $permissions['options']['add']=='1' ? 'checked' : ''}}/>
                                                    <label for="add_options"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_options" name="edit[]" value="options" {{isset($permissions['options']) && $permissions['options']['edit']=='1' ? 'checked' : ''}}/>
                                                    <label for="edit_options"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_options" name="delete[]" value="options" {{isset($permissions['options']) && $permissions['options']['delete']=='1' ? 'checked' : ''}}/>
                                                    <label for="delete_options"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="hot_today" name="module_name[]" value="hot_today" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('hot_today', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('hot_today')"/>
                                                    <label for="hot_today">Hot Today</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_hot_today" name="add[]" value="hot_today" {{isset($permissions['hot_today']) && $permissions['hot_today']['add']=='1' ? 'checked' : ''}}/>
                                                    <label for="add_hot_today"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_hot_today" name="edit[]" value="hot_today" {{isset($permissions['hot_today']) && $permissions['hot_today']['edit']=='1' ? 'checked' : ''}}/>
                                                    <label for="edit_hot_today"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_hot_today" name="delete[]" value="hot_today" {{isset($permissions['hot_today']) && $permissions['hot_today']['delete']=='1' ? 'checked' : ''}}/>
                                                    <label for="delete_hot_today"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="brands" name="module_name[]" value="brands" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('brands', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('brands')"/>
                                                    <label for="brands">Brands</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_brands" name="add[]" value="brands" {{isset($permissions['brands']) && $permissions['brands']['add']=='1' ? 'checked' : ''}}/>
                                                    <label for="add_brands"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_brands" name="edit[]" value="brands" {{isset($permissions['brands']) && $permissions['brands']['edit']=='1' ? 'checked' : ''}}/>
                                                    <label for="edit_brands"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_brands" name="delete[]" value="brands" {{isset($permissions['brands']) && $permissions['brands']['delete']=='1' ? 'checked' : ''}}/>
                                                    <label for="delete_brands"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>

                                     <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="help" name="module_name[]" value="help" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('help', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('help')"/>
                                                    <label for="help">Help Section</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_help" name="add[]" value="help" {{isset($permissions['help']) && $permissions['help']['add']=='1' ? 'checked' : ''}}/>
                                                    <label for="add_help"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_help" name="edit[]" value="help" {{isset($permissions['help']) && $permissions['help']['edit']=='1' ? 'checked' : ''}}/>
                                                    <label for="edit_help"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_help" name="delete[]" value="help" {{isset($permissions['help']) && $permissions['help']['delete']=='1' ? 'checked' : ''}}/>
                                                    <label for="delete_help"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>

                                     <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="gift" name="module_name[]" value="gift" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('help', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('gift')"/>
                                                    <label for="gift">Gift Voucher</label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-info">
                                                    <input type="checkbox" id="add_gift" name="add[]" value="gift" {{isset($permissions['gift']) && $permissions['gift']['add']=='1' ? 'checked' : ''}}/>
                                                    <label for="add_gift"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-success">
                                                    <input type="checkbox" id="edit_gift" name="edit[]" value="gift" {{isset($permissions['gift']) && $permissions['gift']['edit']=='1' ? 'checked' : ''}}/>
                                                    <label for="edit_gift"></label>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-danger">
                                                    <input type="checkbox" id="delete_gift" name="delete[]" value="gift" {{isset($permissions['gift']) && $permissions['gift']['delete']=='1' ? 'checked' : ''}}/>
                                                    <label for="delete_gift"></label>
                                                </div>
                                            </div>
                                        </td>

                                    </tr>

                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="newsletter" name="module_name[]" value="newsletter" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('newsletter', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('newsletter')"/>
                                                    <label for="newsletter">Newsletter</label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="form-group py-2">
                                                <div class="icheck-material-primary">
                                                    <input type="checkbox" id="notification" name="module_name[]" value="notification" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('notification', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('notification')"/>
                                                    <label for="notification">notification</label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>

                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="order_report" name="module_name[]" value="order_report" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('order_report', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('order_report')"/>
                                                        <label for="order_report">Order Report</label>
                                                    </div>
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="form-group py-2">
                                                    <div class="icheck-material-primary">
                                                        <input type="checkbox" id="user_report" name="module_name[]" value="user_report" @if(isset($permissions['module_name']) && !empty($permissions['module_name'])){{in_array('user_report', $permissions['module_name']) ? 'checked' : ''}}@endif onclick="permissionCheckboxToggle('user_report')"/>
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
        round_success_noti_record_update();
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

