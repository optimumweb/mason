@extends('workshop.configuration.layout')

@section('main')
    <form
        action="{{ route('workshop.configuration.locale.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h1 class="title is-1">
                        <a href="{{ route('workshop.configuration.locale.index') }}">
                            @icon('fa-arrow-left-long')
                            <span>@lang('locales.title')</span>
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
                        <span>@lang('locales.actions.save.label')</span>
                    </button>
                </div>
            </div>
        </div>

        @include('workshop.configuration.locales.partials.fields')
    </form>
@endsection
