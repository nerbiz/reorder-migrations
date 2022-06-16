@extends('reorder-migrations::layouts.base')

@section('content')
    <p>
        {{ __('Here you can reorder all the migration files of your Laravel application.') }}
    </p>

    <p class="warning">
        {{ __('Warning: this package assumes the default file format "yyyy_mm_dd_hhmmss_file_name.php".') }}<br>
        {{ __('If you use any other format, unexpected things will happen.') }}<br>
        {{ __('In any case, make sure you have a backup, or use a version control system.') }}
    </p>

    <form action="{{ route('reorderMigrations.processReorder') }}" method="post">
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

        <p>
            {{ __('By clicking the button below, files will not be renamed yet, you can then confirm or cancel the action.') }}
        </p>

        <button type="submit" id="submit-button">
            {{ __('Preview the changes') }}
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
@endsection
