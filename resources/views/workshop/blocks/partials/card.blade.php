<div
    id="block-{{ $block->getKey() }}"
    class="card"
>
    <div class="card-content">
        <div class="block">
            <h2 class="title is-2">
                <a href="{{ route('workshop.blocks.edit', [$block]) }}">
                    {{ $block->title ?? __('blocks.untitled') }}
                </a>
            </h2>
        </div>

        <div class="block block-meta">
            <div class="field is-grouped is-grouped-multiline">
                @isset($block->location_info)
                    <div
                        class="control block-location"
                        title="@lang('blocks.attributes.location')"
                    >
                        @icon('fa-square')
                        <span>{{ $block->location_info->title }}</span>
                    </div>
                @endisset

                @isset($block->locale)
                    <div
                        class="control block-locale"
                        title="@lang('blocks.attributes.locale')"
                    >
                        @icon('fa-language')
                        <span>{{ $block->locale }}</span>
                    </div>
                @endisset
            </div>
        </div>
    </div>

    <div class="card-footer">
        <a
            class="card-footer-item"
            href="{{ route('workshop.blocks.edit', [$block]) }}"
        >
            @icon('fa-pencil')
            <span class="is-hidden-mobile">@lang('blocks.actions.edit.label')</span>
        </a>

        <a
            class="card-footer-item"
            href="{{ route('workshop.blocks.destroy', [$block]) }}"
            data-confirm="@lang('general.confirm')"
        >
            @icon('fa-trash-can')
            <span class="is-hidden-mobile">@lang('blocks.actions.destroy.label')</span>
        </a>
    </div>
</div>
