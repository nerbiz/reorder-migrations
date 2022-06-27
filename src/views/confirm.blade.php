@extends('reorder-migrations::layouts.base')

@section('content')
    <p>
        {{ __('The file renaming to be done is shown below:') }}
    </p>

    <form action="{{ route('reorderMigrations.confirm') }}" method="post">
        @csrf

        <table>
            <thead>
                <tr>
                    <th>Current filenames</th>
                    <th>New filenames</th>
                </tr>
            </thead>
            <tbody>
                @foreach($currentFilenames as $index => $filename)
                    <input type="hidden" name="current_filenames[]" value="{{ $filename }}">
                    <input type="hidden" name="new_filenames[]" value="{{ $newFilenames[$index] }}">

                    <tr>
                        <td>{{ $filename }}</td>
                        <td>{{ $newFilenames[$index] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" id="submit-button">
            {{ __('Confirm the changes') }}
        </button>

        <a class="cancel-button" href="{{ route('reorderMigrations.reorder') }}">
            {{ __('Cancel the changes') }}
        </a>
    </form>
@endsection
