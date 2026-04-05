import { defineStore } from 'pinia'
import api from '../services/api'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        user: JSON.parse(localStorage.getItem('user')) || null,
        token: localStorage.getItem('token') || null,
    }),
    getters: {
        isAuthenticated: (state) => !!state.token,
        isAdmin: (state) => state.user?.role && typeof state.user.role === 'string' && state.user.role.toLowerCase() === 'admin'
    },
    actions: {
        async login(email, password) {
            try {
                const response = await api.post('/auth/login', { email, password })
                this.token = response.data.token
                this.user = response.data.user

                localStorage.setItem('token', this.token)
                localStorage.setItem('user', JSON.stringify(this.user))

                return true
            } catch (error) {
                console.error('Login failed:', error)
                throw error
            }
        },
        async register(userData) {
            try {
                await api.post('/auth/register', userData)
                return true
            } catch (error) {
                throw error
            }
        },
        async sendOtp(email) {
            try {
                await api.post('/auth/forgot-password', { email })
                return true
            } catch (error) {
                throw error
            }
        },
        async verifyOtp(email, otp) {
            try {
                await api.post('/auth/verify-otp', { email, otp })
                return true
            } catch (error) {
                throw error
            }
        },
        async resetPassword(email, otp, password) {
            try {
                await api.post('/auth/reset-password', { email, otp, password })
                return true
            } catch (error) {
                throw error
            }
        },
        async fetchProfile() {
            try {
                // If we have data in local state, maybe return it? 
                // But generally better to fetch fresh data for profile page
                const response = await api.get('/auth/profile')
                this.user = { ...this.user, ...response.data }
                localStorage.setItem('user', JSON.stringify(this.user))
                return this.user
            } catch (error) {
                console.error('Fetch profile failed:', error)
                throw error
            }
        },
        async updateProfile(profileData) {
            try {
                const response = await api.put('/auth/profile', profileData)
                // Update local user state with new data
                // The backend might not return the full user object, so we merge carefully
                // But for now, let's assume successful update means we can trust profileData 
                // or we should re-fetch.
                // Let's re-fetch to be safe and simple, or merge if backend returns it.
                // AuthController.updateProfile returns { message: "..." }

                // So we should merge profileData into this.user manually
                this.user = { ...this.user, ...profileData }
                localStorage.setItem('user', JSON.stringify(this.user))
                return true
            } catch (error) {
                console.error('Update profile failed:', error)
                throw error
            }
        },
        logout() {
            this.token = null
            this.user = null
            localStorage.removeItem('token')
            localStorage.removeItem('user')
        }
    }
})
