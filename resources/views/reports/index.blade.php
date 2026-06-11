@extends('layouts.app')

@section('content')
<div class="relative overflow-hidden rounded-2xl bg-white border border-gray-200/50 p-8 shadow-xl mb-8">
    <div class="absolute inset-0 z-0">
        <div class="absolute -right-20 -top-20 w-64 h-64 bg-blue-600/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl"></div>
    </div>
    <div class="relative z-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Laporan & Analitik</h1>
        <p class="text-gray-500 text-lg">Pantau kinerja tiket, rating teknisi, dan tren secara interaktif.</p>
    </div>
</div>

<!-- Filter Section -->
<div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl p-6 mb-8">
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
            <input type="date" id="filterDateFrom"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
            <input type="date" id="filterDateTo"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500">
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
            <select id="filterStatus"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500">
                <option value="">Semua Status</option>
                @foreach ($statuses as $s)
                    <option value="{{ $s }}">{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Prioritas</label>
            <select id="filterPriority"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500">
                <option value="">Semua Prioritas</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="urgent">Urgent</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Kategori</label>
            <select id="filterCategory"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->name }}">{{ $c->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Teknisi</label>
            <select id="filterTechnician"
                class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500">
                <option value="">Semua Teknisi</option>
                @foreach ($technicians as $t)
                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="flex items-center gap-3 mt-4">
        <button onclick="applyFilters()"
            class="px-5 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-500 transition-colors shadow-sm">
            Terapkan Filter
        </button>
        <button onclick="resetFilters()"
            class="px-5 py-2 border border-gray-200 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors">
            Reset
        </button>
        <a href="#" onclick="exportCsv(event)"
            class="px-5 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-500 transition-colors shadow-sm ml-auto">
            Export CSV
        </a>
    </div>
</div>

<!-- Summary Cards -->
<div id="summaryCards" class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8"></div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Tren Tiket per Bulan</h3>
        <canvas id="chartMonthly" height="200"></canvas>
    </div>
    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Distribusi Status</h3>
        <canvas id="chartStatus" height="200"></canvas>
    </div>
    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Tiket per Kategori</h3>
        <canvas id="chartCategory" height="200"></canvas>
    </div>
    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Tiket per Prioritas</h3>
        <canvas id="chartPriority" height="200"></canvas>
    </div>
</div>

<!-- Rating Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Rating Teknisi</h3>
        <div id="techRatingsTable" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Teknisi</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Rating</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Total Rating</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Terselesaikan</th>
                    </tr>
                </thead>
                <tbody id="techRatingsBody" class="divide-y divide-gray-200"></tbody>
            </table>
        </div>
    </div>
    <div class="bg-white border border-gray-200/50 rounded-2xl shadow-xl p-6">
        <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Rating per Kategori</h3>
        <div id="catRatingsTable" class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Rating</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Total Rating</th>
                        <th class="px-4 py-2.5 text-center text-xs font-semibold text-gray-500 uppercase">Total Tiket</th>
                    </tr>
                </thead>
                <tbody id="catRatingsBody" class="divide-y divide-gray-200"></tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
let charts = {};

function buildUrl(params) {
    const base = '{{ route("reports.data") }}';
    const qs = Object.entries(params)
        .filter(([_, v]) => v)
        .map(([k, v]) => encodeURIComponent(k) + '=' + encodeURIComponent(v))
        .join('&');
    return qs ? base + '?' + qs : base;
}

function getFilters() {
    return {
        date_from: document.getElementById('filterDateFrom').value,
        date_to: document.getElementById('filterDateTo').value,
        status: document.getElementById('filterStatus').value,
        priority: document.getElementById('filterPriority').value,
        category: document.getElementById('filterCategory').value,
        technician: document.getElementById('filterTechnician').value,
    };
}

function applyFilters() {
    const filters = getFilters();
    fetch(buildUrl(filters))
        .then(res => res.json())
        .then(data => renderAll(data));
}

function resetFilters() {
    document.querySelectorAll('#filterDateFrom, #filterDateTo, #filterStatus, #filterPriority, #filterCategory, #filterTechnician').forEach(el => el.value = '');
    applyFilters();
}

function exportCsv(e) {
    e.preventDefault();
    const filters = getFilters();
    const url = buildUrl(filters).replace('/data', '/export');
    window.location.href = url;
}

function renderAll(data) {
    renderSummary(data.summary);
    renderCharts(data);
    renderTechRatings(data.tech_ratings);
    renderCatRatings(data.cat_ratings);
}

function renderSummary(s) {
    const cards = [
        { label: 'Total Tiket', value: s.total, color: 'blue' },
        { label: 'Open', value: s.open, color: 'yellow' },
        { label: 'Resolved', value: s.resolved, color: 'green' },
        { label: 'Closed', value: s.closed, color: 'gray' },
        { label: 'Rata-rata Rating', value: s.avg_rating, color: 'indigo' },
    ];
    document.getElementById('summaryCards').innerHTML = cards.map(c => `
        <div class="bg-white border border-gray-200/50 rounded-xl p-4 shadow-sm">
            <p class="text-xs font-medium text-gray-500 mb-1">${c.label}</p>
            <p class="text-2xl font-bold tracking-tight text-${c.color}-600">${c.value}</p>
        </div>
    `).join('');
}

function renderCharts(data) {
    const configs = {
        chartMonthly: {
            type: 'line',
            data: {
                labels: data.monthly.labels,
                datasets: [{
                    label: 'Tiket',
                    data: data.monthly.data,
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.3,
                }]
            },
            opts: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        },
        chartStatus: {
            type: 'doughnut',
            data: {
                labels: data.status.labels,
                datasets: [{ data: data.status.data, backgroundColor: data.status.colors }]
            },
            opts: { responsive: true, plugins: { legend: { position: 'bottom' } } }
        },
        chartCategory: {
            type: 'bar',
            data: {
                labels: data.category.labels,
                datasets: [{
                    label: 'Jumlah',
                    data: data.category.data,
                    backgroundColor: 'rgba(99, 102, 241, 0.7)',
                    borderColor: '#6366F1',
                    borderWidth: 1,
                }]
            },
            opts: { responsive: true, indexAxis: 'y', plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        },
        chartPriority: {
            type: 'bar',
            data: {
                labels: data.priority ? data.priority.labels : [],
                datasets: [{
                    label: 'Jumlah',
                    data: data.priority ? data.priority.data : [],
                    backgroundColor: ['rgba(16,185,129,0.7)', 'rgba(245,158,11,0.7)', 'rgba(249,115,22,0.7)', 'rgba(239,68,68,0.7)'],
                    borderColor: ['#10B981', '#F59E0B', '#F97316', '#EF4444'],
                    borderWidth: 1,
                }]
            },
            opts: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        },
    };

    // Destroy old charts
    Object.values(charts).forEach(c => c.destroy());
    charts = {};

    Object.entries(configs).forEach(([id, cfg]) => {
        const el = document.getElementById(id);
        if (el) charts[id] = new Chart(el, { type: cfg.type, data: cfg.data, options: cfg.opts });
    });
}

function renderTechRatings(ratings) {
    const tbody = document.getElementById('techRatingsBody');
    if (!ratings || ratings.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 italic">Belum ada rating.</td></tr>';
        return;
    }
    tbody.innerHTML = ratings.map(r => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 text-sm font-medium text-gray-900">${r.name}</td>
            <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${r.avg_rating >= 4 ? 'bg-green-100 text-green-800' : r.avg_rating >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">
                    ⭐ ${r.avg_rating}
                </span>
            </td>
            <td class="px-4 py-3 text-center text-sm text-gray-500">${r.total_rated}</td>
            <td class="px-4 py-3 text-center text-sm text-gray-500">${r.total_resolved}</td>
        </tr>
    `).join('');
}

function renderCatRatings(ratings) {
    const tbody = document.getElementById('catRatingsBody');
    if (!ratings || ratings.length === 0) {
        tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500 italic">Belum ada rating.</td></tr>';
        return;
    }
    tbody.innerHTML = ratings.map(r => `
        <tr class="hover:bg-gray-50 transition-colors">
            <td class="px-4 py-3 text-sm font-medium text-gray-900">${r.name}</td>
            <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${r.avg_rating >= 4 ? 'bg-green-100 text-green-800' : r.avg_rating >= 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'}">
                    ⭐ ${r.avg_rating}
                </span>
            </td>
            <td class="px-4 py-3 text-center text-sm text-gray-500">${r.total_rated}</td>
            <td class="px-4 py-3 text-center text-sm text-gray-500">${r.total_tickets}</td>
        </tr>
    `).join('');
}

document.addEventListener('DOMContentLoaded', applyFilters);
</script>
@endsection
