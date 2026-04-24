@extends('master')

@section('content')

@php
use Illuminate\Support\Str;
@endphp

<main id="main" class="main">
    <div class="container-fluid">

        <section class="section py-4">

            {{-- Success --}}
            @if(session('success'))
            <div id="successAlert" class="alert alert-success">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function() {
                    let alert = document.getElementById('successAlert');
                    if (alert) {
                        alert.style.transition = "opacity .5s";
                        alert.style.opacity = "0";
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 5000);
            </script>
            @endif

            <div class="row">
                <div class="col-12">

                    <div class="card shadow-sm">
                        <div class="card-body">

                            @hasanyrole('admin|hr_manager')

                            {{-- FILTER BAR --}}
                            {{-- FILTER BAR --}}
                            <div class="card shadow-sm mb-3 border-0">
                                <div class="card-body py-3">

                                    <form method="GET"
                                        action="{{ route('officepolicy.index') }}"
                                        class="row g-3 align-items-end">

                                        {{-- Title --}}
                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <label class="form-label fw-semibold small mb-1">Title</label>
                                            <input type="text"
                                                name="title"
                                                value="{{ request('title') }}"
                                                class="form-control form-control-sm"
                                                placeholder="Search title">
                                        </div>

                                        {{-- Status --}}
                                        <div class="col-lg-2 col-md-2 col-sm-6">
                                            <label class="form-label fw-semibold small mb-1">Status</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">All</option>
                                                <option value="Active" {{ request('status')=='Active'?'selected':'' }}>Active</option>
                                                <option value="Inactive" {{ request('status')=='Inactive'?'selected':'' }}>Inactive</option>
                                            </select>
                                        </div>

                                        {{-- Created --}}
                                        <div class="col-lg-3 col-md-3 col-sm-6">
                                            <label class="form-label fw-semibold small mb-1">Created</label>
                                            <input type="date"
                                                name="created_at"
                                                value="{{ request('created_at') }}"
                                                class="form-control form-control-sm">
                                        </div>

                                        {{-- Buttons --}}
                                        {{-- Buttons --}}
                                        <div class="col-lg-3 col-md-4 col-sm-12">
                                            <div class="d-flex gap-2 justify-content-lg-end justify-content-md-end justify-content-start">

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    Search
                                                </button>

                                                <a href="{{ route('officepolicy.index') }}"
                                                    class="btn btn-outline-secondary btn-sm">
                                                    Reset
                                                </a>

                                                <a href="{{ route('officepolicy.create') }}"
                                                    class="btn btn-success btn-sm">
                                                    Add Policy
                                                </a>

                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>

                            @endhasanyrole

                            {{-- TABLE --}}
                            <div class="table-responsive">
                                <table class="table table-borderless table-hover align-middle mb-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:60px">#</th>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th style="width:120px">Status</th>
                                            <th style="width:140px">Created</th>

                                            @hasanyrole('admin|hr_manager')
                                            <th style="width:160px">Action</th>
                                            @endhasanyrole
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse($policies as $policy)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">{{ $policy->title }}</td>
                                            <td style="max-width:340px">
                                                {{ Str::limit($policy->description,100) }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $policy->status=='Active'?'bg-success':'bg-danger' }}">
                                                    {{ $policy->status }}
                                                </span>
                                            </td>
                                            <td>{{ $policy->created_at->format('d M Y') }}</td>

                                            @hasanyrole('admin|hr_manager')
                                            <td>
                                                <div class="d-flex gap-2 flex-wrap">
                                                    <a href="{{ route('officepolicy.edit',$policy->id) }}"
                                                        class="btn btn-warning btn-sm">Edit</a>
                                                    <form action="{{ route('officepolicy.destroy',$policy->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Delete this policy?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-sm">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                            @endhasanyrole
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">
                                                No policies found
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

        </section>
    </div>
</main>

@endsection