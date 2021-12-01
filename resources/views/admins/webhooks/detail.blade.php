<?php use App\Enums\WebhookStatus; ?>

@extends('layouts.app')
@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li><a href="{{ route('admin.webhooks.index') }}">Webhooks</a></li>
    <li>Detail</li>
</ul>
<!-- Simple Editor Block -->
<div class="block">
    <!-- Simple Editor Title -->
    <div class="block-title">
        <h2><strong>Detail webhook</strong></h2>
    </div>
    <!-- END Simple Editor Title -->
    <!-- Simple Editor Content -->
    <div class="form-group row">
        <div class="col-xs-6">
            <label class="field-compulsory">Url</label>
            <div class="input-group">
                <input type="text" id="webhookUrl" class="form-control" value="{{ config('app.url').'/api/v1/webhooks/'.$webhook->token }}" readonly>
                <a class="input-group-addon" id="copyUrl" ><i class="fa fa-clipboard"></i></a>
            </div>
        </div>
        <div class="mt-15 col-xs-8">
            <label class="field-compulsory required">Webhook name</label>
            <input type="text" class="form-control" value="{{  $webhook->name }}" readonly>
        </div>
        <div class="mt-15 col-xs-4">
            <label class="field-compulsory required">Status</label>
            <input type="text" class="form-control" {{ $webhook->status == WebhookStatus::ENABLED ? 'value=Enabled' : 'value=Disabled' }} readonly>
        </div>
        <div class="mt-15 col-xs-12">
            <label class="field-compulsory required">Description</label>
            <textarea class="form-control" rows="5" readonly>{{ $webhook->description }}</textarea>
        </div>
    </div>
    <div class="block">
        <div class="block-title">
            <h2><strong>Chatbot</strong></h2>
        </div>
        <div class="form-group row">
            <div class="col-xs-4">
                <label class="field-compulsory required">Slack bot</label>
                <input type="text" readonly value="{{ $bot->name }}" class="form-control">
            </div>
            <div class="col-xs-4">
                <label class="field-compulsory required">Slack room</label>
                <input type="text" readonly value="{{ $webhook->room_name }}" class="form-control">
            </div>
            <div class="col-xs-4">
                <label class="field-compulsory required">Slack room id</label>
                <input type="text" readonly value="{{ $webhook->room_id }}" class="form-control">
            </div>
        </div>
    </div>
    <br/>
    <div class="block">
        <!-- Simple Editor Title -->
        <div class="block-title">
            <h2><strong>Payloads</strong></h2>
        </div>
        @if (count($payloads) <= 0)
            <div class="form-group row">
                <div class="col-xs-12">
                    No records
                </div>
            </div>
        @else
            <div class="form-group row">
                <div class="col-xs-6">
                    <label class="field-compulsory">Payload content</label>
                </div>
                <div class="col-xs-6">
                    <label class="field-compulsory">Payload condition</label>
                </div>
            </div>
            @include('webhooks.delete_confirm_modal')
            @foreach($payloads as $key => $payload)
            <div class="form-group row">
                <div class="col-xs-6">
                    <input type="text" class="form-control" value="{{ $payload->content }}" readonly>
                </div>
                <div class="col-xs-6">
                    @if($payload->conditions->isEmpty())
                        <input type="text" class="form-control" value="No conditions" readonly>
                        <div class="panel-heading">
                            <h4 class="panel-title"></h4>
                        </div>
                    @else
                        @foreach($payload->conditions as $key => $condition)
                        <input type="text" class="form-control" value="{{$condition->field}} {{$condition->operator}} {{$condition->value}}" readonly>
                        @endforeach
                    @endif
                </div>
            </div>
            @endforeach
        @endif
    </div>
    <!-- END Simple Editor Content -->
</div>

@endsection
@section('js')
    <script src="{{ asset('/js/webhook.js') }}"></script>
    @include('common.flash-message')
@endsection
