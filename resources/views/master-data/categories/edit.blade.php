@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Category</h1>
            <p class="text-gray-500 mt-1 text-sm">Update the details of this category.</p>
        </div>
        <a href="{{ route('master-data.categories.index') }}" class="inline-flex items-center text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Categories
        </a>
    </div>

    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden">
        <div class="h-1 bg-gradient-to-r from-indigo-600 to-purple-600"></div>
        <div class="p-8">
            <form action="{{ route('master-data.categories.update', $category->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name <span class="text-red-600">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                           class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-inner sm:text-sm">
                    @error('name')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description <span class="text-gray-500 text-xs font-normal">(Optional)</span></label>
                    <textarea name="description" id="description" rows="4"
                              class="block w-full px-4 py-3 border border-gray-200 rounded-xl bg-gray-50/50 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all shadow-inner sm:text-sm resize-y">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="text-red-600 text-xs mt-1.5 flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-6 border-t border-gray-200/50 flex justify-end gap-4">
                    <a href="{{ route('master-data.categories.index') }}" class="px-6 py-2.5 border border-gray-300 rounded-xl shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-gray-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 border border-transparent rounded-xl shadow-lg shadow-indigo-500/30 text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-indigo-500 transition-all transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Dynamic Fields Management Section --}}
    <div class="mt-10 bg-white border border-gray-200/50 rounded-2xl shadow-xl overflow-hidden" x-data="{ showAddField: false, editingFieldId: null }">
        <div class="h-1 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
        <div class="p-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-gray-100">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Dynamic Fields</h2>
                    <p class="text-gray-500 mt-1 text-sm">Kelola kolom tambahan khusus untuk kategori ini.</p>
                </div>
                <button type="button" @click="showAddField = !showAddField" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 text-white bg-blue-600 hover:bg-blue-500 transition-all">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Dynamic Field
                </button>
            </div>

            {{-- Form Add Field --}}
            <div x-show="showAddField" x-cloak x-collapse class="bg-gray-50/50 border border-gray-200/60 rounded-2xl p-6 mb-8 shadow-inner">
                <h3 class="text-md font-semibold text-gray-900 mb-4">Add New Dynamic Field</h3>
                <form action="{{ route('master-data.categories.fields.store', $category->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Field Label <span class="text-red-500">*</span></label>
                            <input type="text" name="label" required placeholder="e.g. Application Name" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-900 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Field Name / Slug <span class="text-red-500">*</span></label>
                            <input type="text" name="name" required placeholder="e.g. application_name (huruf kecil & underscore)" pattern="^[a-z0-9_]+$" title="Hanya boleh huruf kecil, angka, dan underscore (_)" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-900 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Field Type <span class="text-red-500">*</span></label>
                            <select name="type" required class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-900 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                <option value="text">Text (Single Line)</option>
                                <option value="textarea">Textarea (Multi Line)</option>
                                <option value="select">Select (Dropdown)</option>
                                <option value="date">Date</option>
                                <option value="datetime-local">Datetime</option>
                                <option value="file">File Upload</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Options <span class="text-gray-400 font-normal">(Untuk dropdown, pisahkan dengan koma)</span></label>
                            <input type="text" name="options" placeholder="e.g. Low,Medium,High" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-900 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Placeholder <span class="text-gray-400 font-normal">(Opsional)</span></label>
                            <input type="text" name="placeholder" placeholder="e.g. Masukkan nama aplikasi" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-900 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex items-center pt-5">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_required" value="1" class="rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="ml-2 text-sm text-gray-700">Wajib Diisi (Required)</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200/50">
                        <button type="button" @click="showAddField = false" class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-sm font-medium shadow-md shadow-blue-500/10">Add Field</button>
                    </div>
                </form>
            </div>

            {{-- Fields List Table --}}
            <div class="border border-gray-200 rounded-xl overflow-hidden bg-white">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Label</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Name (Slug)</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Required</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($category->fields as $field)
                            {{-- View Row --}}
                            <tr x-show="editingFieldId !== {{ $field->id }}" class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $field->label }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ $field->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 uppercase">{{ $field->type }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    @if($field->is_required)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-50 text-red-700 border border-red-200">Yes</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">No</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                    <button type="button" @click="editingFieldId = {{ $field->id }}" class="text-blue-600 hover:text-blue-800 transition-colors" title="Edit">
                                        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <form action="{{ route('master-data.categories.fields.destroy', $field->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus field ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition-colors" title="Delete">
                                            <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Edit Row --}}
                            <tr x-show="editingFieldId === {{ $field->id }}" x-cloak class="bg-blue-50/20">
                                <td colspan="5" class="px-6 py-4">
                                    <form action="{{ route('master-data.categories.fields.update', $field->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Field Label <span class="text-red-500">*</span></label>
                                                <input type="text" name="label" required value="{{ $field->label }}" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-900">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Field Name / Slug <span class="text-red-500">*</span></label>
                                                <input type="text" name="name" required value="{{ $field->name }}" pattern="^[a-z0-9_]+$" title="Hanya boleh huruf kecil, angka, dan underscore (_)" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-900">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Field Type <span class="text-red-500">*</span></label>
                                                <select name="type" required class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-900">
                                                    <option value="text" {{ $field->type === 'text' ? 'selected' : '' }}>Text (Single Line)</option>
                                                    <option value="textarea" {{ $field->type === 'textarea' ? 'selected' : '' }}>Textarea (Multi Line)</option>
                                                    <option value="select" {{ $field->type === 'select' ? 'selected' : '' }}>Select (Dropdown)</option>
                                                    <option value="date" {{ $field->type === 'date' ? 'selected' : '' }}>Date</option>
                                                    <option value="datetime-local" {{ $field->type === 'datetime-local' ? 'selected' : '' }}>Datetime</option>
                                                    <option value="file" {{ $field->type === 'file' ? 'selected' : '' }}>File Upload</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Options <span class="text-gray-400 font-normal">(Koma-terpisah untuk select)</span></label>
                                                <input type="text" name="options" value="{{ $field->options }}" placeholder="e.g. Low,Medium,High" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-900">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-500 mb-1">Placeholder <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                                <input type="text" name="placeholder" value="{{ $field->placeholder }}" placeholder="e.g. Masukkan nama aplikasi" class="block w-full px-3 py-2 border border-gray-200 rounded-lg text-sm bg-white text-gray-900">
                                            </div>
                                            <div class="flex items-center pt-5">
                                                <label class="inline-flex items-center cursor-pointer">
                                                    <input type="checkbox" name="is_required" value="1" {{ $field->is_required ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500 border-gray-300">
                                                    <span class="ml-2 text-sm text-gray-700">Wajib Diisi (Required)</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="flex justify-end gap-2 pt-3 border-t border-gray-200">
                                            <button type="button" @click="editingFieldId = null" class="px-3 py-1.5 border border-gray-300 rounded-lg text-xs text-gray-700 bg-white hover:bg-gray-50">Cancel</button>
                                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-lg text-xs font-medium shadow-md shadow-blue-500/10">Save Changes</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Belum ada field dinamis untuk kategori ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
