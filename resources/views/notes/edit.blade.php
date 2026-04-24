@extends('master')
@section('css')
@endsection
@section('content')
<main id="main" class="main">
<div class="container">
    <h3 class="mb-4 shadow-sm p-3 rounded bg-white">Edit Note</h3>

    <form action="{{ route('notes.update', $note->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $note->title }}" required>
            @error('title')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea name="content" id="content" class="form-control" rows="5" required>{{ $note->content }}</textarea>
            @error('content')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" id="date" class="form-control" value="{{ $note->date }}">
            @error('date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" name="time" id="time" class="form-control" value="{{ $note->time }}">
            @error('time')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Update Note</button>
    </form>
</div>
</div>
</main>
@endsection
