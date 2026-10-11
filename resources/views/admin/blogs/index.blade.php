@extends('layouts.admin')

@section('title', 'Blog & News Hub - Aczet CMS')
@section('page_title', 'Blog & News Manager')

@section('styles')
<style>
    .blog-hub-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .blog-stat-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #FFFFFF;
        border: 1px solid var(--border-color);
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--primary);
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .blog-toolbar {
        background: #FFFFFF;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        box-shadow: 0 2px 8px rgba(15,23,42,0.03);
    }

    .filter-chip-btn {
        background: #F8FAFC;
        border: 1px solid var(--border-color);
        color: #475569;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .filter-chip-btn:hover {
        background: #F1F5F9;
        color: var(--primary);
        border-color: #CBD5E1;
    }

    .filter-chip-btn.active {
        background: var(--primary);
        color: #FFFFFF;
        border-color: var(--primary);
    }

    /* Article Cards Grid */
    .article-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 24px;
    }

    .article-card {
        background: #FFFFFF;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.04);
        display: flex;
        flex-direction: column;
    }

    .article-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
        border-color: #CBD5E1;
    }

    .article-img-wrap {
        height: 180px;
        background: radial-gradient(circle at center, #1E293B 0%, #0B132B 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        padding: 16px;
        overflow: hidden;
    }

    .article-img-wrap img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .article-card:hover .article-img-wrap img {
        transform: scale(1.05);
    }

    .type-badge-pill {
        position: absolute;
        top: 12px;
        right: 12px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        backdrop-filter: blur(6px);
    }

    .type-badge-pill.news {
        background: rgba(239, 68, 68, 0.9);
        color: #FFFFFF;
    }

    .type-badge-pill.blog {
        background: rgba(37, 99, 235, 0.9);
        color: #FFFFFF;
    }

    .type-badge-pill.press {
        background: rgba(147, 51, 234, 0.9);
        color: #FFFFFF;
    }

    .article-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .article-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.78rem;
        color: var(--text-muted);
        margin-bottom: 8px;
    }

    .article-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--primary);
        line-height: 1.35;
        margin-bottom: 8px;
    }

    .article-excerpt {
        font-size: 0.84rem;
        color: var(--text-muted);
        line-height: 1.5;
        margin-bottom: 16px;
        flex: 1;
    }

    .article-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 14px;
        border-top: 1px solid var(--border-color);
        margin-top: auto;
    }

    /* Modal upload drag and drop */
    .dropzone-box {
        border: 2px dashed #CBD5E1;
        border-radius: 12px;
        padding: 20px 16px;
        text-align: center;
        background: #F8FAFC;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
    }

    .dropzone-box:hover {
        border-color: var(--accent);
        background: rgba(229, 86, 4, 0.04);
    }

    .dropzone-box input[type="file"] {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .preview-box {
        display: none;
        align-items: center;
        gap: 14px;
        background: #0F172A;
        border-radius: 12px;
        padding: 10px 14px;
        margin-top: 10px;
        color: #FFFFFF;
    }

    .preview-box.active {
        display: flex;
    }
</style>
@endsection

@section('content')

<!-- Header with Live Stats -->
<div class="blog-hub-header">
    <div>
        <div style="font-size:0.8rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px;">
            Content Management &bull; Editorial Hub
        </div>
        <h3 style="font-size:1.6rem; font-weight:800; color:var(--primary); margin:0;">
            Blog & News Management
        </h3>
        <p style="margin-top:6px; color:var(--text-muted); font-size:0.9rem;">
            Publish technical whitepapers, precision weighing insights, ISO compliance guides, and company press releases.
        </p>
    </div>

    <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
        <div class="blog-stat-pill">
            <i class="fa-solid fa-book-open" style="color:#2563EB;"></i>
            <span>{{ $blogCount }} Blogs</span>
        </div>
        <div class="blog-stat-pill">
            <i class="fa-solid fa-bullhorn" style="color:#EF4444;"></i>
            <span>{{ $newsCount }} News</span>
        </div>
        <a href="{{ route('blogs.index') }}" target="_blank" class="btn-sm btn-outline" style="text-decoration:none; padding:8px 16px; border-radius:8px; font-weight:700;">
            <i class="fa-solid fa-arrow-up-right-from-square"></i> Public Insights
        </a>
        <button class="btn-primary" type="button" onclick="openBlogModal()" style="padding:8px 18px; border-radius:8px; font-weight:700;">
            <i class="fa-solid fa-plus"></i> Write New Article
        </button>
    </div>
</div>

@if($errors->any())
    <div class="card" style="border-left:4px solid #EF4444; color:#991B1B; padding:16px 20px; margin-bottom:24px; display:flex; align-items:center; gap:12px;">
        <i class="fa-solid fa-triangle-exclamation fa-lg"></i>
        <span>{{ $errors->first() }}</span>
    </div>
@endif

<!-- Toolbar: Filter Pills, Search Bar -->
<div class="blog-toolbar">
    <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
        <span style="font-size:0.82rem; font-weight:700; color:var(--text-muted); margin-right:4px;">Filter:</span>
        <a href="{{ route('admin.blogs') }}" class="filter-chip-btn {{ !request()->filled('type') ? 'active' : '' }}">
            All Articles ({{ $totalCount }})
        </a>
        <a href="{{ route('admin.blogs', ['type' => 'Blog']) }}" class="filter-chip-btn {{ request()->query('type') === 'Blog' ? 'active' : '' }}">
            <i class="fa-solid fa-book-open"></i> Blogs ({{ $blogCount }})
        </a>
        <a href="{{ route('admin.blogs', ['type' => 'News']) }}" class="filter-chip-btn {{ request()->query('type') === 'News' ? 'active' : '' }}">
            <i class="fa-solid fa-newspaper"></i> News ({{ $newsCount }})
        </a>
    </div>

    <form action="{{ route('admin.blogs') }}" method="GET" style="display:flex; gap:8px; margin:0; width:280px;">
        @if(request()->filled('type'))
            <input type="hidden" name="type" value="{{ request()->query('type') }}">
        @endif
        <div style="position:relative; width:100%;">
            <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#94A3B8; font-size:0.85rem;"></i>
            <input type="text" name="q" value="{{ request()->query('q') }}" placeholder="Search articles..." class="form-control" style="padding-left:34px; padding-top:7px; padding-bottom:7px; font-size:0.85rem; border-radius:20px;">
        </div>
        @if(request()->filled('q'))
            <a href="{{ route('admin.blogs', request()->filled('type') ? ['type' => request()->query('type')] : []) }}" class="btn-sm btn-outline" style="text-decoration:none; display:flex; align-items:center;">Reset</a>
        @endif
    </form>
</div>

<!-- Article Cards Grid -->
<div class="article-grid">
    @forelse($blogs as $blog)
        <div class="article-card">
            <div class="article-img-wrap">
                @if($blog->image)
                    <img src="{{ vasset($blog->image) }}" alt="{{ $blog->title }}" loading="lazy">
                @else
                    <i class="fa-solid fa-newspaper" style="color:#64748B; font-size:3rem;"></i>
                @endif

                <span class="type-badge-pill {{ $blog->type === 'News' ? 'news' : ($blog->type === 'Press Release' ? 'press' : 'blog') }}">
                    {{ $blog->type ?? 'Blog' }}
                </span>
            </div>

            <div class="article-body">
                <div class="article-meta">
                    <span style="font-weight:700; color:var(--accent);">
                        {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Draft' }}
                    </span>
                    <span>
                        <i class="fa-solid fa-user-pen"></i> {{ $blog->author ?: 'Aczet Team' }}
                    </span>
                </div>

                <h4 class="article-title">
                    <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" style="color:inherit; text-decoration:none;">
                        {{ $blog->title }}
                    </a>
                </h4>

                <p class="article-excerpt">
                    {{ Str::limit($blog->summary ?: strip_tags($blog->content), 120) }}
                </p>

                <div class="article-footer">
                    <span style="font-size:0.75rem; color:#94A3B8; font-family:monospace;">
                        /blog/{{ Str::limit($blog->slug, 22) }}
                    </span>
                    <div style="display:flex; gap:6px;">
                        <a href="{{ route('blogs.show', $blog->slug) }}" target="_blank" class="btn-sm btn-outline" style="padding:5px 10px; font-size:0.75rem; border-radius:6px; text-decoration:none;" title="View Live">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        </a>
                        <button class="btn-sm btn-outline" type="button" onclick='editBlog(@json($blog))' style="padding:5px 10px; font-size:0.75rem; border-radius:6px;" title="Edit">
                            <i class="fa-solid fa-pen"></i> Edit
                        </button>
                        <form action="{{ route('admin.blogs.delete', $blog->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete this article?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn-sm btn-danger" type="submit" style="padding:5px 10px; font-size:0.75rem; border-radius:6px;" title="Delete">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="card" style="grid-column:1/-1; text-align:center; padding:60px 20px; color:var(--text-muted);">
            <i class="fa-solid fa-newspaper" style="font-size:3rem; margin-bottom:14px; color:#CBD5E1; display:block;"></i>
            <h4 style="font-size:1.2rem; font-weight:700; color:var(--primary);">No Articles Found</h4>
            <p style="font-size:0.88rem; margin-top:4px;">No posts match the current filter or search criteria.</p>
        </div>
    @endforelse
</div>

@if($blogs->hasPages())
    <div style="margin-top:32px; display:flex; justify-content:center;">
        {{ $blogs->links() }}
    </div>
@endif

<!-- Create / Edit Article Modal -->
<div class="modal-backdrop" id="blogModalBackdrop">
    <div class="modal-card" style="max-width:840px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; border-bottom:1px solid var(--border-color); padding-bottom:14px;">
            <h3 id="modalBlogHeading" style="font-size:1.25rem; font-weight:800; color:var(--primary); display:flex; align-items:center; gap:10px; margin:0;">
                <span style="width:10px; height:10px; background:var(--accent); border-radius:50%; display:inline-block;"></span>
                New Article
            </h3>
            <button type="button" onclick="closeBlogModal()" style="background:none; border:none; font-size:1.4rem; color:var(--text-muted); cursor:pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="blogModalForm" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display:grid; grid-template-columns:1.3fr 0.7fr; gap:20px; margin-bottom:18px;">
                <div>
                    <label for="blogTitle" style="font-weight:700; font-size:0.85rem; margin-bottom:6px; display:block;">Title *</label>
                    <input name="title" id="blogTitle" required class="form-control" placeholder="Article headline or news title">
                </div>

                <div>
                    <label for="blogType" style="font-weight:700; font-size:0.85rem; margin-bottom:6px; display:block;">Category / Type *</label>
                    <select name="type" id="blogType" required class="form-control">
                        <option value="Blog">Technical Blog</option>
                        <option value="News">Company & Product News</option>
                        <option value="Press Release">Press Release</option>
                    </select>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; margin-bottom:18px;">
                <div>
                    <label for="blogSlug" style="font-weight:700; font-size:0.85rem; margin-bottom:6px; display:block;">Slug (optional)</label>
                    <input name="slug" id="blogSlug" class="form-control" placeholder="auto-generated-if-empty">
                </div>
                <div>
                    <label for="blogAuthor" style="font-weight:700; font-size:0.85rem; margin-bottom:6px; display:block;">Author</label>
                    <input name="author" id="blogAuthor" class="form-control" placeholder="Aczet Metrology Team" value="Aczet Team">
                </div>
                <div>
                    <label for="blogPublishedAt" style="font-weight:700; font-size:0.85rem; margin-bottom:6px; display:block;">Publish Date</label>
                    <input type="date" name="published_at" id="blogPublishedAt" class="form-control" value="{{ date('Y-m-d') }}">
                </div>
            </div>

            <div style="margin-bottom:18px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                    <label style="font-weight:700; font-size:0.85rem; margin:0;">Featured Image</label>
                    <div style="display:flex; gap:4px;">
                        <button type="button" id="tabBlogUploadBtn" class="btn-sm btn-primary" onclick="switchBlogImageTab('upload')" style="padding:2px 8px; font-size:0.72rem; border-radius:4px;">Upload</button>
                        <button type="button" id="tabBlogUrlBtn" class="btn-sm btn-outline" onclick="switchBlogImageTab('url')" style="padding:2px 8px; font-size:0.72rem; border-radius:4px;">URL / Path</button>
                    </div>
                </div>

                <div id="blogUploadWrap">
                    <div class="dropzone-box">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size:1.6rem; color:var(--accent); margin-bottom:6px; display:block;"></i>
                        <span style="font-size:0.82rem; font-weight:700; color:var(--primary); display:block;">
                            Choose image file or drag here
                        </span>
                        <small style="font-size:0.72rem; color:var(--text-muted); display:block; margin-top:2px;">
                            JPG, PNG, WebP &bull; Max 5 MB
                        </small>
                        <input name="image_file" id="blogImageFile" type="file" accept=".jpg,.jpeg,.png,.webp" onchange="handleBlogFileSelect(this)">
                    </div>
                </div>

                <div id="blogUrlWrap" style="display:none;">
                    <input name="image" id="blogImage" class="form-control" placeholder="https://images.unsplash.com/... or /images/..." oninput="handleBlogUrlInput(this.value)">
                </div>

                <div class="preview-box" id="blogLivePreviewBox">
                    <img src="" id="blogLivePreviewImg" alt="Preview" style="width:65px; height:50px; object-fit:contain; background:rgba(255,255,255,0.1); border-radius:6px; padding:3px;">
                    <div style="flex:1; min-width:0;">
                        <span style="font-size:0.8rem; font-weight:700; display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" id="blogLivePreviewTitle">Selected Image</span>
                        <small style="font-size:0.72rem; color:#94A3B8;">Preview</small>
                    </div>
                    <button type="button" onclick="clearBlogLivePreview()" style="background:none; border:none; color:#EF4444; cursor:pointer; font-size:1rem;"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>

            <div style="margin-bottom:18px;">
                <label for="blogSummary" style="font-weight:700; font-size:0.85rem; margin-bottom:6px; display:block;">Summary / Excerpt</label>
                <textarea name="summary" id="blogSummary" rows="2" class="form-control" placeholder="Short description shown on article cards..."></textarea>
            </div>

            <div style="margin-bottom:24px;">
                <label for="blogContent" style="font-weight:700; font-size:0.85rem; margin-bottom:6px; display:block;">Full Article Content *</label>
                <textarea name="content" id="blogContent" rows="7" required class="form-control" placeholder="Write full article body content here..."></textarea>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:12px;">
                <button type="button" class="btn-sm btn-outline" onclick="closeBlogModal()" style="padding:9px 18px; font-weight:600;">Cancel</button>
                <button type="submit" class="btn-primary" id="modalBlogSubmitBtn" style="padding:9px 22px; font-weight:700;">
                    <i class="fa-solid fa-floppy-disk"></i> Publish Article
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
function switchBlogImageTab(mode) {
    var uploadWrap = document.getElementById('blogUploadWrap');
    var urlWrap = document.getElementById('blogUrlWrap');
    var uploadBtn = document.getElementById('tabBlogUploadBtn');
    var urlBtn = document.getElementById('tabBlogUrlBtn');

    if (mode === 'upload') {
        uploadWrap.style.display = 'block';
        urlWrap.style.display = 'none';
        uploadBtn.className = 'btn-sm btn-primary';
        urlBtn.className = 'btn-sm btn-outline';
    } else {
        uploadWrap.style.display = 'none';
        urlWrap.style.display = 'block';
        uploadBtn.className = 'btn-sm btn-outline';
        urlBtn.className = 'btn-sm btn-primary';
    }
}

function handleBlogFileSelect(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('blogLivePreviewImg').src = e.target.result;
            document.getElementById('blogLivePreviewTitle').textContent = file.name;
            document.getElementById('blogLivePreviewBox').classList.add('active');
        };
        reader.readAsDataURL(file);
    }
}

function handleBlogUrlInput(url) {
    if (url && url.trim().length > 3) {
        document.getElementById('blogLivePreviewImg').src = url;
        document.getElementById('blogLivePreviewTitle').textContent = url.split('/').pop() || 'Remote Image';
        document.getElementById('blogLivePreviewBox').classList.add('active');
    } else {
        document.getElementById('blogLivePreviewBox').classList.remove('active');
    }
}

function clearBlogLivePreview() {
    document.getElementById('blogImageFile').value = '';
    document.getElementById('blogImage').value = '';
    document.getElementById('blogLivePreviewBox').classList.remove('active');
}

function openBlogModal() {
    document.getElementById('modalBlogHeading').innerHTML = '<span style="width:10px; height:10px; background:var(--accent); border-radius:50%; display:inline-block;"></span> New Article';
    document.getElementById('blogModalForm').action = '{{ route("admin.blogs.store") }}';
    document.getElementById('blogModalForm').reset();
    document.getElementById('blogAuthor').value = 'Aczet Team';
    document.getElementById('blogPublishedAt').value = '{{ date("Y-m-d") }}';
    clearBlogLivePreview();
    document.getElementById('modalBlogSubmitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Publish Article';
    document.getElementById('blogModalBackdrop').classList.add('active');
}

function closeBlogModal() {
    document.getElementById('blogModalBackdrop').classList.remove('active');
}

function editBlog(blog) {
    document.getElementById('modalBlogHeading').innerHTML = '<span style="width:10px; height:10px; background:#38BDF8; border-radius:50%; display:inline-block;"></span> Edit Article';
    document.getElementById('blogModalForm').action = '/admin/blogs/' + blog.id + '/update';
    document.getElementById('blogTitle').value = blog.title || '';
    document.getElementById('blogType').value = blog.type || 'Blog';
    document.getElementById('blogSlug').value = blog.slug || '';
    document.getElementById('blogAuthor').value = blog.author || 'Aczet Team';
    
    if (blog.published_at) {
        var d = new Date(blog.published_at);
        document.getElementById('blogPublishedAt').value = d.toISOString().split('T')[0];
    }
    
    document.getElementById('blogImage').value = blog.image || '';
    if (blog.image) {
        handleBlogUrlInput(blog.image);
    }
    
    document.getElementById('blogSummary').value = blog.summary || '';
    document.getElementById('blogContent').value = blog.content || '';
    document.getElementById('modalBlogSubmitBtn').innerHTML = '<i class="fa-solid fa-check"></i> Update Article';
    document.getElementById('blogModalBackdrop').classList.add('active');
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeBlogModal();
    }
});
</script>
@endsection
