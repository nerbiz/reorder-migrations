@php
    use Nerbiz\ReorderMigrations\ReorderMigrationsServiceProvider;
@endphp

<!DOCTYPE html>
<html lang="en">
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
            #file-list {
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

            #file-list {
                padding: 0;
                list-style-type: none;
            }

            .list-item {
                cursor: move;
                padding: 10px 15px;
                background-color: lightskyblue;
            }

            .list-item:not(:last-child) {
                border-bottom: 1px white solid;
            }

            .list-item-ghost {
                opacity: 0.5;
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

        <p>
            {{ __('Here you can reorder all the migration files of your Laravel application.') }}
        </p>

        <p class="warning">
            {{ __('Warning: this package assumes the default file format "yyyy_mm_dd_hhmmss_file_name.php".') }}<br>
            {{ __('If you use any other format, unexpected things will happen.') }}<br>
            {{ __('In any case, make sure you have a backup, or use a version control system.') }}
        </p>

        <form action="{{ route('reorderMigrations.apply') }}" method="post">
            @csrf

            <p>
                {{ __('Select the file naming mode') }}<br>
                <label>
                    <input type="radio" name="naming_mode" value="current" @checked(in_array(old('naming_mode'), ['current', null]))>
                    {{ __('Keep the current filename dates') }}
                </label><br>
                <label>
                    <input type="radio" name="naming_mode" value="custom" @checked(old('naming_mode') === 'custom')>
                    {{ __('Use a custom prefix:') }}
                    <input type="text" name="filename_prefix" value="{{ old('filename_prefix', sprintf(__('%d_01_01'), date('Y'))) }}"><br>
                    {{ __('(The custom prefix will be appended with _000001, _000002, etc.)') }}
                </label><br>
            </p>

            <p>
                {{ __('Drag the files in the order you like:') }}
            </p>

            <ul id="file-list">
                @foreach($filenames as $fileName)
                    <li class="list-item">
                        <input type="hidden" name="filenames[]" value="{{ $fileName }}">
                        {{ $fileName }}
                    </li>
                @endforeach
            </ul>

            <button type="submit" id="submit-button">
                {{ __('Save order') }}
            </button>
        </form>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js" integrity="sha512-Eezs+g9Lq4TCCq0wae01s9PuNWzHYoCMkE97e2qdkYthpI0pzC3UGB03lgEHn2XM85hDOUF6qgqqszs+iXU4UA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            const fileList = document.querySelector('#file-list');

            new Sortable(fileList, {
                animation: 150,
                ghostClass: 'list-item-ghost',
            });
        </script>
    </body>
</html>
