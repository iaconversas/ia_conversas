@props([])

@php
    $classes = Flux::classes()
        ->add('px-6 py-4 whitespace-nowrap text-sm text-zinc-900 dark:text-zinc-100')
        ;
@endphp

<td {{ $attributes->class($classes) }} data-flux-table-cell>
    {{ $slot }}
</td>