@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Owner')}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <div class="row">
                    <div class="col_child">
                        <h2 class="card-title">{{__('Fill out the form')}}</h2>
                        <label for="name">{{__('Name')}}:</label>
                        <input type="text" form="form-insert" name="name" id="name" placeholder="Nome" required>
                        <label>{{__('Owner Status')}}:</label>
                        <div class="radio-container">
                            <label class="radio-option"><input type="radio" form="form-insert" name="active" value="1" checked>{{__('Active')}}</label>
                            <label class="radio-option"><input type="radio" form="form-insert" name="active" value="0">{{__('Inactive')}}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col_child">
                        <button type="submit" form="form-insert">{{__('Save')}}</button>
                    </div>
                </div>
                <form method="post" id="form-insert" action="{{route('owner.store')}}"> @csrf </form>
            </div>
            <div class="col">
                <h2 class="card-title">{{__('Owner List')}}</h2>
                <table>
                    @foreach($owners as $owner)
                    <tr>
                        <td class="td-item">
                            <span class="td-content">{{ $owner->name }}
                                @if(!$owner->active)
                                    <span class="tag">{{__('Inactive')}}</span>
                                @endif
                            </span>
                            <form method="post" id="form-update-{{$owner->id}}" action="{{route('owner.update')}}"> @csrf @method('PUT') </form>
                            <form method="get" id="form-list-{{$owner->id}}" action="{{route('owner.wallet.list', ['owner_id' => $owner->id])}}"></form>
                            <div class="td-buttons">
                                <input type="hidden" form="form-update-{{$owner->id}}" name="id" value={{$owner->id}}>
                                <input type="hidden" form="form-update-{{$owner->id}}" name="active" value={{!$owner->active}}>
                                @if ($owner->active)
                                    <button type="submit" form="form-update-{{$owner->id}}">{{__('Inactivate')}}</button>
                                @else
                                    <button type="submit" form="form-update-{{$owner->id}}">{{__('Activate')}}</button>
                                @endif
                                <button type="submit" form="form-list-{{$owner->id}}">{{__('Wallets')}}</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
