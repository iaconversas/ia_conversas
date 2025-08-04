@props([
    'sortable' => false,
])

@php
    $classes = Flux::classes()
        ->add('px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider')
        ->add($sortable ? 'cursor-pointer hover:text-zinc-700 dark:hover:text-zinc-300' : '')
        ;
@endphp

<th {{ $attributes->class($classes) }} data-flux-table-column>
    {{ $slot }}
</th>