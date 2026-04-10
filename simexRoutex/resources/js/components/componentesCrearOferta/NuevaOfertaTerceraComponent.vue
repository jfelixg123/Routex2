<template>
    <div class="space-y-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Envío</h2>
            <p class="text-gray-500">Complete the shipping details for your cargo</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Select Branch</label>
                    <select class="border rounded-md p-2 outline-none focus:ring-2 focus:ring-orange-400">
                    <option>Choose a branch...</option>
                    </select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Contact Name</label>
                    <input type="text" placeholder="Enter contact name" class="border rounded-md p-2 outline-none">
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">State</label>
                    <select class="border rounded-md p-2 outline-none"><option>Select state</option></select>
                </div>
                <div class="flex flex-col gap-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Phone</label>
                    <input type="text" placeholder="+1 (555) 000-0000" class="border rounded-md p-2 outline-none">
                </div>
                </div>

                <!-- Special Requirements -->
                <div class="space-y-3">
                <h4 class="text-xs font-bold text-gray-400 uppercase">Special Requirements</h4>
                <div class="flex gap-6">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" class="accent-orange-500"> Dangerous Goods Base
                    </label>
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                    <input type="checkbox" class="accent-orange-500"> Vessel profile
                    </label>
                </div>
                </div>

                <!-- Formas -->
                <div class="space-y-3">
                <h4 class="text-xs font-bold text-gray-400 uppercase">Formas</h4>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 text-sm">
                    <input type="radio" name="forma" checked class="accent-orange-500"> Contenedor 20 FT
                    </label>
                    <label class="flex items-center gap-2 text-sm">
                    <input type="radio" name="forma" class="accent-orange-500"> Quotation
                    </label>
                </div>
                    <textarea placeholder="Describe the shipping itinerary..." class="w-full border rounded-md p-3 h-32 outline-none text-sm bg-gray-50"></textarea>
                </div>
            </div>

            <div class="space-y-15">
                <div class="flex flex-col gap-1">
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-xs font-bold text-gray-400 uppercase">Instrucciones para gestión</label>
                        <span class="text-m border px-2 py-1 rounded bg-white shadow-sm">Standard Management</span>
                    </div>
                    <textarea placeholder="Provide detailed management instructions..." class="w-full border rounded-md p-3 h-32 outline-none text-sm"></textarea>
                </div>

                <div class="flex flex-col gap-1">
                    <div class="flex justify-between items-center mb-1">
                        <label class="text-xs font-bold text-gray-400 uppercase">Pesos</label>
                        <span class="text-xs border px-2 py-1 rounded bg-white shadow-sm">Metric Tons (MT)</span>
                    </div>
                    <textarea placeholder="Specify weight breakdown per item..." class="w-full border rounded-md p-3 h-40 outline-none text-sm"></textarea>
                </div>
            </div>

        </div>


    </div>
</template>

<script setup>

</script>

<style lang="scss" scoped>

</style>
