@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Configurations')}}</h1>
    </div>
    <div class="presentation">
        <h2 class="card-title">{{__('Backup')}}</h2>
        <div class="flex-container">
            <div class="col_50 md_col">
                <label for="backup_file">{{ __('Backup File (Data)') }}:</label>
                <input type="file" form="form-restore" accept=".txt,.sql,.csv,.xlsx" name="backup_file" id="backup_file" required/>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <input class="button-as-input" type="submit" form="form-restore" id="submit_button" value="{{ __('Restore') }}">
            </div>
            <div class="col_25 md_col_50 sm_col">
                <input class="button-as-input" type="submit" form="form-backup" id="submit_button" value="{{ __('Backup') }}">
            </div>
        </div>
        <form method="post" id="form-restore" action="{{route('configuration.restore')}} " enctype="multipart/form-data"> @csrf </form>
        <form method="post" id="form-backup" action="{{route('configuration.backup')}} "> @csrf </form>
    </div>
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <h2>{{__('Configurations')}}</h2>
            </div>
            @foreach($configurations as $configuration)
                <div class="col_50 col_sm">
                    <h4><label id="installmentData" class="label-as-input" title="{{ $configuration->description }}">{{ $configuration->name }}:</label></h4>
                </div>
                <div class="col_50 sm_col">
                    <label for="value">{{ $configuration->key }}</label>
                    <input type="text" form="form-config" name="{{ $configuration->key }}" value="{{ $configuration->value }}" required>
                </div>
            @endforeach
            <div class="col">
                <div class="flex-container">
                    <div class="col_child">
                        <button type="submit" form="form-config">{{__('Save')}}</button>
                    </div>
                </div>
            </div>
            <form method="post" id="form-config" action="{{route('configuration.update')}}"> @csrf @method('PUT') </form>
        </div>
    </div>
@endsection
