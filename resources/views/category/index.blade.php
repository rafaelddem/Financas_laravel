@php
use App\Enums\Relevance;
@endphp

@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Category')}}</h1>
    </div>
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <input type="button" value="Novo" onclick="window.location='{{ route('category.create') }}'">
            </div>
        </div>
        <div class="flex-container">
            <div class="col">
                <table>
                    @foreach($categories as $category)
                    <tr>
                        <td class="td-item">
                            <span class="td-content">
                                {{ $category->name }}
                                <span class="tag">{{ __($category->relevance->name)}}</span>
                                @if(!$category->active)
                                    <span class="tag">{{__('Inactive')}}</span>
                                @endif
                            </span>
                            <form method="get" id="form-update-{{$category->id}}" action="{{route('category.edit', ['id' => $category->id])}}"></form>
                            <form method="post" id="form-delete-{{$category->id}}" action="{{route('category.destroy')}}"> @csrf @method('DELETE') </form>
                            <div class="td-buttons">
                                <button type="submit" form="form-update-{{$category->id}}">{{__('Edit')}}</button>
                                <input type="hidden" form="form-delete-{{$category->id}}" name="id" value={{$category->id}}>
                                <button type="submit" form="form-delete-{{$category->id}}">{{__('Delete')}}</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
