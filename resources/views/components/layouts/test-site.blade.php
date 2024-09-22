
@php
  use Filament\Support\Enums\MaxWidth;

  $navigation = filament()->getNavigation();
@endphp

<!DOCTYPE html>
<html
  lang="{{ str_replace('_', '-', app()->getLocale()) }}"
  dir="{{ __('filament-panels::layout.direction') ?? 'ltr' }}"
  @class([
      'fi min-h-screen',
      'dark' => filament()->hasDarkModeForced(),
  ])
>
  <head>
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::HEAD_START, scopes: $livewire->getRenderHookScopes()) }}

    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    @if ($favicon = filament()->getFavicon())
        <link rel="icon" href="{{ $favicon }}" />
    @endif

    @php
        $title = trim(strip_tags(($livewire ?? null)?->getTitle() ?? ''));
        $brandName = trim(strip_tags(filament()->getBrandName()));
    @endphp

    <title>
        {{ filled($title) ? "{$title} - " : null }} {{ $brandName }}
    </title>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::STYLES_BEFORE, scopes: $livewire->getRenderHookScopes()) }}

    <style>
        [x-cloak=''],
        [x-cloak='x-cloak'],
        [x-cloak='1'] {
            display: none !important;
        }

        @media (max-width: 1023px) {
            [x-cloak='-lg'] {
                display: none !important;
            }
        }

        @media (min-width: 1024px) {
            [x-cloak='lg'] {
                display: none !important;
            }
        }
    </style>

    @filamentStyles

    {{ filament()->getTheme()->getHtml() }}
    {{ filament()->getFontHtml() }}

    <style>
        :root {
            --font-family: '{!! filament()->getFontFamily() !!}';
            --sidebar-width: {{ filament()->getSidebarWidth() }};
            --collapsed-sidebar-width: {{ filament()->getCollapsedSidebarWidth() }};
            --default-theme-mode: {{ filament()->getDefaultThemeMode()->value }};
        }
    </style>

    @stack('styles')

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::STYLES_AFTER, scopes: $livewire->getRenderHookScopes()) }}

    @if (! filament()->hasDarkMode())
        <script>
            localStorage.setItem('theme', 'light')
        </script>
    @elseif (filament()->hasDarkModeForced())
        <script>
            localStorage.setItem('theme', 'dark')
        </script>
    @else
        <script>
            const theme = localStorage.getItem('theme') ?? @js(filament()->getDefaultThemeMode()->value)

            if (
                theme === 'dark' ||
                (theme === 'system' &&
                    window.matchMedia('(prefers-color-scheme: dark)')
                        .matches)
            ) {
                document.documentElement.classList.add('dark')
            }
        </script>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::HEAD_END, scopes: $livewire->getRenderHookScopes()) }}
  </head>

  <body
    {{ $attributes
      ->merge(($livewire ?? null)?->getExtraBodyAttributes() ?? [], escape: false)
      ->class([
        'fi-body',
        'fi-panel-' . filament()->getId(),
        'min-h-screen bg-gray-50 font-normal text-gray-950 antialiased dark:bg-gray-950 dark:text-white',
      ]) }}
  >
    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::BODY_START, scopes: $livewire->getRenderHookScopes()) }}

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_BEFORE, scopes: $livewire->getRenderHookScopes()) }}

        <x-filament-panels::topbar :navigation="$navigation" />

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::TOPBAR_AFTER, scopes: $livewire->getRenderHookScopes()) }}

    <div
      class="fi-layout flex min-h-screen w-full flex-row overflow-x-clip"
    >
      <div
        @if (filament()->isSidebarCollapsibleOnDesktop())
          x-data="{}"
          x-bind:class="{
              'fi-main-ctn-sidebar-open': $store.sidebar.isOpen,
          }"
          x-bind:style="'display: flex; opacity:1;'" {{-- Mimics `x-cloak`, as using `x-cloak` causes visual issues with chart widgets --}}
        @elseif (filament()->isSidebarFullyCollapsibleOnDesktop())
          x-data="{}"
          x-bind:class="{
              'fi-main-ctn-sidebar-open': $store.sidebar.isOpen,
          }"
          x-bind:style="'display: flex; opacity:1;'" {{-- Mimics `x-cloak`, as using `x-cloak` causes visual issues with chart widgets --}}
        @elseif (! (filament()->isSidebarCollapsibleOnDesktop() || filament()->isSidebarFullyCollapsibleOnDesktop() || filament()->hasTopNavigation() || (! filament()->hasNavigation())))
          x-data="{}"
          x-bind:style="'display: flex; opacity:1;'" {{-- Mimics `x-cloak`, as using `x-cloak` causes visual issues with chart widgets --}}
        @endif
        @class([
          'fi-main-ctn w-screen flex-1 flex-col',
          'h-full opacity-0 transition-all' => filament()->isSidebarCollapsibleOnDesktop() || filament()->isSidebarFullyCollapsibleOnDesktop(),
          'flex',
        ])
      >
        <main
            @class([
                'fi-main mx-auto h-full w-full px-4 md:px-6 lg:px-8',
                match ($maxContentWidth ??= (filament()->getMaxContentWidth() ?? MaxWidth::SevenExtraLarge)) {
                    MaxWidth::ExtraSmall, 'xs' => 'max-w-xs',
                    MaxWidth::Small, 'sm' => 'max-w-sm',
                    MaxWidth::Medium, 'md' => 'max-w-md',
                    MaxWidth::Large, 'lg' => 'max-w-lg',
                    MaxWidth::ExtraLarge, 'xl' => 'max-w-xl',
                    MaxWidth::TwoExtraLarge, '2xl' => 'max-w-2xl',
                    MaxWidth::ThreeExtraLarge, '3xl' => 'max-w-3xl',
                    MaxWidth::FourExtraLarge, '4xl' => 'max-w-4xl',
                    MaxWidth::FiveExtraLarge, '5xl' => 'max-w-5xl',
                    MaxWidth::SixExtraLarge, '6xl' => 'max-w-6xl',
                    MaxWidth::SevenExtraLarge, '7xl' => 'max-w-7xl',
                    MaxWidth::Full, 'full' => 'max-w-full',
                    MaxWidth::MinContent, 'min' => 'max-w-min',
                    MaxWidth::MaxContent, 'max' => 'max-w-max',
                    MaxWidth::FitContent, 'fit' => 'max-w-fit',
                    MaxWidth::Prose, 'prose' => 'max-w-prose',
                    MaxWidth::ScreenSmall, 'screen-sm' => 'max-w-screen-sm',
                    MaxWidth::ScreenMedium, 'screen-md' => 'max-w-screen-md',
                    MaxWidth::ScreenLarge, 'screen-lg' => 'max-w-screen-lg',
                    MaxWidth::ScreenExtraLarge, 'screen-xl' => 'max-w-screen-xl',
                    MaxWidth::ScreenTwoExtraLarge, 'screen-2xl' => 'max-w-screen-2xl',
                    default => $maxContentWidth,
                },
            ])
        >
          {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_START, scopes: $livewire->getRenderHookScopes()) }}

          {{ $slot }}

          {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::CONTENT_END, scopes: $livewire->getRenderHookScopes()) }}
        </main>

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::FOOTER, scopes: $livewire->getRenderHookScopes()) }}
      </div>

      <div
          x-cloak
          x-data="{}"
          x-on:click="$store.sidebar.close()"
          x-show="$store.sidebar.isOpen"
          x-transition.opacity.300ms
          class="fi-sidebar-close-overlay fixed inset-0 z-30 bg-gray-950/50 transition duration-500 dark:bg-gray-950/75 lg:hidden"
      ></div>

      <x-filament-panels::sidebar
          :navigation="$navigation"
          class="fi-main-sidebar"
      />

      <script>
        document.addEventListener('DOMContentLoaded', () => {
          setTimeout(() => {
            let activeSidebarItem = document.querySelector(
              '.fi-main-sidebar .fi-sidebar-item.fi-active',
            )

            if (
              !activeSidebarItem ||
              activeSidebarItem.offsetParent === null
            ) {
              activeSidebarItem = document.querySelector(
                  '.fi-main-sidebar .fi-sidebar-group.fi-active',
              )
            }

            if (
              !activeSidebarItem ||
              activeSidebarItem.offsetParent === null
            ) {
              return
            }

            const sidebarWrapper = document.querySelector(
              '.fi-main-sidebar .fi-sidebar-nav',
            )

            if (!sidebarWrapper) {
              return
            }

            sidebarWrapper.scrollTo(
              0,
              activeSidebarItem.offsetTop -
                  window.innerHeight / 2,
            )
          }, 10)
        })
      </script>
    </div>

  {{--
    <x-library.overlay 
      :show="$show ?? ''" 
      :overlay="$overlay ?? ''"
      :title="$overlayTitle ?? ''"
      :footer="$overlayFooter ?? ''"
    />
  --}}
    <div id='fredapps-container' class='hide'>
    </div>
    
    <div id='modals'>
    </div>

    @livewire(Filament\Livewire\Notifications::class)

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SCRIPTS_BEFORE, scopes: $livewire->getRenderHookScopes()) }}

    @filamentScripts(withCore: true)

    @if (filament()->hasBroadcasting() && config('filament.broadcasting.echo'))
      <script data-navigate-once>
        window.Echo = new window.EchoFactory(@js(config('filament.broadcasting.echo')))

        window.dispatchEvent(new CustomEvent('EchoLoaded'))
      </script>
    @endif

    @stack('scripts')

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SCRIPTS_AFTER, scopes: $livewire->getRenderHookScopes()) }}

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::BODY_END, scopes: $livewire->getRenderHookScopes()) }}
  </body>
</html>
