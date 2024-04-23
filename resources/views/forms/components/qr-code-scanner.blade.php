<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
  <div wire:ignore id="reader"></div>
</x-dynamic-component>