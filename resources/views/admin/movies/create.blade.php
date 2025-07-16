
<div class="container">
    <h2>{{ __('Add New Movie') }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">{{ __('Title') }}</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">{{ __('Description') }}</label>
            <textarea name="description" id="description" class="form-control" rows="3" required>{{ old('description') }}</textarea>
        </div>

        {{-- Genre --}}
        <div class="mb-3">
            <label for="genre" class="form-label">{{ __('Genre') }}</label>
            <input type="text" name="genre" id="genre" class="form-control" value="{{ old('genre') }}">
        </div>

        {{-- Release Year --}}
        <div class="mb-3">
            <label for="release_year" class="form-label">{{ __('Release Year') }}</label>
            <input type="number" name="release_year" id="release_year" class="form-control" value="{{ old('release_year') }}">
        </div>

        {{-- Language --}}
        <div class="mb-3">
            <label for="language" class="form-label">{{ __('Language') }}</label>
            <input type="text" name="language" id="language" class="form-control" value="{{ old('language') }}">
        </div>

        {{-- Poster (image upload) --}}
        <div class="mb-3">
            <label for="poster" class="form-label">{{ __('Poster') }}</label>
            <input type="file" name="poster" id="poster" class="form-control">
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary">{{ __('Save Movie') }}</button>
    </form>
</div>

