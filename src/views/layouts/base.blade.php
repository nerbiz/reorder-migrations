@php
    use Nerbiz\ReorderMigrations\ReorderMigrationsServiceProvider;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', config('app.locale')) }}">
    <head>
        <title>{{ ReorderMigrationsServiceProvider::PACKAGE_NAME }}</title>
        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 20px;
                font-family: 'Helvetica Neue', 'Arial', sans-serif;
                background-color: white;
            }

            h1,
            p,
            #file-list,
            table {
                margin: 0 0 20px 0;
            }

            #notification-success,
            #notification-error {
                width: 600px;
                padding: 10px 15px;
                color: white;
            }

            #notification-success {
                background-color: forestgreen;
            }

            #notification-error {
                background-color: orangered;
            }

            .warning {
                color: orangered;
                font-weight: bold;
            }

            .cancel-button {
                margin-left: 50px;
            }

            #file-list {
                padding: 0;
                list-style-type: none;
            }

            .list-item {
                display: flex;
                align-items: center;
                padding: 10px 15px;
                height: 50px;
                background-color: lightskyblue;
            }

            .list-item:not(:last-child) {
                border-bottom: 1px white solid;
            }

            .list-item-ghost {
                opacity: 0.5;
            }

            .move-handle {
                cursor: move;
                display: inline-block;
                margin-top: -8px;
                margin-right: 10px;
                width: 50px;
                font-size: 50px;
                text-align: center;
            }

            table {
                border-collapse: collapse;
            }

            td {
                border: 1px grey solid;
                padding: 10px;
            }

            input[type='text'] {
                border-radius: 4px;
                border: 1px grey solid;
                padding: 5px 10px;
            }

            #submit-button {
                cursor: pointer;
                border-radius: 4px;
                border: 1px grey solid;
                padding: 5px 10px;
            }
        </style>
    </head>
    <body>
        <h1>
            {{ ReorderMigrationsServiceProvider::PACKAGE_NAME }}
        </h1>

        @if(session()->has('success'))
            <p id="notification-success">
                {{ session('success') }}
            </p>
        @endif

        @if($errors->any())
            @foreach($errors->all() as $error)
                <p id="notification-error">
                    {{ $error }}
                </p>
            @endforeach
        @endif

        @yield('content')
    </body>
</html>
