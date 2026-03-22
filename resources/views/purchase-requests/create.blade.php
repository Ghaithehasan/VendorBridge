<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-2xl font-semibold text-slate-900">New Purchase Request</h1>
            <p class="mt-1 text-sm text-slate-600">Capture material demand digitally so Procurement can act with traceability.</p>
        </div>
    </x-slot>

    <form method="POST" action="{{ route('purchase-requests.store') }}" x-data="{ lines: [{ key: Date.now() }] }" class="space-y-6">
        @csrf

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Request Date</label>
                    <input type="date" name="request_date" value="{{ old('request_date', now()->toDateString()) }}" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    @error('request_date') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Notes</label>
                    <textarea name="notes" rows="3" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('notes') }}</textarea>
                    @error('notes') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">PR Lines</h2>
                    <p class="text-sm text-slate-600">Add one or more required raw materials.</p>
                </div>
                <button type="button" @click="lines.push({ key: Date.now() + lines.length })" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Add Line
                </button>
            </div>

            <div class="mt-6 space-y-6">
                <template x-for="(line, index) in lines" :key="line.key">
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500" x-text="'Line ' + (index + 1)"></h3>
                            <button type="button" x-show="lines.length > 1" @click="lines.splice(index, 1)" class="text-sm font-semibold text-rose-600 hover:text-rose-700">
                                Remove
                            </button>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <div class="xl:col-span-2">
                                <label class="block text-sm font-medium text-slate-700">Raw Material</label>
                                <select :name="`lines[${index}][material_id]`" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                                    <option value="">Select material</option>
                                    @foreach ($rawMaterials as $material)
                                        <option value="{{ $material->material_id }}">{{ $material->name }} ({{ $material->baseUnit->name }} {{ $material->baseUnit->symbol }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">Quantity</label>
                                <input type="number" step="0.0001" min="0.0001" :name="`lines[${index}][quantity]`" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">UOM</label>
                                <select :name="`lines[${index}][unit_id]`" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                                    <option value="">Select unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->unit_id }}">{{ $unit->name }} ({{ $unit->symbol }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">Required Delivery Date</label>
                                <input type="date" :name="`lines[${index}][required_delivery_date]`" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>

                            <div class="md:col-span-2 xl:col-span-3">
                                <label class="block text-sm font-medium text-slate-700">Line Notes</label>
                                <input type="text" :name="`lines[${index}][notes]`" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    Please correct the highlighted PR input errors and try again.
                </div>
            @endif
        </div>

        <div class="flex justify-end">
            <button type="submit" class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                Save Purchase Request
            </button>
        </div>
    </form>
</x-app-layout>
