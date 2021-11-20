@extends('layouts.admin.app')

@section('title','Settings')

@push('css_or_js')
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 48px;
        height: 23px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 15px;
        width: 15px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #377dff;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #377dff;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    }
    #location_map_canvas{
        height: 100%;
    }
    @media only screen and (max-width: 768px) {
        /* For mobile phones: */
        #location_map_canvas{
            height: 200px;
        }
    }
</style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <h1 class="page-header-title text-capitalize">{{__('messages.business')}} {{__('messages.setup')}}</h1>
                </div>
            </div>
        </div>
        <!-- End Page Header -->
        <div class="row gx-2 gx-lg-3">
            <div class="col-md-12 mb-3 mt-3">
                <div class="card">
                    <div class="card-body" style="padding-bottom: 12px">
                        <div class="row">
                            @php($config=\App\CentralLogics\Helpers::get_business_settings('maintenance_mode'))
                            <div class="col-6">
                                <h5 class="text-capitalize">
                                    <i class="tio-settings-outlined"></i>
                                    {{__('messages.maintenance_mode')}}
                                </h5>
                            </div>
                            <div class="col-6">
                                <label class="switch ml-3 float-right">
                                    <input type="checkbox" class="status" onclick="maintenance_mode()"
                                        {{isset($config) && $config?'checked':''}}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-12 mb-3 mb-lg-2">
                <form action="{{route('admin.business-settings.update-setup')}}" method="post"
                      enctype="multipart/form-data">
                    @csrf
                    @php($name=\App\Models\BusinessSetting::where('key','business_name')->first())
                    <div class="form-group">
                        <label class="input-label" for="exampleFormControlInput1">{{__('messages.business')}} {{__('messages.name')}}</label>
                        <input type="text" name="restaurant_name" value="{{$name->value??''}}" class="form-control"
                               placeholder="{{__('messages.new_business')}}" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-12">
                        @php($currency_code=\App\Models\BusinessSetting::where('key','currency')->first())
                            <div class="form-group">
                                <label class="input-label" for="exampleFormControlInput1">{{__('messages.currency')}}</label>
                                <select name="currency" class="form-control js-select2-custom">
                                    @foreach(\App\Models\Currency::orderBy('currency_code')->get() as $currency)
                                        <option
                                            value="{{$currency['currency_code']}}" {{$currency_code?($currency_code->value==$currency['currency_code']?'selected':''):''}}>
                                            {{$currency['currency_code']}} ( {{$currency['currency_symbol']}} )
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-12">
                        @php($currency_symbol_position=\App\Models\BusinessSetting::where('key','currency_symbol_position')->first())
                            <div class="form-group">
                                <label class="input-label text-capitalize" for="currency_symbol_position">{{__('messages.currency_symbol_positon')}}</label>
                                <select name="currency_symbol_position" class="form-control js-select2-custom" id="currency_symbol_position">
                                    <option
                                        value="left" {{$currency_symbol_position?($currency_symbol_position->value=='left'?'selected':''):''}}>
                                        {{__('messages.left')}} ({{\App\CentralLogics\Helpers::currency_symbol()}}123)
                                    </option>
                                    <option
                                        value="right" {{$currency_symbol_position?($currency_symbol_position->value=='right'?'selected':''):''}}>
                                        {{__('messages.right')}} (123{{\App\CentralLogics\Helpers::currency_symbol()}})
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="form-group">
                                <label class="input-label text-capitalize d-inline" for="country">{{__('messages.country')}}</label>    
                                <select id="country" name="country" class="form-control  js-select2-custom">
                                    <option value="AF">Afghanistan</option>
                                    <option value="AX">Åland Islands</option>
                                    <option value="AL">Albania</option>
                                    <option value="DZ">Algeria</option>
                                    <option value="AS">American Samoa</option>
                                    <option value="AD">Andorra</option>
                                    <option value="AO">Angola</option>
                                    <option value="AI">Anguilla</option>
                                    <option value="AQ">Antarctica</option>
                                    <option value="AG">Antigua and Barbuda</option>
                                    <option value="AR">Argentina</option>
                                    <option value="AM">Armenia</option>
                                    <option value="AW">Aruba</option>
                                    <option value="AU">Australia</option>
                                    <option value="AT">Austria</option>
                                    <option value="AZ">Azerbaijan</option>
                                    <option value="BS">Bahamas</option>
                                    <option value="BH">Bahrain</option>
                                    <option value="BD">Bangladesh</option>
                                    <option value="BB">Barbados</option>
                                    <option value="BY">Belarus</option>
                                    <option value="BE">Belgium</option>
                                    <option value="BZ">Belize</option>
                                    <option value="BJ">Benin</option>
                                    <option value="BM">Bermuda</option>
                                    <option value="BT">Bhutan</option>
                                    <option value="BO">Bolivia, Plurinational State of</option>
                                    <option value="BQ">Bonaire, Sint Eustatius and Saba</option>
                                    <option value="BA">Bosnia and Herzegovina</option>
                                    <option value="BW">Botswana</option>
                                    <option value="BV">Bouvet Island</option>
                                    <option value="BR">Brazil</option>
                                    <option value="IO">British Indian Ocean Territory</option>
                                    <option value="BN">Brunei Darussalam</option>
                                    <option value="BG">Bulgaria</option>
                                    <option value="BF">Burkina Faso</option>
                                    <option value="BI">Burundi</option>
                                    <option value="KH">Cambodia</option>
                                    <option value="CM">Cameroon</option>
                                    <option value="CA">Canada</option>
                                    <option value="CV">Cape Verde</option>
                                    <option value="KY">Cayman Islands</option>
                                    <option value="CF">Central African Republic</option>
                                    <option value="TD">Chad</option>
                                    <option value="CL">Chile</option>
                                    <option value="CN">China</option>
                                    <option value="CX">Christmas Island</option>
                                    <option value="CC">Cocos (Keeling) Islands</option>
                                    <option value="CO">Colombia</option>
                                    <option value="KM">Comoros</option>
                                    <option value="CG">Congo</option>
                                    <option value="CD">Congo, the Democratic Republic of the</option>
                                    <option value="CK">Cook Islands</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="CI">Côte d'Ivoire</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CU">Cuba</option>
                                    <option value="CW">Curaçao</option>
                                    <option value="CY">Cyprus</option>
                                    <option value="CZ">Czech Republic</option>
                                    <option value="DK">Denmark</option>
                                    <option value="DJ">Djibouti</option>
                                    <option value="DM">Dominica</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="EG">Egypt</option>
                                    <option value="SV">El Salvador</option>
                                    <option value="GQ">Equatorial Guinea</option>
                                    <option value="ER">Eritrea</option>
                                    <option value="EE">Estonia</option>
                                    <option value="ET">Ethiopia</option>
                                    <option value="FK">Falkland Islands (Malvinas)</option>
                                    <option value="FO">Faroe Islands</option>
                                    <option value="FJ">Fiji</option>
                                    <option value="FI">Finland</option>
                                    <option value="FR">France</option>
                                    <option value="GF">French Guiana</option>
                                    <option value="PF">French Polynesia</option>
                                    <option value="TF">French Southern Territories</option>
                                    <option value="GA">Gabon</option>
                                    <option value="GM">Gambia</option>
                                    <option value="GE">Georgia</option>
                                    <option value="DE">Germany</option>
                                    <option value="GH">Ghana</option>
                                    <option value="GI">Gibraltar</option>
                                    <option value="GR">Greece</option>
                                    <option value="GL">Greenland</option>
                                    <option value="GD">Grenada</option>
                                    <option value="GP">Guadeloupe</option>
                                    <option value="GU">Guam</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="GG">Guernsey</option>
                                    <option value="GN">Guinea</option>
                                    <option value="GW">Guinea-Bissau</option>
                                    <option value="GY">Guyana</option>
                                    <option value="HT">Haiti</option>
                                    <option value="HM">Heard Island and McDonald Islands</option>
                                    <option value="VA">Holy See (Vatican City State)</option>
                                    <option value="HN">Honduras</option>
                                    <option value="HK">Hong Kong</option>
                                    <option value="HU">Hungary</option>
                                    <option value="IS">Iceland</option>
                                    <option value="IN">India</option>
                                    <option value="ID">Indonesia</option>
                                    <option value="IR">Iran, Islamic Republic of</option>
                                    <option value="IQ">Iraq</option>
                                    <option value="IE">Ireland</option>
                                    <option value="IM">Isle of Man</option>
                                    <option value="IL">Israel</option>
                                    <option value="IT">Italy</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="JP">Japan</option>
                                    <option value="JE">Jersey</option>
                                    <option value="JO">Jordan</option>
                                    <option value="KZ">Kazakhstan</option>
                                    <option value="KE">Kenya</option>
                                    <option value="KI">Kiribati</option>
                                    <option value="KP">Korea, Democratic People's Republic of</option>
                                    <option value="KR">Korea, Republic of</option>
                                    <option value="KW">Kuwait</option>
                                    <option value="KG">Kyrgyzstan</option>
                                    <option value="LA">Lao People's Democratic Republic</option>
                                    <option value="LV">Latvia</option>
                                    <option value="LB">Lebanon</option>
                                    <option value="LS">Lesotho</option>
                                    <option value="LR">Liberia</option>
                                    <option value="LY">Libya</option>
                                    <option value="LI">Liechtenstein</option>
                                    <option value="LT">Lithuania</option>
                                    <option value="LU">Luxembourg</option>
                                    <option value="MO">Macao</option>
                                    <option value="MK">Macedonia, the former Yugoslav Republic of</option>
                                    <option value="MG">Madagascar</option>
                                    <option value="MW">Malawi</option>
                                    <option value="MY">Malaysia</option>
                                    <option value="MV">Maldives</option>
                                    <option value="ML">Mali</option>
                                    <option value="MT">Malta</option>
                                    <option value="MH">Marshall Islands</option>
                                    <option value="MQ">Martinique</option>
                                    <option value="MR">Mauritania</option>
                                    <option value="MU">Mauritius</option>
                                    <option value="YT">Mayotte</option>
                                    <option value="MX">Mexico</option>
                                    <option value="FM">Micronesia, Federated States of</option>
                                    <option value="MD">Moldova, Republic of</option>
                                    <option value="MC">Monaco</option>
                                    <option value="MN">Mongolia</option>
                                    <option value="ME">Montenegro</option>
                                    <option value="MS">Montserrat</option>
                                    <option value="MA">Morocco</option>
                                    <option value="MZ">Mozambique</option>
                                    <option value="MM">Myanmar</option>
                                    <option value="NA">Namibia</option>
                                    <option value="NR">Nauru</option>
                                    <option value="NP">Nepal</option>
                                    <option value="NL">Netherlands</option>
                                    <option value="NC">New Caledonia</option>
                                    <option value="NZ">New Zealand</option>
                                    <option value="NI">Nicaragua</option>
                                    <option value="NE">Niger</option>
                                    <option value="NG">Nigeria</option>
                                    <option value="NU">Niue</option>
                                    <option value="NF">Norfolk Island</option>
                                    <option value="MP">Northern Mariana Islands</option>
                                    <option value="NO">Norway</option>
                                    <option value="OM">Oman</option>
                                    <option value="PK">Pakistan</option>
                                    <option value="PW">Palau</option>
                                    <option value="PS">Palestinian Territory, Occupied</option>
                                    <option value="PA">Panama</option>
                                    <option value="PG">Papua New Guinea</option>
                                    <option value="PY">Paraguay</option>
                                    <option value="PE">Peru</option>
                                    <option value="PH">Philippines</option>
                                    <option value="PN">Pitcairn</option>
                                    <option value="PL">Poland</option>
                                    <option value="PT">Portugal</option>
                                    <option value="PR">Puerto Rico</option>
                                    <option value="QA">Qatar</option>
                                    <option value="RE">Réunion</option>
                                    <option value="RO">Romania</option>
                                    <option value="RU">Russian Federation</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="BL">Saint Barthélemy</option>
                                    <option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
                                    <option value="KN">Saint Kitts and Nevis</option>
                                    <option value="LC">Saint Lucia</option>
                                    <option value="MF">Saint Martin (French part)</option>
                                    <option value="PM">Saint Pierre and Miquelon</option>
                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                    <option value="WS">Samoa</option>
                                    <option value="SM">San Marino</option>
                                    <option value="ST">Sao Tome and Principe</option>
                                    <option value="SA">Saudi Arabia</option>
                                    <option value="SN">Senegal</option>
                                    <option value="RS">Serbia</option>
                                    <option value="SC">Seychelles</option>
                                    <option value="SL">Sierra Leone</option>
                                    <option value="SG">Singapore</option>
                                    <option value="SX">Sint Maarten (Dutch part)</option>
                                    <option value="SK">Slovakia</option>
                                    <option value="SI">Slovenia</option>
                                    <option value="SB">Solomon Islands</option>
                                    <option value="SO">Somalia</option>
                                    <option value="ZA">South Africa</option>
                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                    <option value="SS">South Sudan</option>
                                    <option value="ES">Spain</option>
                                    <option value="LK">Sri Lanka</option>
                                    <option value="SD">Sudan</option>
                                    <option value="SR">Suriname</option>
                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                    <option value="SZ">Swaziland</option>
                                    <option value="SE">Sweden</option>
                                    <option value="CH">Switzerland</option>
                                    <option value="SY">Syrian Arab Republic</option>
                                    <option value="TW">Taiwan, Province of China</option>
                                    <option value="TJ">Tajikistan</option>
                                    <option value="TZ">Tanzania, United Republic of</option>
                                    <option value="TH">Thailand</option>
                                    <option value="TL">Timor-Leste</option>
                                    <option value="TG">Togo</option>
                                    <option value="TK">Tokelau</option>
                                    <option value="TO">Tonga</option>
                                    <option value="TT">Trinidad and Tobago</option>
                                    <option value="TN">Tunisia</option>
                                    <option value="TR">Turkey</option>
                                    <option value="TM">Turkmenistan</option>
                                    <option value="TC">Turks and Caicos Islands</option>
                                    <option value="TV">Tuvalu</option>
                                    <option value="UG">Uganda</option>
                                    <option value="UA">Ukraine</option>
                                    <option value="AE">United Arab Emirates</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="US">United States</option>
                                    <option value="UM">United States Minor Outlying Islands</option>
                                    <option value="UY">Uruguay</option>
                                    <option value="UZ">Uzbekistan</option>
                                    <option value="VU">Vanuatu</option>
                                    <option value="VE">Venezuela, Bolivarian Republic of</option>
                                    <option value="VN">Viet Nam</option>
                                    <option value="VG">Virgin Islands, British</option>
                                    <option value="VI">Virgin Islands, U.S.</option>
                                    <option value="WF">Wallis and Futuna</option>
                                    <option value="EH">Western Sahara</option>
                                    <option value="YE">Yemen</option>
                                    <option value="ZM">Zambia</option>
                                    <option value="ZW">Zimbabwe</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-12">
                        @php($tz=\App\Models\BusinessSetting::where('key','timezone')->first())
                        @php($tz=$tz?$tz->value:0)
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize">{{__('messages.time')}} {{__('messages.zone')}}</label>
                                <select name="timezone" class="form-control js-select2-custom">
                                    <option value="UTC" {{$tz?($tz==''?'selected':''):''}}>UTC</option>
                                    <option value="Etc/GMT+12"  {{$tz?($tz=='Etc/GMT+12'?'selected':''):''}}>(GMT-12:00) International Date Line West</option>
                                    <option value="Pacific/Midway"  {{$tz?($tz=='Pacific/Midway'?'selected':''):''}}>(GMT-11:00) Midway Island, Samoa</option>
                                    <option value="Pacific/Honolulu"  {{$tz?($tz=='Pacific/Honolulu'?'selected':''):''}}>(GMT-10:00) Hawaii</option>
                                    <option value="US/Alaska"  {{$tz?($tz=='US/Alaska'?'selected':''):''}}>(GMT-09:00) Alaska</option>
                                    <option value="America/Los_Angeles"  {{$tz?($tz=='America/Los_Angeles'?'selected':''):''}}>(GMT-08:00) Pacific Time (US & Canada)</option>
                                    <option value="America/Tijuana"  {{$tz?($tz=='America/Tijuana'?'selected':''):''}}>(GMT-08:00) Tijuana, Baja California</option>
                                    <option value="US/Arizona"  {{$tz?($tz=='US/Arizona'?'selected':''):''}}>(GMT-07:00) Arizona</option>
                                    <option value="America/Chihuahua"  {{$tz?($tz=='America/Chihuahua'?'selected':''):''}}>(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                    <option value="US/Mountain"  {{$tz?($tz=='US/Mountain'?'selected':''):''}}>(GMT-07:00) Mountain Time (US & Canada)</option>
                                    <option value="America/Managua"  {{$tz?($tz=='America/Managua'?'selected':''):''}}>(GMT-06:00) Central America</option>
                                    <option value="US/Central"  {{$tz?($tz=='US/Central'?'selected':''):''}}>(GMT-06:00) Central Time (US & Canada)</option>
                                    <option value="America/Mexico_City"  {{$tz?($tz=='America/Mexico_City'?'selected':''):''}}>(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                    <option value="Canada/Saskatchewan"  {{$tz?($tz=='Canada/Saskatchewan'?'selected':''):''}}>(GMT-06:00) Saskatchewan</option>
                                    <option value="America/Bogota"  {{$tz?($tz=='America/Bogota'?'selected':''):''}}>(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                    <option value="US/Eastern"  {{$tz?($tz=='US/Eastern'?'selected':''):''}}>(GMT-05:00) Eastern Time (US & Canada)</option>
                                    <option value="US/East-Indiana"  {{$tz?($tz=='US/East-Indiana'?'selected':''):''}}>(GMT-05:00) Indiana (East)</option>
                                    <option value="Canada/Atlantic"  {{$tz?($tz=='Canada/Atlantic'?'selected':''):''}}>(GMT-04:00) Atlantic Time (Canada)</option>
                                    <option value="America/Caracas"  {{$tz?($tz=='America/Caracas'?'selected':''):''}}>(GMT-04:00) Caracas, La Paz</option>
                                    <option value="America/Manaus"  {{$tz?($tz=='America/Manaus'?'selected':''):''}}>(GMT-04:00) Manaus</option>
                                    <option value="America/Santiago"  {{$tz?($tz=='America/Santiago'?'selected':''):''}}>(GMT-04:00) Santiago</option>
                                    <option value="Canada/Newfoundland"  {{$tz?($tz=='Canada/Newfoundland'?'selected':''):''}}>(GMT-03:30) Newfoundland</option>
                                    <option value="America/Sao_Paulo"  {{$tz?($tz=='America/Sao_Paulo'?'selected':''):''}}>(GMT-03:00) Brasilia</option>
                                    <option value="America/Argentina/Buenos_Aires"  {{$tz?($tz=='America/Argentina/Buenos_Aires'?'selected':''):''}}>(GMT-03:00) Buenos Aires, Georgetown</option>
                                    <option value="America/Godthab"  {{$tz?($tz=='America/Godthab'?'selected':''):''}}>(GMT-03:00) Greenland</option>
                                    <option value="America/Montevideo"  {{$tz?($tz=='America/Montevideo'?'selected':''):''}}>(GMT-03:00) Montevideo</option>
                                    <option value="America/Noronha"  {{$tz?($tz=='America/Noronha'?'selected':''):''}}>(GMT-02:00) Mid-Atlantic</option>
                                    <option value="Atlantic/Cape_Verde"  {{$tz?($tz=='Atlantic/Cape_Verde'?'selected':''):''}}>(GMT-01:00) Cape Verde Is.</option>
                                    <option value="Atlantic/Azores"  {{$tz?($tz=='Atlantic/Azores'?'selected':''):''}}>(GMT-01:00) Azores</option>
                                    <option value="Africa/Casablanca"  {{$tz?($tz=='Africa/Casablanca'?'selected':''):''}}>(GMT+00:00) Casablanca, Monrovia, Reykjavik</option>
                                    <option value="Etc/Greenwich"  {{$tz?($tz=='Etc/Greenwich'?'selected':''):''}}>(GMT+00:00) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                    <option value="Europe/Amsterdam"  {{$tz?($tz=='Europe/Amsterdam'?'selected':''):''}}>(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                    <option value="Europe/Belgrade"  {{$tz?($tz=='Europe/Belgrade'?'selected':''):''}}>(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                    <option value="Europe/Brussels"  {{$tz?($tz=='Europe/Brussels'?'selected':''):''}}>(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                    <option value="Europe/Sarajevo"  {{$tz?($tz=='Europe/Sarajevo'?'selected':''):''}}>(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                    <option value="Africa/Lagos"  {{$tz?($tz=='Africa/Lagos'?'selected':''):''}}>(GMT+01:00) West Central Africa</option>
                                    <option value="Asia/Amman"  {{$tz?($tz=='Asia/Amman'?'selected':''):''}}>(GMT+02:00) Amman</option>
                                    <option value="Europe/Athens"  {{$tz?($tz=='Europe/Athens'?'selected':''):''}}>(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                    <option value="Asia/Beirut"  {{$tz?($tz=='Asia/Beirut'?'selected':''):''}}>(GMT+02:00) Beirut</option>
                                    <option value="Africa/Cairo"  {{$tz?($tz=='Africa/Cairo'?'selected':''):''}}>(GMT+02:00) Cairo</option>
                                    <option value="Africa/Harare"  {{$tz?($tz=='Africa/Harare'?'selected':''):''}}>(GMT+02:00) Harare, Pretoria</option>
                                    <option value="Europe/Helsinki"  {{$tz?($tz=='Europe/Helsinki'?'selected':''):''}}>(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                    <option value="Asia/Jerusalem"  {{$tz?($tz=='Asia/Jerusalem'?'selected':''):''}}>(GMT+02:00) Jerusalem</option>
                                    <option value="Europe/Minsk"  {{$tz?($tz=='Europe/Minsk'?'selected':''):''}}>(GMT+02:00) Minsk</option>
                                    <option value="Africa/Windhoek"  {{$tz?($tz=='Africa/Windhoek'?'selected':''):''}}>(GMT+02:00) Windhoek</option>
                                    <option value="Asia/Kuwait"  {{$tz?($tz=='Asia/Kuwait'?'selected':''):''}}>(GMT+03:00) Kuwait, Riyadh, Baghdad</option>
                                    <option value="Europe/Moscow"  {{$tz?($tz=='Europe/Moscow'?'selected':''):''}}>(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                    <option value="Africa/Nairobi"  {{$tz?($tz=='Africa/Nairobi'?'selected':''):''}}>(GMT+03:00) Nairobi</option>
                                    <option value="Asia/Tbilisi"  {{$tz?($tz=='Asia/Tbilisi'?'selected':''):''}}>(GMT+03:00) Tbilisi</option>
                                    <option value="Asia/Tehran"  {{$tz?($tz=='Asia/Tehran'?'selected':''):''}}>(GMT+03:30) Tehran</option>
                                    <option value="Asia/Muscat"  {{$tz?($tz=='Asia/Muscat'?'selected':''):''}}>(GMT+04:00) Abu Dhabi, Muscat</option>
                                    <option value="Asia/Baku"  {{$tz?($tz=='Asia/Baku'?'selected':''):''}}>(GMT+04:00) Baku</option>
                                    <option value="Asia/Yerevan"  {{$tz?($tz=='Asia/Yerevan'?'selected':''):''}}>(GMT+04:00) Yerevan</option>
                                    <option value="Asia/Kabul"  {{$tz?($tz=='Asia/Kabul'?'selected':''):''}}>(GMT+04:30) Kabul</option>
                                    <option value="Asia/Yekaterinburg"  {{$tz?($tz=='Asia/Yekaterinburg'?'selected':''):''}}>(GMT+05:00) Yekaterinburg</option>
                                    <option value="Asia/Karachi"  {{$tz?($tz=='Asia/Karachi'?'selected':''):''}}>(GMT+05:00) Islamabad, Karachi, Tashkent</option>
                                    <option value="Asia/Calcutta"  {{$tz?($tz=='Asia/Calcutta'?'selected':''):''}}>(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                    <!-- <option value="Asia/Calcutta"  {{$tz?($tz=='Asia/Calcutta'?'selected':''):''}}>(GMT+05:30) Sri Jayawardenapura</option> -->
                                    <option value="Asia/Katmandu"  {{$tz?($tz=='Asia/Katmandu'?'selected':''):''}}>(GMT+05:45) Kathmandu</option>
                                    <option value="Asia/Almaty"  {{$tz?($tz=='Asia/Almaty'?'selected':''):''}}>(GMT+06:00) Almaty, Novosibirsk</option>
                                    <option value="Asia/Dhaka"  {{$tz?($tz=='Asia/Dhaka'?'selected':''):''}}>(GMT+06:00) Astana, Dhaka</option>
                                    <option value="Asia/Rangoon"  {{$tz?($tz=='Asia/Rangoon'?'selected':''):''}}>(GMT+06:30) Yangon (Rangoon)</option>
                                    <option value="Asia/Bangkok"  {{$tz?($tz=='"Asia/Bangkok'?'selected':''):''}}>(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                    <option value="Asia/Krasnoyarsk"  {{$tz?($tz=='Asia/Krasnoyarsk'?'selected':''):''}}>(GMT+07:00) Krasnoyarsk</option>
                                    <option value="Asia/Hong_Kong"  {{$tz?($tz=='Asia/Hong_Kong'?'selected':''):''}}>(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                    <option value="Asia/Kuala_Lumpur"  {{$tz?($tz=='Asia/Kuala_Lumpur'?'selected':''):''}}>(GMT+08:00) Kuala Lumpur, Singapore</option>
                                    <option value="Asia/Irkutsk"  {{$tz?($tz=='Asia/Irkutsk'?'selected':''):''}}>(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                    <option value="Australia/Perth"  {{$tz?($tz=='Australia/Perth'?'selected':''):''}}>(GMT+08:00) Perth</option>
                                    <option value="Asia/Taipei"  {{$tz?($tz=='Asia/Taipei'?'selected':''):''}}>(GMT+08:00) Taipei</option>
                                    <option value="Asia/Tokyo"  {{$tz?($tz=='Asia/Tokyo'?'selected':''):''}}>(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                    <option value="Asia/Seoul"  {{$tz?($tz=='Asia/Seoul'?'selected':''):''}}>(GMT+09:00) Seoul</option>
                                    <option value="Asia/Yakutsk"  {{$tz?($tz=='Asia/Yakutsk'?'selected':''):''}}>(GMT+09:00) Yakutsk</option>
                                    <option value="Australia/Adelaide"  {{$tz?($tz=='Australia/Adelaide'?'selected':''):''}}>(GMT+09:30) Adelaide</option>
                                    <option value="Australia/Darwin"  {{$tz?($tz=='Australia/Darwin'?'selected':''):''}}>(GMT+09:30) Darwin</option>
                                    <option value="Australia/Brisbane"  {{$tz?($tz=='Australia/Brisbane'?'selected':''):''}}>(GMT+10:00) Brisbane</option>
                                    <option value="Australia/Canberra"  {{$tz?($tz=='Australia/Canberra'?'selected':''):''}}>(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                    <option value="Australia/Hobart"  {{$tz?($tz=='Australia/Hobart'?'selected':''):''}}>(GMT+10:00) Hobart</option>
                                    <option value="Pacific/Guam"  {{$tz?($tz=='Pacific/Guam'?'selected':''):''}}>(GMT+10:00) Guam, Port Moresby</option>
                                    <option value="Asia/Vladivostok"  {{$tz?($tz=='Asia/Vladivostok'?'selected':''):''}}>(GMT+10:00) Vladivostok</option>
                                    <option value="Asia/Magadan"  {{$tz?($tz=='Asia/Magadan'?'selected':''):''}}>(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                    <option value="Pacific/Auckland"  {{$tz?($tz=='Pacific/Auckland'?'selected':''):''}}>(GMT+12:00) Auckland, Wellington</option>
                                    <option value="Pacific/Fiji"  {{$tz?($tz=='Pacific/Fiji'?'selected':''):''}}>(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                    <option value="Pacific/Tongatapu"  {{$tz?($tz=='Pacific/Tongatapu'?'selected':''):''}}>(GMT+13:00) Nuku'alofa</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-12">
                            @php($schedule_order=\App\Models\BusinessSetting::where('key','schedule_order')->first())
                            @php($schedule_order=$schedule_order?$schedule_order->value:0)
                            <div class="form-group">
                                <label class="input-label d-inline">{{__('messages.scheduled')}} {{__('messages.orders')}}</label><small style="color: red">
                                <!-- <span class="input-label-secondary" title="{{__('messages.customer_varification_toggle')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.customer_varification_toggle')}}"></span> -->
                                 *</small>
                                <div class="input-group input-group-md-down-break">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="1" name="schedule_order"
                                                   id="schedule_order" {{$schedule_order==1?'checked':''}}>
                                            <label class="custom-control-label" for="schedule_order">{{__('messages.on')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="0" name="schedule_order"
                                                   id="schedule_order2" {{$schedule_order==0?'checked':''}}>
                                            <label class="custom-control-label" for="schedule_order2">{{__('messages.off')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-12">
                            @php($order_confirmation_model=\App\Models\BusinessSetting::where('key','order_confirmation_model')->first())
                            @php($order_confirmation_model=$order_confirmation_model?$order_confirmation_model->value:'deliveryman')
                            <div class="form-group">
                                <label class="input-label d-inline">{{__('messages.order_confirmation_model')}}</label><small style="color: red">
                                <span class="input-label-secondary" title="{{__('messages.order_confirmation_model_hint')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}"></span>
                                 *</small>
                                <div class="input-group input-group-md-down-break">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="restaurant" name="order_confirmation_model"
                                                   id="order_confirmation_model" {{$order_confirmation_model=='restaurant'?'checked':''}}>
                                            <label class="custom-control-label" for="order_confirmation_model">{{__('messages.restaurant')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="deliveryman" name="order_confirmation_model"
                                                   id="order_confirmation_model2" {{$order_confirmation_model=='deliveryman'?'checked':''}}>
                                            <label class="custom-control-label" for="order_confirmation_model2">{{__('messages.deliveryman')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4 col-12">
                            @php($admin_order_notification=\App\Models\BusinessSetting::where('key','admin_order_notification')->first())
                            @php($admin_order_notification=$admin_order_notification?$admin_order_notification->value:0)
                            <div class="form-group">
                                <label class="input-label d-inline">{{__('messages.admin')}} {{__('messages.order')}} {{__('messages.notification')}}</label><small style="color: red">*</small>
                                <div class="input-group input-group-md-down-break">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="1" name="admin_order_notification"
                                                   id="aon1" {{$admin_order_notification==1?'checked':''}}>
                                            <label class="custom-control-label" for="aon1">{{__('messages.on')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="0" name="admin_order_notification"
                                                   id="aon2" {{$admin_order_notification==0?'checked':''}}>
                                            <label class="custom-control-label" for="aon2">{{__('messages.off')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            @php($ev=\App\Models\BusinessSetting::where('key','customer_verification')->first())
                            @php($ev=$ev?$ev->value:0)
                            <div class="form-group">
                                <label class="input-label d-inline">{{__('messages.customer')}} {{__('messages.verification')}}</label><small style="color: red"><span
                                        class="input-label-secondary" title="{{__('messages.customer_varification_toggle')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.customer_varification_toggle')}}"></span> *</small>
                                <div class="input-group input-group-md-down-break">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="1" name="customer_verification"
                                                   id="ev1" {{$ev==1?'checked':''}}>
                                            <label class="custom-control-label" for="ev1">{{__('messages.on')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="0" name="customer_verification"
                                                   id="ev2" {{$ev==0?'checked':''}}>
                                            <label class="custom-control-label" for="ev2">{{__('messages.off')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            @php($odc=\App\Models\BusinessSetting::where('key','order_delivery_verification')->first())
                            @php($odc=$odc?$odc->value:0)
                            <div class="form-group">
                                <label class="input-label d-inline">{{__('messages.order')}} {{__('messages.delivery')}} {{__('messages.verification')}}</label><small style="color: red"><span
                                        class="input-label-secondary" title="{{__('messages.order_varification_toggle')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.order_varification_toggle')}}"></span> *</small>
                                <div class="input-group input-group-md-down-break">
                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="1" name="odc"
                                                   id="odc1" {{$odc==1?'checked':''}}>
                                            <label class="custom-control-label" for="odc1">{{__('messages.on')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->

                                    <!-- Custom Radio -->
                                    <div class="form-control">
                                        <div class="custom-control custom-radio">
                                            <input type="radio" class="custom-control-input" value="0" name="odc"
                                                   id="odc2" {{$odc==0?'checked':''}}>
                                            <label class="custom-control-label" for="odc2">{{__('messages.off')}}</label>
                                        </div>
                                    </div>
                                    <!-- End Custom Radio -->
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 col-12">
                        @php($admin_commission=\App\Models\BusinessSetting::where('key','admin_commission')->first())
                            <div class="form-group p-2">
                                <label class="input-label text-capitalize" for="admin_commission">{{__('messages.default_admin_commission')}}</label>
                                <input type="number" name="admin_commission" class="form-control" id="admin_commission" value="{{$admin_commission?$admin_commission->value:0}}" min="0" max="100" required>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                        @php($free_delivery_over=\App\Models\BusinessSetting::where('key','free_delivery_over')->first())
                            <div class="form-group p-2 border">
                                <label class="input-label d-inline text-capitalize" for="free_delivery_over">{{__('messages.free_delivery_over')}} ({{\App\CentralLogics\Helpers::currency_symbol()}})</label>
                                <label class="switch ml-3 float-right">
                                    <input type="checkbox" class="status" name="free_delivery_over_status" id="free_delivery_over_status" value="1"
                                        {{isset($free_delivery_over->value)?'checked':''}}>
                                    <span class="slider round"></span>
                                </label>
                                <input type="number" name="free_delivery_over" class="form-control" id="free_delivery_over" value="{{$free_delivery_over?$free_delivery_over->value:0}}" min="0" step=".01" required {{isset($free_delivery_over->value)?'':'readonly'}}>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                        @php($minimum_shipping_charge=\App\Models\BusinessSetting::where('key','minimum_shipping_charge')->first())
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize" for="minimum_shipping_charge">{{__('messages.minimum_shipping_charge')}}</label>
                                <input type="number" name="minimum_shipping_charge" class="form-control" id="minimum_shipping_charge"  min="0" step=".01" value="{{$minimum_shipping_charge?$minimum_shipping_charge->value:0}}" required>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                        @php($per_km_shipping_charge=\App\Models\BusinessSetting::where('key','per_km_shipping_charge')->first())
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize" for="per_km_shipping_charge">{{__('messages.per_km_shipping_charge')}}</label>
                                <input type="number" name="per_km_shipping_charge" class="form-control" id="per_km_shipping_charge"  min="0" step=".01" value="{{$per_km_shipping_charge?$per_km_shipping_charge->value:0}}" required>
                            </div>
                        </div>

                        <div class="col-md-4 col-12">
                        @php($dm_maximum_orders=\App\Models\BusinessSetting::where('key','dm_maximum_orders')->first())
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize" for="dm_maximum_orders">{{__('messages.dm_maximum_order')}}</label><small style="color: red"><span
                                        class="input-label-secondary" title="{{__('messages.dm_maximum_order_hint')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.dm_maximum_order_hint')}}"></span> *</small>
                                <input type="number" name="dm_maximum_orders" class="form-control" id="dm_maximum_orders"  min="1" value="{{$dm_maximum_orders?$dm_maximum_orders->value:1}}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        @php($phone=\App\Models\BusinessSetting::where('key','phone')->first())
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">{{__('messages.phone')}}</label>
                                <input type="text" value="{{$phone->value??''}}"
                                       name="phone" class="form-control"
                                       placeholder="" required>
                            </div>
                        </div>
                        @php($email=\App\Models\BusinessSetting::where('key','email_address')->first())
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">{{__('messages.email')}}</label>
                                <input type="email" value="{{$email->value??''}}"
                                       name="email" class="form-control" placeholder=""
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            @php($address=\App\Models\BusinessSetting::where('key','address')->first())
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">{{__('messages.address')}}</label>
                                <textarea type="text" id="address"
                                       name="address" class="form-control" placeholder="" rows="1"
                                       required>{{$address->value??''}}</textarea>
                            </div>
                            @php($default_location=\App\Models\BusinessSetting::where('key','default_location')->first())
                            @php($default_location=$default_location->value?json_decode($default_location->value, true):0)
                            <div class="form-group">
                                <label class="input-label text-capitalize d-inline" for="latitude">{{__('messages.latitude')}}<span
                                        class="input-label-secondary" title="{{__('messages.click_on_the_map_select_your_defaul_location')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.click_on_the_map_select_your_defaul_location')}}"></span></label>    
                                <input type="text" id="latitude"
                                       name="latitude" class="form-control d-inline"
                                       placeholder="Ex : -94.22213" value="{{$default_location?$default_location['lat']:0}}" required readonly>
                            </div>
                            <div class="form-group">
                                <label class="input-label d-inline text-capitalize" for="longitude">{{__('messages.longitude')}}<span
                                        class="input-label-secondary" title="{{__('messages.click_on_the_map_select_your_defaul_location')}}"><img src="{{asset('/public/assets/admin/img/info-circle.svg')}}" alt="{{__('messages.click_on_the_map_select_your_defaul_location')}}"></span></label>
                                <input type="text" 
                                       name="longitude" class="form-control"
                                       placeholder="Ex : 103.344322" id="longitude" value="{{$default_location?$default_location['lng']:0}}" required readonly>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <input id="pac-input" class="controls rounded" style="height: 3em;width:fit-content;" title="{{__('messages.search_your_location_here')}}" type="text" placeholder="{{__('messages.search_here')}}"/>
                            <div id="location_map_canvas"></div>
                        </div>
                    </div>

                    <div class="row">
                    @php($footer_text=\App\Models\BusinessSetting::where('key','footer_text')->first())
                        <div class="col-12">
                            <div class="form-group">
                                <label class="input-label d-inline" for="exampleFormControlInput1">{{__('messages.footer')}} {{__('messages.text')}}</label>
                                <textarea type="text" value=""
                                       name="footer_text" class="form-control" placeholder=""
                                       required>{{$footer_text->value??''}}</textarea>
                            </div>
                        </div>
                    </div>

                    @php($logo=\App\Models\BusinessSetting::where('key','logo')->first())
                    @php($logo=$logo->value??'')
                    <div class="form-group">
                        <label class="input-label d-inline">{{__('messages.logo')}}</label><small style="color: red">* ( {{__('messages.ratio')}} 3:1 )</small>
                        <div class="custom-file">
                            <input type="file" name="logo" id="customFileEg1" class="custom-file-input"
                                   accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                            <label class="custom-file-label" for="customFileEg1">{{__('messages.choose')}} {{__('messages.file')}}</label>
                        </div>
                        <hr>
                        <center>
                            <img style="height: 100px;border: 1px solid; border-radius: 10px;" id="viewer"
                                 onerror="this.src='{{asset('public/assets/admin/img/160x160/img2.jpg')}}'"
                                 src="{{asset('storage/app/public/business/'.$logo)}}" alt="logo image"/>
                        </center>
                    </div>
                    <hr>
                    <button type="{{env('APP_MODE')!='demo'?'submit':'button'}}" onclick="{{env('APP_MODE')!='demo'?'':'call_demo()'}}" class="btn btn-primary mb-2">{{trans('messages.submit')}}</button>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>

        function maintenance_mode() {
        @if(env('APP_MODE')=='demo')
            toastr.warning('Sorry! You can not enable maintainance mode in demo!');
        @else    
            Swal.fire({
                title: 'Are you sure?',
                text: 'Be careful before you turn on/off maintenance mode',
                type: 'warning',
                showCancelButton: true,
                cancelButtonColor: 'default',
                confirmButtonColor: '#377dff',
                cancelButtonText: 'No',
                confirmButtonText: 'Yes',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.get({
                        url: '{{route('admin.maintenance-mode')}}',
                        contentType: false,
                        processData: false,
                        beforeSend: function () {
                            $('#loading').show();
                        },
                        success: function (data) {
                            toastr.success(data.message);
                        },
                        complete: function () {
                            $('#loading').hide();
                        },
                    });
                } else {
                    location.reload();
                }
            })
        @endif
        };

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{\App\Models\BusinessSetting::where('key', 'map_api_key')->first()->value}}&libraries=places&v=3"></script>
    <script>
        function initAutocomplete() {
            var myLatLng = { lat: {{$default_location?$default_location['lat']:'-33.8688'}}, lng: {{$default_location?$default_location['lng']:'151.2195'}} };
            const map = new google.maps.Map(document.getElementById("location_map_canvas"), {
                center: { lat: {{$default_location?$default_location['lat']:'-33.8688'}}, lng: {{$default_location?$default_location['lng']:'151.2195'}} },
                zoom: 13,
                mapTypeId: "roadmap",
            });

            var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
            });

            marker.setMap( map );
            var geocoder = geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function (mapsMouseEvent) {
                var coordinates = JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2);
                var coordinates = JSON.parse(coordinates);
                var latlng = new google.maps.LatLng( coordinates['lat'], coordinates['lng'] ) ;
                marker.setPosition( latlng );
                map.panTo( latlng );

                document.getElementById('latitude').value = coordinates['lat'];
                document.getElementById('longitude').value = coordinates['lng'];

                
                geocoder.geocode({ 'latLng': latlng }, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[1]) {
                            document.getElementById('address').innerHtml = results[1].formatted_address;
                        }
                    }
                });
            }); 
            // Create the search box and link it to the UI element.
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });
            let markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var mrkr = new google.maps.Marker({
                        map,
                        title: place.name,
                        position: place.geometry.location,
                    });
                    google.maps.event.addListener(mrkr, "click", function (event) {
                        document.getElementById('latitude').value = this.position.lat();
                        document.getElementById('longitude').value = this.position.lng();
                    });
                    
                    markers.push(mrkr);

                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        };
        $(document).on('ready', function () {
            initAutocomplete();
            @php($country=\App\Models\BusinessSetting::where('key','country')->first())

            @if($country)
            $("#country option[value='{{$country->value}}']").attr('selected', 'selected').change();
            @endif



            $("#free_delivery_over_status").on('change', function(){
                if($("#free_delivery_over_status").is(':checked')){
                    $('#free_delivery_over').removeAttr('readonly');
                } else {
                    $('#free_delivery_over').attr('readonly', true);
                    $('#free_delivery_over').val('0');
                }
            });
        });

        $(document).on("keydown", "input", function(e) {
          if (e.which==13) e.preventDefault();
        });
    </script>
@endpush
