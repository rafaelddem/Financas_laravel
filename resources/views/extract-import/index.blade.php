@extends('layout')

@push('head-script')
    <script src="{{ asset('js/extract-import/script.js') }}" defer></script>
@endpush('head-script')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Extract Import')}}</h1>
    </div>
    <div class="presentation">
        <form method="post" id="form-extract" action="{{ route('extract-import.extract') }}" enctype="multipart/form-data">
            @csrf
            <h2 class="card-title">{{__('Extract Import')}}</h2>
            <div class="flex-container">
                <div class="col_25 md_col_50 sm_col">
                    <input class="button-as-input" type="submit" id="submit_button" value="{{ __('Extract') }}" disabled>
                </div>
                <div class="col_25 md_col_50 sm_col">
                    <label for="module_id">{{ __('Extract Module') }}:</label>
                    <select name="module_id" id="module_id">
                        <option value="" selected hidden>{{ __('Select A Extract Module') }}</option>
                        @foreach ($importOptions as $importOption)
                            <option value="{{ $importOption->id }}">{{ $importOption->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col_50 md_col">
                    <label for="extract_file">{{ __('Source Wallet') }}:</label>
                    <input type="file" accept=".txt,.csv,.xlsx" name="extract_file" id="extract_file" disabled />
                </div>
            </div>
        </form>
    </div>
@endsection
