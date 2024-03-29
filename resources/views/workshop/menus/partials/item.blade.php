<div class="box menu-item {{ $item->created_at->diffInSeconds() < 5 ? 'is-new' : '' }}">
    <input
        type="hidden"
        name="menu[items][]"
        value="{{ $item->getKey() }}"
    />

    <div class="level">
        <div class="level-left">
            <div class="level-item">
                <div>
                    <h4 class="title is-4">
                        {{ $item }}
                    </h4>

                    <div class="is-size-6">
                        @isset($item->href)
                            <div class="menu-item-href">
                                <a
                                    href="{{ $item->href }}"
                                    target="_blank"
                                >
                                    {{ $item->href }}
                                </a>
                            </div>
                        @else
                            @isset($item->target, $item->target->url)
                                <div class="menu-item-target-url has-text-small">
                                    <a
                                        href="{{ $item->target->url }}"
                                        target="_blank"
                                    >
                                        {{ $item->target->url }}
                                    </a>
                                </div>
                            @endisset
                        @endisset
                    </div>
                </div>
            </div>
        </div>

        <div class="level-right">
            <div class="level-item">
                <div class="field has-addons">
                    <div class="control">
                        <a
                            class="button is-white edit-menu-item"
                            href="{{ route('workshop.menus.items.edit', [$item->menu, $item]) }}"
                            title="@lang('menus.actions.edit.label')"
                            rel="open-modal"
                        >
                            @icon('fa-pencil')
                        </a>
                    </div>

                    <div class="control">
                        <a
                            class="button is-white create-sub-menu-item"
                            href="{{ route('workshop.menus.items.create', [$item->menu, 'item' => ['parent_id' => $item->id]]) }}"
                            title="@lang('menus.items.actions.create.label')"
                        >
                            @icon('fa-plus')
                        </a>
                    </div>

                    <div class="control">
                        <a
                            class="button is-white destroy-menu-item"
                            href="{{ route('workshop.menus.items.destroy', [$item->menu, $item]) }}"
                            title="@lang('menus.items.actions.destroy.label')"
                        >
                            @icon('fa-trash-can')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if ($item->children->count() > 0)
    <ul class="ui-sortable">
        @foreach ($item->children as $child)
            <li>
                @include('workshop.menus.partials.item', ['item' => $child])
            </li>
        @endforeach
    </ul>
@endif

