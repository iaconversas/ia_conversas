@props([
    'padding' => true,
])

@php
    $classes = Flux::classes()
        ->add('bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg shadow-sm overflow-hidden')
        ->add($padding ? 'p-6' : '')
        ;
@endphp

<div {{ $attributes->class($classes) }} data-flux-card>
    {{ $slot }}
</div>