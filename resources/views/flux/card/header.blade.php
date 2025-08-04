@props([
    'padding' => 'px-6 py-4',
])

@php
    $classes = Flux::classes()
        ->add('border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50')
        ->add($padding)
        ;
@endphp

<div {{ $attributes->class($classes) }} data-flux-card-header>
    {{ $slot }}
</div>