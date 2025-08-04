@props([])

@php
    $classes = Flux::classes()
        ->add('bg-zinc-50 dark:bg-zinc-800/50')
        ;
@endphp

<thead {{ $attributes->class($classes) }} data-flux-table-header>
    {{ $slot }}
</thead>