@php
    /** @var \App\Models\User $user */
@endphp

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@stop

@section('content_header')
    <div class="d-flex justify-content-between align-items-center mx-auto">
        <h1 class="text-blue">{{ $user->first_name }}</h1>
        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger">Выйти</button>
        </form>

    </div>
@stop

@section('content')
    <div class="content-wrapper">
        <div class="row mx-auto">
            <div class="col-md-2 line-right">
                <h3>Все события</h3>
                <ul id="all-events-list" class="reset-padding">
                    {{-- тут будет список всех событий в который пользователь не учавствует--}}
                </ul>

                <h3>Мои события</h3>
                <ul id="user-events-list" class="reset-padding">
                    {{-- тут будет список всех событий в который пользователь принимает учавстие--}}
                </ul>

            </div>
            <div class="col-md-10">
                <h4>Добро пожаловать!</h4>
                <p>Выберите событие из меню слева!</p>
            </div>
        </div>
    </div>

    <!-- Модальное окно для отображения информации о пользователе -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Информация о пользователе</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Здесь будет информация о пользователе -->
                    <p id="userInfo"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    @parent
    <script>
        window.userId = {{ $user->id }};
    </script>
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection


