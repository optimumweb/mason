@extends('workshop.configuration.layout')

@section('main')
    <form
        action="{{ route('workshop.configuration.entry-type.index') }}"
        method="GET"
    >
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <h1 class="title is-1">
                        @lang('entryTypes.title')
                    </h1>
                </div>
            </div>

            <div class="level-right">
                <div class="level-item">
                    @include('workshop.partials.search')
                </div>

                <div class="level-item">
                    @include('workshop.partials.paginator')
                </div>

                <div class="level-item">
                    @include('workshop.configuration.entry-types.partials.buttons.create')
                </div>
            </div>
        </div>

        <hr />

        @if ($entryTypes->count() > 0)
            <div class="columns is-multiline is-card-grid">
                @foreach ($entryTypes as $entryType)
                    <div class="column is-4 is-3-desktop">
                        @include('workshop.configuration.entry-types.partials.card')
                    </div>
                @endforeach
            </div>

            <hr />

            {{ $entryTypes->appends(request()->input())->links('workshop.partials.pagination') }}
        @else
            <div class="section is-medium has-text-centered">
                <p class="block no-records">
                    @lang('entryTypes.noRecords')
                </p>

                <p class="block">
                    @include('workshop.configuration.entry-types.partials.buttons.create')
                </p>
            </div>
        @endif
    </form>
@endsection
