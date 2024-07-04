<div>
    <!-- ========== MAIN CONTENT ========== -->
    <main id="content">
        <div class="overflow-hidden">
            <x-guest-navbar></x-guest-navbar>
            <div class="relative">
                <!-- Gradients -->
                <div aria-hidden="true" class="flex -z-[1] absolute -top-48 start-0">
                    <div class="bg-lime-200 opacity-30 blur-3xl w-[1036px] h-[600px] dark:bg-lime-900 dark:opacity-20"></div>
                    <div class="bg-gray-200 opacity-90 blur-3xl w-[577px] h-[300px] transform translate-y-32 dark:bg-neutral-800/60"></div>
                </div>
                <!-- End Gradients -->

                <div class="absolute top-1/2 start-1/2 -z-[1] transform -translate-y-1/2 -translate-x-1/2 w-[340px] h-[340px] border border-dashed border-green-200 rounded-full dark:border-green-900/60"></div>
                <div class="absolute top-1/2 start-1/2 -z-[1] transform -translate-y-1/2 -translate-x-1/2 w-[575px] h-[575px] border border-dashed border-green-200 rounded-full opacity-80 dark:border-green-900/60"></div>
                <div class="absolute top-1/2 start-1/2 -z-[1] transform -translate-y-1/2 -translate-x-1/2 w-[840px] h-[840px] border border-dashed border-green-200 rounded-full opacity-60 dark:border-green-900/60"></div>
                <div class="absolute top-1/2 start-1/2 -z-[1] transform -translate-y-1/2 -translate-x-1/2 w-[1080px] h-[1080px] border border-dashed border-green-200 rounded-full opacity-40 dark:border-green-900/60"></div>
            </div>

            <!-- Title -->
            <div class="mx-auto text-center">
                <span class="inline-block py-1 px-3 mb-2 text-xs font-semibold text-green-900 bg-white rounded-full">PETA AREA PONDOK</span>
                <h2 class="text-2xl font-bold font-serif italic md:text-4xl md:leading-tight dark:text-white">Perguruan Tinggi Terdekat</h2>
            </div>
            <!-- End Title -->

            <div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-12 mx-auto h-full md:h-screen flex items-center">
                <!-- Map section -->
                <div class="w-full h-screen bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <div id="map" class="z-[9] w-full min-h-screen rounded-md shadow-md" wire:ignore></div>
                </div>
            </div>
        </div>
    </main>

    <x-guest-footer></x-guest-footer>
    <div wire:ignore.self id="hs-univ-modal" class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
        <div class="hs-overlay-open:mt-7 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto h-[calc(100%-3.5rem)] min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full max-h-full overflow-hidden flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
                <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700">
                    <h3 class="font-bold text-gray-800 dark:text-white">
                        {{$clickedUniv['nama'] ?? ''}}
                    </h3>
                    <button id="close-btn" type="button" class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700" data-hs-overlay="#hs-univ-modal">
                        <span class="sr-only">Close</span>
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 6 6 18"></path>
                            <path d="m6 6 12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4 overflow-y-auto">
                    <div class="space-y-4 w-full">


                    </div>
                </div>
                <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                    @isset($clickedUniv['link_website'])
                        <a href="//{{$clickedUniv['link_website']}}" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 disabled:opacity-50 disabled:pointer-events-none">
                            <svg class="w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 14v4.833A1.166 1.166 0 0 1 16.833 20H5.167A1.167 1.167 0 0 1 4 18.833V7.167A1.166 1.166 0 0 1 5.167 6h4.618m4.447-2H20v5.768m-7.889 2.121 7.778-7.778"/>
                            </svg>
                            Buka Situs Web
                        </a>
                    @endisset
                    <button id="close-btn" type="button" class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-gray-200 bg-white text-gray-800 shadow-sm hover:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700 dark:text-white dark:hover:bg-neutral-800" data-hs-overlay="#hs-univ-modal">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


@push('styles')
    @once
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
              integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
              crossorigin=""/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/solid.min.css">
        <link rel="stylesheet" href="{{asset('vendor/leaflet.awesome-markers/leaflet.awesome-markers.css')}}"/>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.css" />
    @endonce
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <script type="text/javascript" src="{{ asset('vendor/leaflet.awesome-markers/leaflet.awesome-markers.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol/dist/L.Control.Locate.min.js" charset="utf-8"></script>
    <script>
        var osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        });

        var map = L.map('map', {
            center: [ -7.4524345217123, 111.08387166008306],
            zoom: 14,
            layers: osm
        });

        var lc = L.control
            .locate({
                zoom: 17,
                position: "topleft",
                setView: 'untilPanOrZoom',
                flyTo: true,
                strings: {
                    title: "Show me where I am, yo!"
                }
            })
            .addTo(map);

        function onLocationFound(e) {
            var radius = e.accuracy;

            L.marker(e.latlng).addTo(map)
                .bindPopup("Kemungkinan Anda berada " + radius + " meter dari titik ini!").openPopup();
        }

        map.on('locationfound', onLocationFound);

        var markersLayer = new L.LayerGroup();

        const mosqueIcon = L.divIcon({
            html: `<svg xmlns="http://www.w3.org/2000/svg" fill="#ca8a04" viewBox="0 0 640 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M400 0c5 0 9.8 2.4 12.8 6.4c34.7 46.3 78.1 74.9 133.5 111.5l0 0 0 0c5.2 3.4 10.5 7 16 10.6c28.9 19.2 45.7 51.7 45.7 86.1c0 28.6-11.3 54.5-29.8 73.4H221.8c-18.4-19-29.8-44.9-29.8-73.4c0-34.4 16.7-66.9 45.7-86.1c5.4-3.6 10.8-7.1 16-10.6l0 0 0 0C309.1 81.3 352.5 52.7 387.2 6.4c3-4 7.8-6.4 12.8-6.4zM288 512V440c0-13.3-10.7-24-24-24s-24 10.7-24 24v72H192c-17.7 0-32-14.3-32-32V352c0-17.7 14.3-32 32-32H608c17.7 0 32 14.3 32 32V480c0 17.7-14.3 32-32 32H560V440c0-13.3-10.7-24-24-24s-24 10.7-24 24v72H448V454c0-19-8.4-37-23-49.2L400 384l-25 20.8C360.4 417 352 435 352 454v58H288zM70.4 5.2c5.7-4.3 13.5-4.3 19.2 0l16 12C139.8 42.9 160 83.2 160 126v2H0v-2C0 83.2 20.2 42.9 54.4 17.2l16-12zM0 160H160V296.6c-19.1 11.1-32 31.7-32 55.4V480c0 9.6 2.1 18.6 5.8 26.8c-6.6 3.4-14 5.2-21.8 5.2H48c-26.5 0-48-21.5-48-48V176 160z"/></svg>`,
            className: "",
            iconSize: [36, 36],
            iconAnchor: [18, 36],
        });

        const mosqueMarker = new L.marker(
            [-7.567929, 110.849758],
            {
                icon: mosqueIcon,
            }
        ).bindTooltip(
            'Masjid Roudlotul Jannah',
            {
                direction: 'top',
                offset: [0, -50]
            }
        ).addTo(markersLayer)

        map.addLayer(markersLayer);

        map.invalidateSize();

        var url = "{{asset('index/border-ppm.json')}}";
        fetch(url)
            .then(response => response.json())
            .then(data => {
                var geojsonLayer = L.geoJSON(data);

                geojsonLayer.addTo(map);
                geojsonLayer.eachLayer(function(layer) {
                    layer.setStyle({color: '#3388ff', weight: 2});
                    layer.bindTooltip(
                        'Area PPM Roudlotul Jannah',
                        {
                            direction: 'top',
                            offset: [0, -50]
                        }
                    );;
                });

                map.fitBounds(geojsonLayer.getBounds());
            });
    </script>
@endpush
@script
<script>
    let univs = @js($univs);
    univs.forEach(addUniv);

    function addUniv(item) {
        const svgIcon = L.divIcon({
            html: `<svg xmlns="http://www.w3.org/2000/svg" class="map-icon" fill="green" width="36" height="36" viewBox="0 0 24 24"><path d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 11c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"/></svg>`,
            className: "",
            iconSize: [36, 36],
            iconAnchor: [18, 36],
        });

        let marker = new L.marker(
            [item['latitude'], item['longitude']],
            {
                icon: svgIcon,
            }
        ).on('click', function() {
            @this.set('clickedUniv', item)
            HSOverlay.open('#hs-univ-modal');
        }).bindTooltip(
            item['nama'],
            {
                direction: 'top',
                offset: [0, -50]
            }
        );

        markersLayer.addLayer(marker);
    }
</script>
@endscript
