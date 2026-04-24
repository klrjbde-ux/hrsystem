@extends('master')

@section('content')
<div class="container">
    <h2>My Tasks</h2>

    @foreach($tasks as $task)
        <div class="card mb-3">
            <div class="card-body">
                <h5>{{ $task->title }}</h5>
                <p>{{ $task->description }}</p>
                <span class="badge bg-{{ $task->status_color }}">
                    {{ ucfirst(str_replace('_',' ', $task->status)) }}
                </span>
                <p class="mt-2"><strong>Project:</strong> {{ $task->project->name }}</p>
            </div>
        </div>
    @endforeach
</div>
@endsection