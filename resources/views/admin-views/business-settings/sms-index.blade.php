@extends('layouts.admin.app')

@section('title','SMS Module Setup')

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-sm-0">
                    <h1 class="page-header-title">{{__('messages.sms')}} {{__('messages.gateway')}} {{__('messages.setup')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->

        <div class="row" style="padding-bottom: 20px">

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h5 class="text-center">Wabot</h5>
                        <span class="badge badge-soft-info mb-3">Fallback feature will only works if you<br>enable Wabot and 1 other SMS gateway as backup</span><br>
                        @php($config=json_decode(file_get_contents(app_path() . '/CentralLogics/wabot.json'), true))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['wabot_gw']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">Wabot Whatsapp Gateway</label>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <input type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.active')}}</label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <input type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <label class=""
                                       style="padding-left: 10px">Wabot Server (ex. https://wabot.arrocy.ml)</label><br>
                                <input type="text" class="form-control" name="server" placeholder="https://wabot.arrocy.ml"
                                       value="{{env('APP_MODE')!='demo'?$config['server']??"":''}}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">Sender Phone with country code</label><br>
                                <input type="text" class="form-control" name="sender" placeholder="6281212341234"
                                       value="{{env('APP_MODE')!='demo'?$config['sender']??"":''}}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">Apikey/Token</label><br>
                                <input type="text" class="form-control" name="apikey" placeholder="458215"
                                       value="{{env('APP_MODE')!='demo'?$config['apikey']??"":''}}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">Message</label><br>
                                <input type="text" class="form-control" name="message" placeholder="Your otp is #OTP#."
                                       value="{{env('APP_MODE')!='demo'?$config['message']??"":''}}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">Wabot Fallback (use below)</label><br>
                                <select class="form-control form-control-lg" name="fallback">
                                    <option @if($config['fallback'] == "none") selected @endif value="none">none</option>
                                    <option @if($config['fallback'] == "any") selected @endif value="any">any gateway</option>
                                </select>
                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{__('messages.save')}}</button>
                                    <a href="https://wabot.arrocy.ml" class="btn btn-primary mb-2 float-right" target="_blank">Register Wabot</a>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h5 class="text-center">{{__('messages.twilio_sms')}}</h5>
                        <span class="badge badge-soft-info mb-3">NB : #OTP# will be replace with otp</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('twilio_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['twilio_sms']):'javascript:'}}"
                            method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{__('messages.twilio_sms')}}</label>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <input type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.active')}}</label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <input type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">{{__('messages.sid')}}</label><br>
                                <input type="text" class="form-control" name="sid"
                                       value="{{env('APP_MODE')!='demo'?$config['sid']??"":''}}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">{{__('messages.messaging_service_id')}}</label><br>
                                <input type="text" class="form-control" name="messaging_service_id"
                                       value="{{env('APP_MODE')!='demo'?$config['messaging_service_id']??"":''}}">
                            </div>
                            <div class="form-group mb-2">
                                <label style="padding-left: 10px">{{__('messages.token')}}</label><br>
                                <input type="text" class="form-control" name="token"
                                       value="{{env('APP_MODE')!='demo'?$config['token']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-left: 10px">{{__('messages.from')}}</label><br>
                                <input type="text" class="form-control" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-left: 10px">{{__('messages.otp_template')}}</label><br>
                                <input type="text" class="form-control" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{__('messages.save')}}</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h5 class="text-center">{{__('messages.nexmo_sms')}}</h5>
                        <span class="badge badge-soft-info mb-3">NB : #OTP# will be replace with otp</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('nexmo_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['nexmo_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{__('messages.nexmo_sms')}}</label>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <input type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.active')}}</label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <input type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">{{__('messages.api_key')}}</label><br>
                                <input type="text" class="form-control" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}">
                            </div>
                            <div class="form-group mb-2">
                                <label style="padding-left: 10px">{{__('messages.api_secret')}}</label><br>
                                <input type="text" class="form-control" name="api_secret"
                                       value="{{env('APP_MODE')!='demo'?$config['api_secret']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-left: 10px">{{__('messages.from')}}</label><br>
                                <input type="text" class="form-control" name="from"
                                       value="{{env('APP_MODE')!='demo'?$config['from']??"":''}}">
                            </div>

                            <div class="form-group mb-2">
                                <label style="padding-left: 10px">{{__('messages.otp_template')}}</label><br>
                                <input type="text" class="form-control" name="otp_template"
                                       value="{{env('APP_MODE')!='demo'?$config['otp_template']??"":''}}">
                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{__('messages.save')}}</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h5 class="text-center">{{__('messages.2factor_sms')}}</h5>
                        <span class="badge badge-soft-info">EX of SMS provider's template : your OTP is XXXX here, please check.</span><br>
                        <span class="badge badge-soft-info mb-3">NB : XXXX will be replace with otp</span>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('2factor_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['2factor_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{__('messages.2factor_sms')}}</label>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <input type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.active')}}</label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <input type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">{{__('messages.api_key')}}</label><br>
                                <input type="text" class="form-control" name="api_key"
                                       value="{{env('APP_MODE')!='demo'?$config['api_key']??"":''}}">
                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{__('messages.save')}}</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-body" style="padding: 20px">
                        <h5 class="text-center">{{__('messages.msg91_sms')}}</h5>
                        <span class="badge badge-soft-info mb-3">NB : Keep an OTP variable in your SMS providers OTP Template.</span><br>
                        @php($config=\App\CentralLogics\Helpers::get_business_settings('msg91_sms'))
                        <form action="{{env('APP_MODE')!='demo'?route('admin.business-settings.sms-module-update',['msg91_sms']):'javascript:'}}"
                              method="post">
                            @csrf

                            <div class="form-group mb-2">
                                <label class="control-label">{{__('messages.msg91_sms')}}</label>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <input type="radio" name="status" value="1" {{isset($config) && $config['status']==1?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.active')}}</label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <input type="radio" name="status" value="0" {{isset($config) && $config['status']==0?'checked':''}}>
                                <label style="padding-left: 10px">{{__('messages.inactive')}} </label>
                                <br>
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">{{__('messages.template_id')}}</label><br>
                                <input type="text" class="form-control" name="template_id"
                                       value="{{env('APP_MODE')!='demo'?$config['template_id']??"":''}}">
                            </div>
                            <div class="form-group mb-2">
                                <label class="text-capitalize"
                                       style="padding-left: 10px">{{__('messages.authkey')}}</label><br>
                                <input type="text" class="form-control" name="authkey"
                                       value="{{env('APP_MODE')!='demo'?$config['authkey']??"":''}}">
                            </div>

                            <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}"
                                    onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}"
                                    class="btn btn-primary mb-2">{{__('messages.save')}}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')

@endpush
