@extends('workshop.layout')

@section('content')
    <form
        class="section"
        action="{{ route('workshop.users.update', [$user]) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @method('PATCH')
        @csrf

        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h1 class="title is-1">
                        <a href="{{ route('workshop.users.index') }}">
                            @icon('fa-arrow-left-long')
                            <span>@lang('users.title')</span>
                        </a>
                    </h1>
                </div>
            </div>

            <div class="level-right">
                <div class="level-item">
                    <button
                        class="button save is-success"
                        type="submit"
                    >
                        @icon('fa-floppy-disk')
                        <span>@lang('users.actions.save.label')</span>
                    </button>
                </div>

                <div class="level-item">
                    <a
                        class="button is-danger"
                        href="{{ route('workshop.users.destroy', [$user]) }}"
                        data-confirm="@lang('general.confirm')"
                    >
                        @icon('fa-trash-can')
                        <span>@lang('users.actions.destroy.label')</span>
                    </a>
                </div>
            </div>
        </div>

        @include('workshop.users.partials.fields')
    </form>
@endsection
