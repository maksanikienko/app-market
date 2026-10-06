import { defineStore } from 'pinia'
import { ref } from 'vue'

// Accepts a { ru, ro } object or its JSON-encoded string (as stored in order/variant pivots).
function parseTranslations(value) {
    if (typeof value !== 'string' || !value.startsWith('{')) return value
    try { return JSON.parse(value) } catch { return value }
}

export const useLocaleStore = defineStore('locale', () => {
    const current = ref(localStorage.getItem('app_locale') ?? 'ru')

    function setLocale(locale) {
        current.value = locale
        localStorage.setItem('app_locale', locale)
    }

    function t(value) {
        const translations = parseTranslations(value)
        if (!translations || typeof translations !== 'object') return translations ?? ''
        return translations[current.value] ?? translations.ru ?? translations.en ?? ''
    }

    return { current, setLocale, t }
})
