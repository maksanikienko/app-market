import { ref, onMounted } from 'vue'

// Module-level promise prevents loading the script more than once per page session
let scriptPromise = null

function loadScript(key) {
    if (scriptPromise) return scriptPromise
    scriptPromise = new Promise((resolve, reject) => {
        if (window.google?.maps) { resolve(window.google.maps); return }
        const script = document.createElement('script')
        script.src = `https://maps.googleapis.com/maps/api/js?key=${key}`
        script.async = true
        script.onload  = () => resolve(window.google.maps)
        script.onerror = (e) => { scriptPromise = null; reject(e) }
        document.head.appendChild(script)
    })
    return scriptPromise
}

// Muted stone-toned map style
const STONE_STYLE = [
    { elementType: 'geometry',            stylers: [{ color: '#f0eeeb' }] },
    { elementType: 'labels.text.fill',    stylers: [{ color: '#78716c' }] },
    { elementType: 'labels.text.stroke',  stylers: [{ color: '#f5f5f0' }] },
    { featureType: 'road',           elementType: 'geometry',       stylers: [{ color: '#ffffff' }] },
    { featureType: 'road',           elementType: 'labels.text.fill', stylers: [{ color: '#a8a29e' }] },
    { featureType: 'road.highway',   elementType: 'geometry',       stylers: [{ color: '#e7e5e4' }] },
    { featureType: 'water',          elementType: 'geometry',       stylers: [{ color: '#c9c5be' }] },
    { featureType: 'water',          elementType: 'labels.text.fill', stylers: [{ color: '#9ca3af' }] },
    { featureType: 'poi',            stylers: [{ visibility: 'off' }] },
    { featureType: 'transit',        stylers: [{ visibility: 'off' }] },
    { featureType: 'administrative', elementType: 'geometry.stroke', stylers: [{ color: '#d6d3d1' }] },
]

/**
 * @param {{ center: {lat: number, lng: number}, zoom?: number, title?: string }} options
 */
export function useGoogleMap({ center, zoom = 15, title = '' } = {}) {
    const mapEl  = ref(null)
    const loaded = ref(false)
    const error  = ref(false)

    onMounted(async () => {
        const key = import.meta.env.VITE_GOOGLE_MAPS_KEY
        if (!key || !mapEl.value) { error.value = true; return }

        try {
            const maps = await loadScript(key)
            loaded.value = true

            const map = new maps.Map(mapEl.value, {
                center,
                zoom,
                styles: STONE_STYLE,
                mapTypeControl:    false,
                streetViewControl: false,
                fullscreenControl: true,
                zoomControl:       true,
            })

            new maps.Marker({ position: center, map, title })
        } catch {
            error.value = true
        }
    })

    return { mapEl, loaded, error }
}
