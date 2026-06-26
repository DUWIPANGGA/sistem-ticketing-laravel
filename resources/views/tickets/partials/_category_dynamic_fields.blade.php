{{--
    _category_dynamic_fields.blade.php
    Inner dynamic form fields based on selected category.
    Supports: text, textarea, number, email, date, datetime-local, select, radio, checkbox, file
--}}
<div class="space-y-5">
    {{-- Loading state --}}
    <div x-show="isLoading" class="flex items-center justify-center py-8">
        <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="ml-3 text-sm text-gray-600 font-medium">Memuat kolom tambahan...</span>
    </div>

    {{-- Empty state - category has no fields --}}
    <div x-show="!isLoading && dynamicFields.length === 0" class="py-6 text-center text-gray-400 text-sm">
        <svg class="mx-auto h-8 w-8 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Tidak ada field tambahan untuk kategori ini.
    </div>

    {{-- Fields Container --}}
    <div x-show="!isLoading && dynamicFields.length > 0" class="grid grid-cols-1 gap-5">
        <template x-for="field in dynamicFields" :key="field.id">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    <span x-text="field.label"></span>
                    <span x-show="field.is_required" class="text-red-500 ml-0.5">*</span>
                </label>

                {{-- ===== TEXT ===== --}}
                <template x-if="field.type === 'text'">
                    <input
                        type="text"
                        :name="'dynamic_fields[' + field.id + ']'"
                        x-model="dynamicValues[field.id]"
                        :required="field.is_required"
                        :placeholder="field.placeholder || field.default_value || ''"
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/70 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all sm:text-sm"
                    >
                </template>

                {{-- ===== NUMBER ===== --}}
                <template x-if="field.type === 'number'">
                    <input
                        type="number"
                        :name="'dynamic_fields[' + field.id + ']'"
                        x-model="dynamicValues[field.id]"
                        :required="field.is_required"
                        :placeholder="field.placeholder || field.default_value || ''"
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/70 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all sm:text-sm"
                    >
                </template>

                {{-- ===== EMAIL ===== --}}
                <template x-if="field.type === 'email'">
                    <input
                        type="email"
                        :name="'dynamic_fields[' + field.id + ']'"
                        x-model="dynamicValues[field.id]"
                        :required="field.is_required"
                        :placeholder="field.placeholder || field.default_value || ''"
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/70 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all sm:text-sm"
                    >
                </template>

                {{-- ===== TEXTAREA ===== --}}
                <template x-if="field.type === 'textarea'">
                    <textarea
                        :name="'dynamic_fields[' + field.id + ']'"
                        x-model="dynamicValues[field.id]"
                        :required="field.is_required"
                        :placeholder="field.placeholder || field.default_value || ''"
                        rows="4"
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/70 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all sm:text-sm resize-y"
                    ></textarea>
                </template>

                {{-- ===== SELECT / DROPDOWN ===== --}}
                <template x-if="field.type === 'select'">
                    <div class="relative">
                        <select
                            :name="'dynamic_fields[' + field.id + ']'"
                            x-model="dynamicValues[field.id]"
                            :required="field.is_required"
                            class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/70 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all sm:text-sm appearance-none pr-10"
                        >
                            <option value="" x-text="field.placeholder || 'Pilih opsi'"></option>
                            <template x-for="option in getOptions(field.options)" :key="option">
                                <option :value="option" x-text="option" :selected="dynamicValues[field.id] === option"></option>
                            </template>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                </template>

                {{-- ===== RADIO ===== --}}
                <template x-if="field.type === 'radio'">
                    <div class="flex flex-wrap gap-4 pt-1">
                        <template x-for="option in getOptions(field.options)" :key="option">
                            <label class="inline-flex items-center gap-2 cursor-pointer group">
                                <input
                                    type="radio"
                                    :name="'dynamic_fields[' + field.id + ']'"
                                    :value="option"
                                    x-model="dynamicValues[field.id]"
                                    :required="field.is_required && !dynamicValues[field.id]"
                                    class="text-blue-600 border-gray-300 focus:ring-blue-500"
                                >
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 transition-colors" x-text="option"></span>
                            </label>
                        </template>
                    </div>
                </template>

                {{-- ===== CHECKBOX ===== --}}
                <template x-if="field.type === 'checkbox'">
                    <div class="flex flex-wrap gap-4 pt-1">
                        <template x-for="option in getOptions(field.options)" :key="option">
                            <label class="inline-flex items-center gap-2 cursor-pointer group">
                                <input
                                    type="checkbox"
                                    :name="'dynamic_fields[' + field.id + '][]'"
                                    :value="option"
                                    @change="handleCheckbox(field.id, option, $event)"
                                    :checked="isChecked(field.id, option)"
                                    class="rounded text-blue-600 border-gray-300 focus:ring-blue-500"
                                >
                                <span class="text-sm text-gray-700 group-hover:text-blue-600 transition-colors" x-text="option"></span>
                            </label>
                        </template>
                    </div>
                </template>

                {{-- ===== DATE ===== --}}
                <template x-if="field.type === 'date'">
                    <input
                        type="date"
                        :name="'dynamic_fields[' + field.id + ']'"
                        x-model="dynamicValues[field.id]"
                        :required="field.is_required"
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/70 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all sm:text-sm"
                    >
                </template>

                {{-- ===== DATETIME ===== --}}
                <template x-if="field.type === 'datetime-local'">
                    <input
                        type="datetime-local"
                        :name="'dynamic_fields[' + field.id + ']'"
                        x-model="dynamicValues[field.id]"
                        :required="field.is_required"
                        class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-white/70 text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all sm:text-sm"
                    >
                </template>

                {{-- ===== FILE ===== --}}
                <template x-if="field.type === 'file'">
                    <div>
                        <input
                            type="file"
                            :name="'dynamic_fields[' + field.id + ']'"
                            :required="field.is_required && !field.existing_value"
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors"
                        >
                        <template x-if="field.existing_value">
                            <p class="text-xs text-gray-500 mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                File terunggah: <a :href="'/storage/' + field.existing_value" target="_blank" class="text-blue-600 hover:underline" x-text="field.existing_value.split('/').pop()"></a>
                            </p>
                        </template>
                    </div>
                </template>

                {{-- Show error message if validation fails --}}
                <template x-if="errors['dynamic_fields.' + field.id]">
                    <p class="text-red-600 text-xs mt-1.5 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span x-text="errors['dynamic_fields.' + field.id][0]"></span>
                    </p>
                </template>
            </div>
        </template>
    </div>
</div>
