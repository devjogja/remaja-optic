@extends('layout.main') @section('content')

@if(session()->has('message'))
  <div class="alert alert-success alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('message') }}</div> 
@endif
@if(session()->has('not_permitted'))
  <div class="alert alert-danger alert-dismissible text-center"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>{{ session()->get('not_permitted') }}</div> 
@endif
<section class="forms">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h4>{{trans('file.General Setting')}}</h4>
                    </div>
                    <div class="card-body">
                        <p class="italic"><small>{{trans('file.The field labels marked with * are required input fields')}}.</small></p>
                        {!! Form::open(['route' => 'setting.generalStore', 'files' => true, 'method' => 'post']) !!}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>{{trans('file.Site Title')}} *</strong></label>
                                        <input type="text" name="site_title" class="form-control" value="@if($ezpos_general_setting_data){{$ezpos_general_setting_data->site_title}}@endif" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>{{trans('file.Site Logo')}}</strong></label>
                                        <input type="file" name="site_logo" class="form-control" value=""/>
                                    </div>
                                    @if($errors->has('site_logo'))
                                   <span>
                                       <strong>{{ $errors->first('site_logo') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>{{trans('file.Currency')}} *</strong></label>
                                        <input type="text" name="currency" class="form-control" value="@if($ezpos_general_setting_data){{$ezpos_general_setting_data->currency}}@endif" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>{{trans('file.Currency Position')}} *</strong></label><br>
                                        @if($ezpos_general_setting_data->currency_position == 'prefix')
                                        <label class="radio-inline">
                                            <input type="radio" name="currency_position" value="prefix" checked> {{trans('file.Prefix')}}
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="currency_position" value="suffix"> {{trans('file.Suffix')}}
                                        </label>
                                        @else
                                        <label class="radio-inline">
                                            <input type="radio" name="currency_position" value="prefix"> {{trans('file.Prefix')}}
                                        </label>
                                        <label class="radio-inline">
                                          <input type="radio" name="currency_position" value="suffix" checked> {{trans('file.Suffix')}}
                                        </label>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>{{trans('file.Time Zone')}}</strong></label>
                                        @if($ezpos_general_setting_data)
                                        <input type="hidden" name="timezone_hidden" value="{{env('APP_TIMEZONE')}}">
                                        @endif
                                        <select name="timezone" class="selectpicker form-control" data-live-search="true" title="Select TimeZone...">
                                            @foreach($zones_array as $zone)
                                            <option value="{{$zone['zone']}}">{{$zone['diff_from_GMT'] . ' - ' . $zone['zone']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="{{trans('file.submit')}}" class="btn btn-primary">
                                    </div>
                                </div> 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>{{trans('file.Staff Access')}} *</strong></label>
                                        <select name="staff_access" class="selectpicker form-control">
                                            @if($ezpos_general_setting_data->staff_access=='all')
                                                <option selected value="all"> {{trans('file.All Records')}}</option>
                                                <option value="own"> {{trans('file.Own Records')}}</option>
                                            @else
                                                <option value="all"> {{trans('file.All Records')}}</option>
                                                <option selected value="own"> {{trans('file.Own Records')}}</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>                      
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $("ul#setting").siblings('a').attr('aria-expanded','true');
    $("ul#setting").addClass("show");
    $("ul#setting #general-setting-menu").addClass("active");

    if($("input[name='timezone_hidden']").val()){
        $('select[name=timezone]').val($("input[name='timezone_hidden']").val());
        $('.selectpicker').selectpicker('refresh');
    }

    $('.selectpicker').selectpicker({
      style: 'btn-link',
    });

</script>
@endsection