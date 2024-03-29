@extends('workshop.layout')

@section('content')
    <form
        class="section"
        action="{{ route('workshop.medium.index') }}"
        method="GET"
    >
        <div class="level">
            <div class="level-left">
                <div class="level-item">
                    <div>
                        <h1 class="title is-1">
                            <a href="{{ route('workshop.medium.index') }}">
                                @lang('media.title')
                            </a>
                        </h1>

                        <div class="pagination-count has-text-small">
                            {{ trans_choice('media.pagination', $media->count() , ['total' => $total]) }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="level-right">
                <div class="level-item">
                    @include('workshop.partials.search')
                </div>

                <div class="level-item">
                    @include('workshop.media.partials.filters')
                </div>

                <div class="level-item">
                    @include('workshop.partials.paginator')
                </div>

                <div class="level-item">
                    @include('workshop.media.partials.buttons.create')
                </div>
            </div>
        </div>

        <hr />

        @if ($media->count() > 0)
            <div class="columns is-multiline is-card-grid">
                @foreach ($media as $medium)
                    <div class="column is-4 is-3-desktop">
                        @include('workshop.media.partials.card', ['medium' => $medium])
                    </div>
                @endforeach
            </div>

            <hr />

            {{ $media->appends(request()->input())->links('workshop.partials.pagination') }}
        @else
            <div class="section is-medium has-text-centered">
                <p class="block no-records">
                    @lang('media.noRecords')
                </p>

                <p class="block">
                    @include('workshop.media.partials.buttons.create')
                </p>
            </div>
        @endif
    </form>
@endsection
