<template>
    <div class="min-h-screen flex">

      <!-- IZQUIERDA -->
      <div class="w-1/2 bg-blue-900 text-white flex flex-col justify-center p-12">
        <h1 class="text-4xl font-bold mb-6">RouteX Transports</h1>
        <h2 class="text-5xl font-bold mb-4">Streamlining Global Commerce</h2>
        <p class="text-lg opacity-80">
          Access your real-time shipping dashboard, manage fleet operations, and optimize supply chain efficiency.
        </p>
      </div>

      <!-- DERECHA -->
      <div class="w-1/2 flex items-center justify-center bg-gray-100">
        <div class="bg-white p-10 rounded-xl shadow-lg w-full max-w-md">

          <h2 class="text-2xl font-bold mb-2">Logistics Portal Authentication</h2>
          <p class="text-gray-500 mb-6">Sign in to manage your shipments and logistics.</p>

          <!-- EMAIL -->
          <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Work Email Address</label>
            <input
              v-model="email"
              type="email"
              placeholder="name@company.com"
              class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"
            />
          </div>

          <!-- PASSWORD -->
          <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Password</label>
            <input
              v-model="password"
              type="password"
              placeholder="********"
              class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-400"
            />
          </div>

          <!-- ERROR -->
          <p v-if="error" class="text-red-500 mb-3">
            {{ error }}
          </p>

          <!-- BOTÓN -->
          <button
            @click="login"
            class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-semibold transition"
          >
            Sign In →
          </button>

        </div>
      </div>

    </div>
  </template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            email: '',
            password: '',
            error: null
        }
    },
    methods: {
        async login() {
            try {
                await axios.post('/login', {
                    correu: this.email,
                    contrasenya: this.password
                });

                this.$router.push('/dashboard');

            } catch (err) {
                this.error = err.response?.data?.error || 'Error al iniciar sesión';
            }
        }
    }
}
</script>
