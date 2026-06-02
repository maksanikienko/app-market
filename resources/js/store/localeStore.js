import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useLocaleStore = defineStore('locale', () => {
    const current = ref(localStorage.getItem('app_locale') ?? 'ru')

    function setLocale(locale) {
        current.value = locale
        localStorage.setItem('app_locale', locale)
    }

    function t(translations) {
        if (!translations || typeof translations !== 'object') return translations ?? ''
        return translations[current.value] ?? translations['ru'] ?? translations['en'] ?? ''
    }

    return { current, setLocale, t }
})