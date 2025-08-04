@props([])

@php
    $classes = Flux::classes()
        ->add('transition-colors duration-200')
        ;
@endphp

<tr {{ $attributes->class($classes) }} data-flux-table-row>
    {{ $slot }}
</tr>