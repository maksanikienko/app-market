import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import axios from 'axios'

export const useUserStore = defineStore('user', () => {
    const user   = ref(null)
    const errors = ref({})

    const isAdmin = computed(() =>
        Array.isArray(user.value?.roles) && user.value.roles.includes('admin')
    )

    async function fetchUser() {
        try {
            const { data } = await axios.get('/user')
            user.value = data          // includes `roles` array from the API
        } catch {
            user.value = null
        }
    }

    async function login(form) {
        errors.value = {}
        try {
            await axios.post('/login', form)
            await fetchUser()          // fetch with roles after login
            return true
        } catch (e) {
            errors.value = e.response?.data ?? {}
            return false
        }
    }

    async function register(form) {
        errors.value = {}
        try {
            await axios.post('/register', form)
            await fetchUser()          // fetch with roles after register
            return true
        } catch (e) {
            errors.value = e.response?.data ?? {}
            return false
        }
    }

    async function logout() {
        await axios.post('/logout')
        user.value = null
    }

    return { user, errors, isAdmin, fetchUser, login, register, logout }
})
