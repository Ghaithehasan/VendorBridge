<x-app-layout>
    <x-slot name="header">
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-slate-900">Edit — {{ $purchaseRequest->pr_number }}</h1>
                <p class="mt-1 text-sm text-slate-600">This Purchase Request is still in draft. Update it before submitting for approval.</p>
            </div>
            <a href="{{ route('purchase-requests.show', $purchaseRequest->pr_id) }}"
               class="rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Cancel
            </a>
        </div>
    </x-slot>

    @php
        $existingLines = $purchaseRequest->lines->map(fn($l) => [
            'key'                    => $l->pr_line_id,
            'material_id'            => $l->material_id,
            'quantity'               => $l->quantity,
            'unit_id'                => $l->unit_id,
            'required_delivery_date' => $l->required_delivery_date->format('Y-m-d'),
            'notes'                  => $l->notes ?? '',
        ])->values()->toArray();
    @endphp

    <form method="POST"
          action="{{ route('purchase-requests.update', $purchaseRequest->pr_id) }}"
          x-data="{ lines: {{ json_encode($existingLines) }} }"
          class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Request Date</label>
                    <input type="date" name="request_date"
                           value="{{ old('request_date', $purchaseRequest->request_date->format('Y-m-d')) }}"
                           class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                    @error('request_date') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700">Notes</label>
                    <textarea name="notes" rows="3"
                              class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">{{ old('notes', $purchaseRequest->notes) }}</textarea>
                    @error('notes') <p class="mt-2 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-slate-900">PR Lines</h2>
                    <p class="text-sm text-slate-600">Update the required raw materials and quantities.</p>
                </div>
                <button type="button"
                        @click="lines.push({ key: Date.now() + lines.length, material_id: '', quantity: '', unit_id: '', required_delivery_date: '', notes: '' })"
                        class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                    Add Line
                </button>
            </div>

            <div class="mt-6 space-y-6">
                <template x-for="(line, index) in lines" :key="line.key">
                    <div class="rounded-2xl border border-slate-200 p-5">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500"
                                x-text="'Line ' + (index + 1)"></h3>
                            <button type="button"
                                    x-show="lines.length > 1"
                                    @click="lines.splice(index, 1)"
                                    class="text-sm font-semibold text-rose-600 hover:text-rose-700">
                                Remove
                            </button>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                            <div class="xl:col-span-2">
                                <label class="block text-sm font-medium text-slate-700">Raw Material</label>
                                <select :name="`lines[${index}][material_id]`"
                                        class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                                    <option value="">Select material</option>
                                    @foreach ($rawMaterials as $material)
                                        <option value="{{ $material->material_id }}"
                                                :selected="line.material_id == {{ $material->material_id }}">
                                            {{ $material->name }} ({{ $material->baseUnit->name }} {{ $material->baseUnit->symbol }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">Quantity</label>
                                <input type="number" step="0.0001" min="0.0001"
                                       :name="`lines[${index}][quantity]`"
                                       :value="line.quantity"
                                       class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">UOM</label>
                                <select :name="`lines[${index}][unit_id]`"
                                        class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                                    <option value="">Select unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->unit_id }}"
                                                :selected="line.unit_id == {{ $unit->unit_id }}">
                                            {{ $unit->name }} ({{ $unit->symbol }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700">Required Delivery Date</label>
                                <input type="date"
                                       :name="`lines[${index}][required_delivery_date]`"
                                       :value="line.required_delivery_date"
                                       class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>

                            <div class="md:col-span-2 xl:col-span-3">
                                <label class="block text-sm font-medium text-slate-700">Line Notes</label>
                                <input type="text"
                                       :name="`lines[${index}][notes]`"
                                       :value="line.notes"
                                       class="mt-1 w-full rounded-xl border-slate-300 shadow-sm focus:border-slate-500 focus:ring-slate-500">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    Please correct the highlighted errors and try again.
                </div>
            @endif
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('purchase-requests.show', $purchaseRequest->pr_id) }}"
               class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                Cancel
            </a>
            <button type="submit"
                    class="rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                Save Changes
            </button>
        </div>
    </form>
</x-app-layout>
