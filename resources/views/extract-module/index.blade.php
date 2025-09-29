@extends('layout')

@push('head-script')
    <script src="{{ asset('js/extract-module/script.js') }}" defer></script>
@endpush('head-script')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Extract Module')}}</h1>
    </div>
    <div class="presentation">
        <h2 class="card-title">{{__('Extract Module Registration')}}</h2>
        <div class="flex-container">
            <div class="col_25 md_col">
                <label for="name">{{__('Name')}}:</label>
                <input type="text" form="form-module" name="name" id="name" value="{{old('name')}}" placeholder="{{__('name')}}" required>
            </div>
            <div class="col_25 md_col_33 sm_col">
                <label for="transaction_base_in_id">{{__('Transaction Base In')}}:</label>
                <select form="form-module" name="transaction_base_in_id" required>
                    <option value="" selected hidden>{{ __('Choose an Transaction Base') }}</option>
                    @foreach ($transactionBases as $transactionBaseItem)
                        <option value='{{ $transactionBaseItem->id }}'>{{ $transactionBaseItem->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col_25 md_col_33 sm_col">
                <label for="transaction_base_out_id">{{__('Transaction Base Out')}}:</label>
                <select form="form-module" name="transaction_base_out_id" required>
                    <option value="" selected hidden>{{ __('Choose an Transaction Base') }}</option>
                    @foreach ($transactionBases as $transactionBaseItem)
                        <option value='{{ $transactionBaseItem->id }}'>{{ $transactionBaseItem->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <input class="button-as-input" type="submit" form="form-module" value="{{ __('Save') }}">
            </div>
        </div>
        <form method="post" id="form-module" action="{{route('extract-module.store')}}"> @csrf </form>
    </div>
    <div class="presentation">
        <div class="flex-container">
            <h2>{{__('Extract Modules')}}</h2>
            <div class="col">
                <table>
                    @foreach($extractModules as $extractModule)
                    <tr>
                        <td class="td-item">
                            <span class="td-content">{{ $extractModule->name }} | {{ $extractModule->transactionBaseIn->title }} | {{ $extractModule->transactionBaseOut->title }}</span>
                            <form method="post" id="form-delete-{{$extractModule->id}}" action="{{route('extract-module.destroy')}}"> @csrf @method('DELETE') </form>
                            <div class="td-buttons">
                                <input type="hidden" form="form-delete-{{$extractModule->id}}" name="id" value={{$extractModule->id}}>
                                <button type="submit" form="form-delete-{{$extractModule->id}}">{{__('Delete')}}</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
