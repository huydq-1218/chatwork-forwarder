<?php

use App\Enums\WebhookStatus; ?>

@extends('layouts.app')

@section('content')
<ul class="breadcrumb breadcrumb-top">
    <li>Webhooks</li>
</ul>
<!-- END Datatables Header -->

<!-- Datatables Content -->
<div class="block full">
    <div class="block-title">
        <h2><strong>Webhooks list</strong></h2>
        <a href="{{ route('webhooks.create') }}" class="btn-pull-right btn btn-md btn-primary"><i class="fa fa-plus-circle"></i> Create</a>
    </div>

    <div class="table-responsive">
        <table class="table table-vcenter table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Name</th>
                    <th class="webhook-description">Description</th>
                    <th>Slack Room</th>
                    <th>Slack Room ID</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @include('webhooks.delete_webhook_confirm_modal')
                @if (count($webhooks) <= 0)
                    <p class="tbl-no-data"><i class="fa fa-info-circle"></i> No data</p>
                @else
                    @foreach ($webhooks as $webhook)
                        <tr class="item-{{ $webhook->id }} webhook-item">
                            <td>{{ Helper::indexNumber(app('request')->input('page'), config('paginate.perPage'), $loop->iteration) }}</td>
                            <td>{{ $webhook->name }}</td>
                            <td class="webhook-description">{{ $webhook->description }}</td>
                            <td>{{ $webhook->room_name }}</td>
                            <td>{{ $webhook->room_id }}</td>
                            <td class="pl-20">
                                @if($webhook->status === WebhookStatus::ENABLED)
                                    <div class="webhook-status label label-success">Enabled</div>
                                @else
                                    <div class="webhook-status label label-warning">Disabled</div>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($webhook->status == WebhookStatus::ENABLED)
                                    <button class="btn btn-sm btn-warning btn-disable-wh btn-enable-disable" data-toggle="modal" data-id="{{ $webhook->id }}" data-name="{{ $webhook->name }}" data-target="#exampleModal">Disable</button>
                                @else
                                    <button class="btn btn-sm btn-success btn-enable-wh btn-enable-disable" data-id="{{ $webhook->id }}" data-name="{{ $webhook->name }}">Enable</button>
                                @endif

                                <a class="btn btn-sm btn-default" href="{{ route('webhooks.edit', ['webhook' => $webhook]) }}"><i class="fa fa-pencil"></i> Edit</a>

                                {{ Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['webhooks.destroy', 'webhook' => $webhook, 'page' => request('page')],
                                    'style' => 'display:inline',
                                    'class' => 'form-delete'
                                ]) }}
                                {{ Form::button('<i class="fa fa-trash-o"></i> Delete' , [
                                    'type' => 'DELETE',
                                    'class' => 'btn btn-sm btn-danger delete-btn',
                                    'title' => 'Delete'
                                ]) }}
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        <div class="text-center pagination-wrapper"> {{ $webhooks->appends(['search' => Request::get('search')])->render() }} </div>
    </div>
</div>
<!-- END Datatables Content -->
@endsection
<script src="{{ mix('/js/custom.js') }}"></script>

<!-- Modal -->
<div class="modal fade" id="enableModal" tabindex="-1" role="dialog" aria-labelledby="enableModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" name="id">
                <h4 class="modal-title" id="enableModalLabel">Enable webhook: <span class="webhook-name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="model-content">Are you sure you want to enable this webhook?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-confirm-enable">Enable</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="disableModal" tabindex="-1" role="dialog" aria-labelledby="disableModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" name="id">
                <h4 class="modal-title" id="disableModalLabel">Disable webhook: <span class="webhook-name"></span></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="model-content">Are you sure you want to disable this webhook?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning btn-confirm-disable">Disable</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@section('js')
    <script src="{{ asset('/js/webhook.js') }}"></script>
    @include('common.flash-message')
@endsection
