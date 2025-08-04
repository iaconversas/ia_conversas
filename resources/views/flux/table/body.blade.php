@props([])

@php
    $classes = Flux::classes()
        ->add('bg-white dark:bg-zinc-900 divide-y divide-zinc-200 dark:divide-zinc-700')
        ;
@endphp

<tbody {{ $attributes->class($classes) }} data-flux-table-body>
    {{ $slot }}
</tbody>