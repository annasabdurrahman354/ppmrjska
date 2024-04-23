<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div wire:ignore
      x-data="{ state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }} }"
      x-load-js="[
          @js(\Filament\Support\Facades\FilamentAsset::getScriptSrc('qrcode-form'))
      ]">
        <div id="reader"></div>
        <button id='scanButton' hidden wire:click="dispatchFormEvent('qr::scanned', '{{ $getStatePath() }}')">
        </button>
    </div>
</x-dynamic-component>


  @push('styles')
    <style>
    #html5-qrcode-button-camera-start {
      padding-top: 0.625rem;
      padding-bottom: 0.625rem; 
      padding-left: 1.25rem;
      padding-right: 1.25rem; 
      margin-bottom: 0.5rem; 
      border-radius: 0.5rem; 
      font-size: 0.875rem;
      line-height: 1.25rem; 
      font-weight: 500; 
      color: #ffffff; 
      background-color: #1D4ED8; 
      :hover {
        background-color: #1E40AF; 
      }
    }

    #html5-qrcode-button-camera-stop{
      padding-top: 0.625rem;
      padding-bottom: 0.625rem; 
      padding-left: 1.25rem;
      padding-right: 1.25rem; 
      margin-bottom: 0.5rem; 
      border-radius: 0.5rem; 
      font-size: 0.875rem;
      line-height: 1.25rem; 
      font-weight: 500; 
      color: #ffffff; 
      background-color: #B91C1C; 
      :hover {
      background-color: #991B1B; 
      }
    }

    #html5-qrcode-anchor-scan-type-change{
        padding-top: 0.625rem;
        padding-bottom: 0.625rem; 
        padding-left: 1.25rem;
        padding-right: 1.25rem; 
        margin-bottom: 0.5rem; 
        border-radius: 0.5rem; 
        border-width: 1px; 
        border-color: #1D4ED8; 
        font-size: 0.875rem;
        line-height: 1.25rem; 
        font-weight: 500; 
        text-align: center; 
        color: #1D4ED8; 
        :hover {
        color: #ffffff; 
        background-color: #1E40AF; 
        }
    }

    #reader__scan_region{
      display: flex;
      justify-content: center;
      padding: 1.5rem; 
    }

    #reader{
      padding: 1.5rem; 
      border-radius: 0.5rem; 
      border-width: 1px; 
      border-color: #E5E7EB; 
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); 
      min-width:min-content;
      max-width:80%;
      margin-left: auto;
      margin-right: auto;
    }
  </style>
  @endpush
    @push('scripts')
      <script src="https://unpkg.com/html5-qrcode" type="text/javascript">
      <script>
          
      </script>
  @endpush
