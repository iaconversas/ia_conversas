@props([
    'striped' => false,
    'hover' => true,
])

@php
    $classes = Flux::classes()
        ->add('min-w-full divide-y divide-zinc-200 dark:divide-zinc-700')
        ->add($striped ? '[&_tbody_tr:nth-child(even)]:bg-zinc-50 dark:[&_tbody_tr:nth-child(even)]:bg-zinc-800/50' : '')
        ->add($hover ? '[&_tbody_tr]:hover:bg-zinc-50 dark:[&_tbody_tr]:hover:bg-zinc-800/50' : '')
        ;
@endphp

<table {{ $attributes->class($classes) }} data-flux-table>
    {{ $slot }}
</table>