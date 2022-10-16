@extends('backend.layout')

@section('content')
    <form
        class="section"
        action="{{ route('backend.users.update', [$user]) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @method('PATCH')
        @csrf

        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h1 class="title is-1">
                        <a href="{{ route('backend.users.index') }}">
                            @icon('fa-arrow-left-long')
                            <span>@lang('users.title')</span>
                        </a>
                    </h1>
                </div>
            </div>

            <div class="level-right">
                <div class="level-item">
                    <button
                        class="button is-dark"
                        type="submit"
                    >
                        @icon('fa-floppy-disk')
                        <span>@lang('users.actions.save.label')</span>
                    </button>
                </div>

                @if ($user->is_cancellable)
                    <div class="level-item">
                        <a
                            class="button"
                            href="{{ route('backend.users.destroy', [$user]) }}"
                        >
                            @icon('fa-ban')
                            <span>@lang('users.actions.cancel.label')</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        @include('backend.users.partials.fields')
    </form>
@endsection