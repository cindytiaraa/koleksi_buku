@extends('layouts.landing')

@section('title', 'Katalog Buku - Library Management System')
@section('nav-catalog', 'active')

@section('content')

    <section class="py-5" style="background-color: var(--lp-bg);">
        <div class="container py-4">

            <div class="text-center mb-5">
                <h1 class="fw-bold">Katalog Buku</h1>
                <p class="text-muted">Jelajahi koleksi buku yang tersedia di perpustakaan kami</p>
            </div>

            <!-- Search & Filter -->
            <div class="row g-3 mb-4 justify-content-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="catalogSearch" class="form-control border-start-0"
                               placeholder="Cari judul atau penulis...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="catalogCategory" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="Fiksi">Fiksi</option>
                        <option value="Non-Fiksi">Non-Fiksi</option>
                        <option value="Sains">Sains</option>
                        <option value="Teknologi">Teknologi</option>
                        <option value="Sejarah">Sejarah</option>
                        <option value="Anak">Anak</option>
                    </select>
                </div>
            </div>

            <!-- Book Grid -->
            <div class="row g-4" id="catalogGrid"></div>

            <!-- Pagination -->
            <nav class="mt-5">
                <ul class="pagination justify-content-center" id="catalogPagination"></ul>
            </nav>

        </div>
    </section>

@endsection

@push('scripts')
<style>
    .book-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
        transition: all .25s ease;
        height: 100%;
    }
    .book-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(108,99,255,0.15);
    }
    .book-cover {
        height: 220px;
        background: linear-gradient(135deg, #6C63FF, #8B5CF6);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .book-cover i { font-size: 3.5rem; color: #fff; opacity: 0.85; }
    .badge-tersedia { background: rgba(34,197,94,0.12); color: #16a34a; }
    .badge-dipinjam { background: rgba(239,68,68,0.12); color: #dc2626; }
</style>

<script>
    // Dummy data preview katalog (bukan data asli sistem)
    const dummyBooks = [
        { title: 'Laskar Pelangi', author: 'Andrea Hirata', category: 'Fiksi', status: 'Tersedia' },
        { title: 'Bumi Manusia', author: 'Pramoedya Ananta Toer', category: 'Fiksi', status: 'Dipinjam' },
        { title: 'Sapiens', author: 'Yuval Noah Harari', category: 'Non-Fiksi', status: 'Tersedia' },
        { title: 'Cosmos', author: 'Carl Sagan', category: 'Sains', status: 'Tersedia' },
        { title: 'Clean Code', author: 'Robert C. Martin', category: 'Teknologi', status: 'Dipinjam' },
        { title: 'Atomic Habits', author: 'James Clear', category: 'Non-Fiksi', status: 'Tersedia' },
        { title: 'Sejarah Nusantara', author: 'Slamet Muljana', category: 'Sejarah', status: 'Tersedia' },
        { title: 'Si Kancil Cerdik', author: 'Kumpulan Cerita', category: 'Anak', status: 'Tersedia' },
        { title: 'The Pragmatic Programmer', author: 'David Thomas', category: 'Teknologi', status: 'Dipinjam' },
        { title: 'Filosofi Teras', author: 'Henry Manampiring', category: 'Non-Fiksi', status: 'Tersedia' },
        { title: 'Negeri 5 Menara', author: 'Ahmad Fuadi', category: 'Fiksi', status: 'Tersedia' },
        { title: 'A Brief History of Time', author: 'Stephen Hawking', category: 'Sains', status: 'Dipinjam' },
    ];

    const perPage = 8;
    let currentPage = 1;

    function renderCatalog() {
        const search = document.getElementById('catalogSearch').value.toLowerCase();
        const category = document.getElementById('catalogCategory').value;

        const filtered = dummyBooks.filter(b => {
            const matchSearch = b.title.toLowerCase().includes(search) || b.author.toLowerCase().includes(search);
            const matchCategory = category === '' || b.category === category;
            return matchSearch && matchCategory;
        });

        const totalPages = Math.max(1, Math.ceil(filtered.length / perPage));
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * perPage;
        const pageItems = filtered.slice(start, start + perPage);

        const grid = document.getElementById('catalogGrid');
        grid.innerHTML = '';

        if (pageItems.length === 0) {
            grid.innerHTML = '<div class="col-12 text-center text-muted py-5"><i class="bi bi-search fs-1 d-block mb-2"></i>Buku tidak ditemukan.</div>';
        }

        pageItems.forEach(book => {
            const badgeClass = book.status === 'Tersedia' ? 'badge-tersedia' : 'badge-dipinjam';
            grid.innerHTML += `
                <div class="col-sm-6 col-lg-3">
                    <div class="book-card">
                        <div class="book-cover"><i class="bi bi-book"></i></div>
                        <div class="p-3">
                            <span class="badge ${badgeClass} mb-2">${book.status}</span>
                            <h6 class="fw-600 mb-1">${book.title}</h6>
                            <p class="small text-muted mb-1">${book.author}</p>
                            <span class="small text-lp-primary">${book.category}</span>
                        </div>
                    </div>
                </div>
            `;
        });

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        const pag = document.getElementById('catalogPagination');
        pag.innerHTML = '';

        for (let i = 1; i <= totalPages; i++) {
            pag.innerHTML += `
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="goToPage(event, ${i})">${i}</a>
                </li>
            `;
        }
    }

    function goToPage(e, page) {
        e.preventDefault();
        currentPage = page;
        renderCatalog();
    }

    document.getElementById('catalogSearch').addEventListener('input', () => { currentPage = 1; renderCatalog(); });
    document.getElementById('catalogCategory').addEventListener('change', () => { currentPage = 1; renderCatalog(); });

    renderCatalog();
</script>
@endpush
