@php
use App\Enums\Relevance;
@endphp

@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Notices')}}</h1>
    </div>
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                @if($notices->count() == 0)
                    <h4>{{__('There Are No Notices')}}</h4>
                @endif
                <table>
                    @foreach($notices as $notice)
                    <tr>
                        <td class="td-item">
                            @if ($notice->read)
                                <span class="td-content"><b>{{ $notice->title }}</b></span>
                            @else
                                <span class="td-content">{{ $notice->title }}</span>
                            @endif
                            <form method="get" id="form-update-{{$notice->id}}" action="{{route('notice.access', ['id' => $notice->id])}}"></form>
                            <form method="post" id="form-update-read-{{$notice->id}}" action="{{route('notice.read', ['id' => $notice->id])}}"> @csrf @method('PUT') </form>
                            <form method="post" id="form-delete-{{$notice->id}}" action="{{route('notice.destroy')}}"> @csrf @method('DELETE') </form>
                            <div class="td-buttons">
                                @if ($notice->link)
                                    <button type="submit" form="form-update-{{$notice->id}}"><i class="fa-solid fa-arrow-right-to-bracket"></i></button>
                                @endif
                                @if ($notice->read)
                                    <input type="hidden" form="form-update-read-{{$notice->id}}" name="read" value=true>
                                    <button type="submit" form="form-update-read-{{$notice->id}}"><i class="fa-solid fa-envelope"></i></button>
                                @else
                                    <input type="hidden" form="form-update-read-{{$notice->id}}" name="read" value=false>
                                    <button type="submit" form="form-update-read-{{$notice->id}}"><i class="fa-solid fa-envelope-open"></i></button>
                                @endif
                                <input type="hidden" form="form-delete-{{$notice->id}}" name="id" value={{$notice->id}}>
                                <button type="submit" form="form-delete-{{$notice->id}}"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
