@php
  use Filament\Support\Enums\MaxWidth;

  $navigation = filament()->getNavigation();

//NAVIGATION TESTING
dd($navigation);


  $openSidebarClasses = 'fi-sidebar-open w-[--sidebar-width] translate-x-0 shadow-xl ring-1 ring-gray-950/5 dark:ring-white/10 rtl:-translate-x-0';
  $isRtl = __('filament-panels::layout.direction') === 'rtl';

@endphp

{{--@props([
  'navigation',
])
--}}

<x-filament-panels::layout.base :livewire="$livewire">
<header
  @class([ 
    'fi-header',
  ])
>
  <div 
    @class([ 
      'fi-topbar',
    ])
  >
    <div 
      @class([
        'site-branding'
      ])
    >
    @if ($homeUrl = filament()->getHomeUrl())
      <a {{ \Filament\Support\generate_href_html($homeUrl) }}>
          <x-filament-panels::logo />
      </a>
    @else
      <x-filament-panels::logo />
    @endif
    </div>
    <nav
      class="flex items-center gap-x-4 bg-white px-4 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 md:px-6 lg:px-8"
    >
      <div
          x-persist="topbar.end"
          class="ms-auto flex items-center gap-x-4"
      >
        @if (filament()->isGlobalSearchEnabled())
          @livewire(Filament\Livewire\GlobalSearch::class)
        @endif

        @if (filament()->auth()->check())
          @if (filament()->hasDatabaseNotifications())
            @livewire(Filament\Livewire\DatabaseNotifications::class, ['lazy' => true])
          @endif
          <x-filament-panels::user-menu />
        @else
          <x-topbar.guest-menu :actions="$this->getCachedHeaderActions()"/>
        @endif
      </div>
    </nav>
  </div>
  <div 
    @class([ 
      'navbar-main',
    ])
  >

{{--    <ul class="me-4 hidden items-center gap-x-4 lg:flex"> --}}
    <ul class="main-nav">
      @foreach ($navigation as $group)
{{--            @if ($groupLabel = $group->getLabel())
              <x-filament::dropdown
                  placement="bottom-start"
                  teleport
                  :attributes="\Filament\Support\prepare_inherited_attributes($group->getExtraTopbarAttributeBag())"
              >
                  <x-slot name="trigger">
                      <x-filament-panels::topbar.item
                          :active="$group->isActive()"
                          :icon="$group->getIcon()"
                      >
                          {{ $groupLabel }}
                      </x-filament-panels::topbar.item>
                  </x-slot>

                  @php
                      $lists = [];

                      foreach ($group->getItems() as $item) {
                          if ($childItems = $item->getChildItems()) {
                              $lists[] = [
                                  $item,
                                  ...$childItems,
                              ];
                              $lists[] = [];

                              continue;
                          }

                          if (empty($lists)) {
                              $lists[] = [$item];

                              continue;
                          }

                          $lists[count($lists) - 1][] = $item;
                      }

                      if (empty($lists[count($lists) - 1])) {
                          array_pop($lists);
                      }
                  @endphp

                  @foreach ($lists as $list)
                      <x-filament::dropdown.list>
                          @foreach ($list as $item)
                              @php
                                  $itemIsActive = $item->isActive();
                              @endphp

                              <x-filament::dropdown.list.item
                                  :badge="$item->getBadge()"
                                  :badge-color="$item->getBadgeColor()"
                                  :badge-tooltip="$item->getBadgeTooltip()"
                                  :color="$itemIsActive ? 'primary' : 'gray'"
                                  :href="$item->getUrl()"
                                  :icon="$itemIsActive ? ($item->getActiveIcon() ?? $item->getIcon()) : $item->getIcon()"
                                  tag="a"
                                  :target="$item->shouldOpenUrlInNewTab() ? '_blank' : null"
                              >
                                  {{ $item->getLabel() }}
                              </x-filament::dropdown.list.item>
                          @endforeach
                      </x-filament::dropdown.list>
                  @endforeach
              </x-filament::dropdown>
          @else
--}}                
    @foreach ($group->getItems() as $item)
      <x-filament-panels::topbar.item
        :active="$item->isActive()"
        :active-icon="$item->getActiveIcon()"
        :badge="$item->getBadge()"
        :badge-color="$item->getBadgeColor()"
        :badge-tooltip="$item->getBadgeTooltip()"
        :icon="$item->getIcon()"
        :should-open-url-in-new-tab="$item->shouldOpenUrlInNewTab()"
        :url="$item->getUrl()"
      >
        {{ $item->getLabel() }}
      </x-filament-panels::topbar.item>
    @endforeach
{{--            @endif --}}
      @endforeach
    </ul>
  </div>
</header>

<div 
  @class([ 'page'
  ])
>
  <main>{{ $slot }}</main>

  <aside
    x-data="{}"

    @if (filament()->hasTopNavigation())
      x-cloak
      x-bind:class="$store.sidebar.isOpen ? @js($openSidebarClasses) : '-translate-x-full rtl:translate-x-full'"
    @elseif (filament()->isSidebarFullyCollapsibleOnDesktop())
      x-cloak
      x-bind:class="$store.sidebar.isOpen ? @js($openSidebarClasses . ' ' . 'lg:sticky') : '-translate-x-full rtl:translate-x-full'"
    @else
      x-cloak="-lg"
      x-bind:class="
        $store.sidebar.isOpen
          ? @js($openSidebarClasses . ' ' . 'lg:sticky')
          : 'w-[--sidebar-width] -translate-x-full rtl:translate-x-full lg:sticky'
      "
    @endif
    {{
      $attributes->class([
        'fi-sidebar transition-all lg:transition-none',
        'lg:translate-x-0 rtl:lg:-translate-x-0' => ! (filament()->isSidebarCollapsibleOnDesktop() || filament()->isSidebarFullyCollapsibleOnDesktop() || filament()->hasTopNavigation()),
        'lg:-translate-x-full rtl:lg:translate-x-full' => filament()->hasTopNavigation(),
      ])
    }}
  >
    <div class="overflow-x-clip">
      <header
        class="fi-sidebar-header flex h-16 items-center bg-white px-6 ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 lg:shadow-sm"
      >
        @if (filament()->isSidebarCollapsibleOnDesktop())
          <x-filament::icon-button
            color="gray"
            :icon="$isRtl ? 'heroicon-o-chevron-left' : 'heroicon-o-chevron-right'"
            {{-- @deprecated Use `panels::sidebar.expand-button.rtl` instead of `panels::sidebar.expand-button` for RTL. --}}
            :icon-alias="$isRtl ? ['panels::sidebar.expand-button.rtl', 'panels::sidebar.expand-button'] : 'panels::sidebar.expand-button'"
            icon-size="lg"
            :label="__('filament-panels::layout.actions.sidebar.expand.label')"
            x-cloak
            x-data="{}"
            x-on:click="$store.sidebar.open()"
            x-show="! $store.sidebar.isOpen"
            class="mx-auto"
          />
        @endif

        @if (filament()->isSidebarCollapsibleOnDesktop() || filament()->isSidebarFullyCollapsibleOnDesktop())
          <x-filament::icon-button
            color="gray"
            :icon="$isRtl ? 'heroicon-o-chevron-right' : 'heroicon-o-chevron-left'"
            {{-- @deprecated Use `panels::sidebar.collapse-button.rtl` instead of `panels::sidebar.collapse-button` for RTL. --}}
            :icon-alias="$isRtl ? ['panels::sidebar.collapse-button.rtl', 'panels::sidebar.collapse-button'] : 'panels::sidebar.collapse-button'"
            icon-size="lg"
            :label="__('filament-panels::layout.actions.sidebar.collapse.label')"
            x-cloak
            x-data="{}"
            x-on:click="$store.sidebar.close()"
            x-show="$store.sidebar.isOpen"
            class="ms-auto hidden lg:flex"
          />
        @endif
      </header>
    </div>

    <nav
      class="fi-sidebar-nav flex-grow flex flex-col gap-y-7 overflow-y-auto overflow-x-hidden px-6 py-8"
      style="scrollbar-gutter: stable"
    >
      {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_NAV_START) }}

      @if (filament()->hasTenancy() && filament()->hasTenantMenu())
        <div
          @class([
            'fi-sidebar-nav-tenant-menu-ctn',
            '-mx-2' => ! filament()->isSidebarCollapsibleOnDesktop(),
          ])
          @if (filament()->isSidebarCollapsibleOnDesktop())
            x-bind:class="$store.sidebar.isOpen ? '-mx-2' : '-mx-4'"
          @endif
        >
          <x-filament-panels::tenant-menu />
        </div>
      @endif

      <ul class="fi-sidebar-nav-groups -mx-2 flex flex-col gap-y-7">
        @foreach ($navigation as $group)
        {{-- group or item? --}}

          <x-filament-panels::sidebar.group
            :active="$group->isActive()"
            :collapsible="$group->isCollapsible()"
            :icon="$group->getIcon()"
            :items="$group->getItems()"
            :label="$group->getLabel()"
            :attributes="\Filament\Support\prepare_inherited_attributes($group->getExtraSidebarAttributeBag())"
          />


        @endforeach
      </ul>

      <script>
        var collapsedGroups = JSON.parse(
          localStorage.getItem('collapsedGroups'),
        )

        if (collapsedGroups === null || collapsedGroups === 'null') {
          localStorage.setItem(
            'collapsedGroups',
            JSON.stringify(@js(
              collect($navigation)
                ->filter(fn (\Filament\Navigation\NavigationGroup $group): bool => $group->isCollapsed())
                ->map(fn (\Filament\Navigation\NavigationGroup $group): string => $group->getLabel())
                ->values()
            )),
          )
        }

        collapsedGroups = JSON.parse(
          localStorage.getItem('collapsedGroups'),
        )

        document
          .querySelectorAll('.fi-sidebar-group')
          .forEach((group) => {
            if (
              !collapsedGroups.includes(group.dataset.groupLabel)
            ) {
              return
            }

            // Alpine.js loads too slow, so attempt to hide a
            // collapsed sidebar group earlier.
            group.querySelector(
              '.fi-sidebar-group-items',
            ).style.display = 'none'
            group
                .classList.add('rotate-180')
          })
      </script>

      {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_NAV_END) }}
    </nav>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_FOOTER) }}
  </aside>
</div>

<footer></footer>

</x-filament-panels::layout.base>