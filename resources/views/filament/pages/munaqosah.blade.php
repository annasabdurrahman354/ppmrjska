<x-filament-panels::page>

    {{ $this->table }}

    <div>
        @livewire(\App\Livewire\Panel\Widgets\JadwalMunaqosahSayaCalendarWidget::class)
    </div>

</x-filament-panels::page>

@push('scripts')
    <script type="text/javascript">
        function mobileCheck() {
            return window.innerWidth < 890;
        }

        function determineInitialView() {
            if (window.innerWidth < 890) {
                return 'listWeek';
            } else {
                return 'dayGridMonth';
            }
        }

        function displayWindowSize() {
            const windowWidth = window.innerWidth;
            const isBigScreen = windowWidth > 890;

            const weekButton = document.querySelector(".fc-dayGridWeek-button");
            const monthButton = document.querySelector(".fc-dayGridMonth-button");

            if (isBigScreen) {
                weekButton.style.display = "block";
                monthButton.style.display = "block";
            } else {
                weekButton.style.display = "none";
                monthButton.style.display = "none";
            }
        }

        // Attaching the event listener function to window's resize event
        window.addEventListener("resize", displayWindowSize);

        @this.set('initialView', determineInitialView())

    </script>
@endpush
