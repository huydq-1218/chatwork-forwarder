@extends('layouts.app')
@section('content')

    <ul class="breadcrumb breadcrumb-top">
        <li><a href="{{ route('bots.index') }}">Bots</a></li>
        <li>Create</li>
    </ul>

    <!-- Simple Editor Block -->
    <div class="block">
        <!-- Simple Editor Title -->
        <div class="block-title">
            <h2><strong>New Bot</strong></h2>
        </div>
        <!-- END Simple Editor Title -->

        <!-- Simple Editor Content -->
        @include('modals.cancel_modal')
        {{ Form::open(['url' => route('bots.store'), 'method' => 'post', 'class' => 'bot-form form-horizontal form-bordered']) }}
        <div class="col-xs-12">
            <button class="btn btn-sm btn-default float-right cancel-btn"><i class="fa fa-times"></i> Cancel</button>
            <button type="submit" class="btn btn-sm btn-primary float-right"><i class="fa fa-check"></i> Save</button>
        </div>
        <div class="form-group row">
            <div class="col-xs-4">
                <label class="field-compulsory required" for="bot_name">Bot name</label>
                <input type="text" id="bot_name" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter name">
                @error('name')
                <div class="has-error">
                    <span class="help-block">{{ $message }}</span>
                </div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-xs-4">
                <label class="field-compulsory required" for="bot_type">Type</label>
                <select id="bot_type" name="type" class="form-control">
                    <option value="{{ \App\Models\Bot::TYPE_CHATWORK }}" {{ (old('type') === strval(\App\Models\Bot::TYPE_CHATWORK) || !old('type')) ? "selected" : ""}}>Chatwork</option>
                    <option value="{{ \App\Models\Bot::TYPE_SUN_PROXY }}" {{ old('type') === strval(\App\Models\Bot::TYPE_SUN_PROXY) ? "selected" : "" }}>Sun CW Proxy</option>
                    <option value="{{ \App\Models\Bot::TYPE_SLACK }}" {{ old('type') === strval(\App\Models\Bot::TYPE_SLACK) ? "selected" : "" }}>Slack</option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-xs-4">
                <label class="field-compulsory required" for="bot_key">Bot Key</label>
                <input type="password" id="bot_key" name="bot_key" class="form-control" value="{{ old('bot_key') }}" placeholder="Enter bot key">
                @error('bot_key')
                <div class="has-error">
                    <span class="help-block">{{ $message }}</span>
                </div>
                @enderror
            </div>
        </div>
    {{ Form::close() }}
    <!-- END Simple Editor Content -->
    </div>
    <!-- END Simple Editor Block -->
@endsection
@section('js')
    <script src="{{ asset('/js/bot.js') }}"></script>
    @include('common.flash-message')
@endsection
