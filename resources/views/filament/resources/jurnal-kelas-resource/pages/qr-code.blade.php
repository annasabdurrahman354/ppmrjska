<x-filament-panels::page>
    <x-filament-panels::form wire:submit="createAndEdit">
        {{ $this->form }}
    </x-filament-panels::form>

    <div>
        {{$this->saveAction}}
        {{$this->saveTemporarilyAction}}
        {{$this->cancelAction}}
    </div>
    <x-filament-panels::page.unsaved-data-changes-alert />
</x-filament-panels::page>

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
            margin: 1.5rem; 
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
            margin: 1.5rem; 
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
            min-width:50%;
            margin-left: auto;
            margin-right: auto;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // handle the scanned code as you like, for example:
            console.log(JSON.stringify(@this.data))
            if(@this.data['kelas'] == "" || @this.data['jenis_kelamin'] == "" ){
                alert('Pilih kelas dan gender santri terlebih dahulu!');
            }
            else{
                @this.addScannedUser(decodedText);
            }
        };

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            // console.warn(`Terjadi kesalahan sistem QR Code Scanner = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            {fps: 5},
        );

        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

    </script> 
@endpush