<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Product;
use App\Models\Category;
use App\Models\Dealer;
use App\Models\Exhibition;
use App\Models\ProductRegionVisibility;
use Illuminate\Support\Str;
use App\Models\Banner;
use App\Models\Gallery;
use App\Models\Blog;
use App\Models\Faq;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalLeads = Lead::count();
        $totalProducts = Product::count();
        $totalDealers = Dealer::count();
        $totalExhibitions = Exhibition::count();
        $totalGalleryItems = Gallery::count();
        $totalBlogs = Blog::count();

        $recentLeads = Lead::with('product')->orderBy('created_at', 'desc')->take(8)->get();
        $pendingDealers = Dealer::where('status', 'Pending')->get();

        return view('admin.dashboard', compact(
            'totalLeads',
            'totalProducts',
            'totalDealers',
            'totalExhibitions',
            'totalGalleryItems',
            'totalBlogs',
            'recentLeads',
            'pendingDealers'
        ));
    }

    public function banners()
    {
        $banners = Banner::orderBy('sort_order')->orderByDesc('created_at')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $validated = $request->validate([
            'eyebrow' => 'nullable|string|max:191',
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'cta_label' => 'nullable|string|max:80',
            'cta_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'banner_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/banners');
            if (! is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $fileName);
            $validated['image'] = '/uploads/banners/' . $fileName;
        }
        if (empty($validated['image'])) {
            return back()->withErrors(['image' => 'Please provide an image path or upload a banner image.'])->withInput();
        }
        $validated['is_active'] = $request->boolean('is_active');
        Banner::create($validated);
        return back()->with('success', 'Homepage banner created successfully.');
    }

    public function updateBanner(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);
        $validated = $request->validate([
            'eyebrow' => 'nullable|string|max:191',
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'cta_label' => 'nullable|string|max:80',
            'cta_url' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
        ]);
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'banner_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/banners');
            if (! is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $fileName);
            $validated['image'] = '/uploads/banners/' . $fileName;
        }
        if (empty($validated['image'])) {
            $validated['image'] = $banner->image;
        }
        $validated['is_active'] = $request->boolean('is_active');
        $banner->update($validated);
        return back()->with('success', 'Homepage banner updated successfully.');
    }

    public function deleteBanner($id)
    {
        Banner::findOrFail($id)->delete();
        return back()->with('success', 'Homepage banner removed.');
    }

    /* Gallery Management */
    public function galleries()
    {
        $galleries = Gallery::orderBy('sort_order', 'asc')->orderByDesc('created_at')->get();
        return view('admin.galleries.index', compact('galleries'));
    }

    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'category' => 'required|string|max:191',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'gallery_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/gallery');
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $fileName);
            $validated['image'] = '/uploads/gallery/' . $fileName;
        }

        if (empty($validated['image'])) {
            return back()->withErrors(['image' => 'Please upload a gallery image or provide an image path/URL.'])->withInput();
        }

        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Gallery::create($validated);

        return back()->with('success', 'Gallery image item added successfully.');
    }

    public function updateGallery(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'category' => 'required|string|max:191',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'gallery_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/gallery');
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $fileName);
            $validated['image'] = '/uploads/gallery/' . $fileName;
        }

        if (empty($validated['image'])) {
            $validated['image'] = $gallery->image;
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $gallery->update($validated);

        return back()->with('success', 'Gallery image item updated successfully.');
    }

    public function deleteGallery($id)
    {
        Gallery::findOrFail($id)->delete();
        return back()->with('success', 'Gallery image item deleted successfully.');
    }

    /* Blog & News Management */
    public function blogs(Request $request)
    {
        $query = Blog::query();

        if ($request->filled('type') && in_array($request->query('type'), ['Blog', 'News', 'Press Release'])) {
            $query->where('type', $request->query('type'));
        }

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function($builder) use ($q) {
                $builder->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%")
                    ->orWhere('summary', 'like', "%{$q}%");
            });
        }

        $blogs = $query->orderBy('published_at', 'desc')->paginate(15)->onEachSide(1)->withQueryString();
        $totalCount = Blog::count();
        $blogCount = Blog::where('type', 'Blog')->count();
        $newsCount = Blog::where('type', 'News')->count();

        return view('admin.blogs.index', compact('blogs', 'totalCount', 'blogCount', 'newsCount'));
    }

    public function storeBlog(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'type' => 'required|string|in:Blog,News,Press Release',
            'author' => 'nullable|string|max:191',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'published_at' => 'nullable|date',
            'slug' => 'nullable|string|max:191|unique:blogs,slug',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        } else {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'blog_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/blogs');
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $fileName);
            $validated['image'] = '/uploads/blogs/' . $fileName;
        }

        $validated['author'] = $validated['author'] ?: 'Aczet Team';
        $validated['published_at'] = $validated['published_at'] ? \Carbon\Carbon::parse($validated['published_at']) : now();

        Blog::create($validated);

        return back()->with('success', "{$validated['type']} article created successfully.");
    }

    public function updateBlog(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'type' => 'required|string|in:Blog,News,Press Release',
            'author' => 'nullable|string|max:191',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
            'published_at' => 'nullable|date',
            'slug' => 'nullable|string|max:191|unique:blogs,slug,' . $blog->id,
        ]);

        if (!empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        } else {
            $validated['slug'] = $blog->slug;
        }

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'blog_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destination = public_path('uploads/blogs');
            if (!is_dir($destination)) {
                mkdir($destination, 0777, true);
            }
            $file->move($destination, $fileName);
            $validated['image'] = '/uploads/blogs/' . $fileName;
        }

        if (empty($validated['image'])) {
            $validated['image'] = $blog->image;
        }

        $validated['author'] = $validated['author'] ?: 'Aczet Team';
        $validated['published_at'] = $validated['published_at'] ? \Carbon\Carbon::parse($validated['published_at']) : $blog->published_at;

        $blog->update($validated);

        return back()->with('success', "{$validated['type']} article updated successfully.");
    }

    public function deleteBlog($id)
    {
        $blog = Blog::findOrFail($id);
        $type = $blog->type ?? 'Article';
        $blog->delete();
        return back()->with('success', "{$type} article deleted successfully.");
    }

    public function leads(Request $request)
    {
        $query = Lead::with('product');

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }
        if ($request->filled('region')) {
            $query->where('region', $request->query('region'));
        }
        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('company', 'like', "%{$q}%");
            });
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate(15)->onEachSide(1)->withQueryString();

        return view('admin.leads.index', compact('leads'));
    }

    public function updateLeadStatus(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->status = $request->input('status', 'New');
        $lead->save();

        return back()->with('success', "Lead #{$lead->id} status updated to {$lead->status}.");
    }

    public function deleteLead($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return back()->with('success', 'Lead record removed.');
    }

    public function products(Request $request)
    {
        $query = Product::with(['category', 'regionVisibilities']);

        if ($request->filled('q')) {
            $q = $request->query('q');
            $query->where(function($b) use ($q) {
                $b->where('name', 'like', "%{$q}%")
                  ->orWhere('model_number', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        $products = $query->orderBy('id', 'desc')->paginate(15)->onEachSide(1)->withQueryString();
        $regions = ['India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'];
        $categories = Category::orderBy('name', 'asc')->get();

        return view('admin.products.index', compact('products', 'regions', 'categories'));
    }

    public function toggleRegionVisibility(Request $request, $productId)
    {
        $region = $request->input('region');
        $vis = ProductRegionVisibility::where('product_id', $productId)
            ->where('region', $region)
            ->first();

        if ($vis) {
            $vis->is_visible = !$vis->is_visible;
            $vis->save();
        } else {
            ProductRegionVisibility::create([
                'product_id' => $productId,
                'region' => $region,
                'is_visible' => false,
            ]);
        }

        return back()->with('success', "Region visibility for {$region} updated.");
    }

    public function createProduct()
    {
        $categories = Category::orderBy('name', 'asc')->get();
        $regions = ['India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'];

        return view('admin.products.create', compact('categories', 'regions'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:191',
            'model_number' => 'required|string|max:191',
            'short_description' => 'required|string',
            'full_description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'prod_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/products');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            $file->move($destPath, $fileName);
            $validated['main_image'] = '/uploads/products/' . $fileName;
        } elseif ($request->filled('image')) {
            $validated['main_image'] = $request->input('image');
        } else {
            $validated['main_image'] = '/images/products/noimage.jpeg';
        }

        $validated['slug'] = Str::slug($validated['name'] . '-' . rand(100, 999));
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active') ? $request->boolean('is_active') : true;

        $product = Product::create($validated);

        // Default all region visibilities to true
        $regions = ['India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'];
        foreach ($regions as $r) {
            ProductRegionVisibility::create([
                'product_id' => $product->id,
                'region' => $r,
                'is_visible' => true,
            ]);
        }

        return redirect()->route('admin.products')->with('success', 'Product created successfully with image!');
    }

    public function editProduct($id)
    {
        $product = Product::with(['category', 'regionVisibilities'])->findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        $regions = ['India', 'GCC', 'Europe', 'Asia Pacific', 'Thailand', 'USA'];

        return view('admin.products.edit', compact('product', 'categories', 'regions'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:191',
            'model_number' => 'required|string|max:191',
            'short_description' => 'required|string',
            'full_description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'prod_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/products');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            $file->move($destPath, $fileName);
            $validated['main_image'] = '/uploads/products/' . $fileName;
        } elseif ($request->filled('image')) {
            $img = trim($request->input('image'));
            $appUrl = config('app.url');
            if ($appUrl && str_starts_with($img, $appUrl)) {
                $img = substr($img, strlen($appUrl));
            }
            if (str_contains($img, '?v=')) {
                $img = explode('?v=', $img)[0];
            }
            $validated['main_image'] = $img ?: '/images/products/noimage.jpeg';
        } elseif ($request->has('remove_image')) {
            $validated['main_image'] = '/images/products/noimage.jpeg';
        } else {
            $validated['main_image'] = $product->getRawOriginal('main_image') ?: '/images/products/noimage.jpeg';
        }

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return back()->with('success', "Product '{$product->name}' and image updated successfully!");
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $name = $product->name;
        $product->delete();

        return back()->with('success', "Product '{$name}' deleted successfully.");
    }

    public function categories()
    {
        $categories = Category::withCount('products')->get();
        $locales = [
            'en' => 'English',
            'es' => 'Español (Spanish)',
            'ar' => 'العربية (Arabic)',
            'de' => 'Deutsch (German)',
            'zh' => '中文 (Chinese)',
            'th' => 'ไทย (Thai)',
            'fr' => 'Français (French)',
            'ja' => '日本語 (Japanese)',
        ];

        return view('admin.categories.index', compact('categories', 'locales'));
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'cat_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/categories');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            $file->move($destPath, $fileName);
            $validated['image'] = '/uploads/categories/' . $fileName;
        } elseif ($request->filled('image')) {
            $validated['image'] = $request->input('image');
        } else {
            $validated['image'] = '/images/products/noimage.jpeg';
        }

        $validated['slug'] = Str::slug($validated['name'] . '-' . rand(100, 999));
        $validated['translations'] = $request->input('translations', []);

        Category::create($validated);

        return back()->with('success', 'Category created successfully with image and multilingual translations!');
    }

    public function updateCategory(Request $request, $id)
    {
        if ($request->isMethod('get')) {
            return redirect()->route('admin.categories');
        }

        $category = Category::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:191',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        $category->name = $request->input('name', $category->name);
        $category->description = $request->input('description', $category->description);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $fileName = 'cat_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/categories');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            $file->move($destPath, $fileName);
            $category->image = '/uploads/categories/' . $fileName;
        } elseif ($request->filled('image')) {
            $category->image = $request->input('image');
        } elseif ($request->has('remove_image')) {
            $category->image = '/images/products/noimage.jpeg';
        }

        $category->translations = $request->input('translations', []);
        $category->save();

        return back()->with('success', "Category '{$category->name}' and image updated successfully!");
    }

    public function profile()
    {
        $user = auth()->user();
        return view('admin.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191|unique:users,email,' . $user->id,
            'avatar_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:5120',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($request->hasFile('avatar_file')) {
            $file = $request->file('avatar_file');
            $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $destPath = public_path('uploads/avatars');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            $file->move($destPath, $fileName);
            $user->avatar = '/uploads/avatars/' . $fileName;
        }

        $user->save();

        return back()->with('success', 'Profile information successfully updated!');
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password does not match our records.']);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->input('new_password'));
        $user->save();

        return back()->with('success', 'Password changed successfully!');
    }

    /* Publications / Whitepapers CMS */
    public function publications()
    {
        $publications = \App\Models\Publication::all();
        $locales = [
            'es' => 'Español (Spanish)',
            'ar' => 'العربية (Arabic)',
            'de' => 'Deutsch (German)',
            'zh' => '中文 (Chinese)',
            'ja' => '日本語 (Japanese)',
            'th' => 'ไทย (Thai)',
            'fr' => 'Français (French)',
            'it' => 'Italiano (Italian)',
            'ru' => 'Русский (Russian)',
            'pt' => 'Português (Portuguese)',
            'ko' => '한국어 (Korean)',
        ];

        return view('admin.publications.index', compact('publications', 'locales'));
    }

    public function storePublication(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'category' => 'required|string|max:191',
            'summary' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $filePath = '/docs/usp41_aczet_guide.pdf';
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $fileName = 'pub_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';
            $destPath = public_path('docs');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            $file->move($destPath, $fileName);
            $filePath = '/docs/' . $fileName;
        }

        $validated['file'] = $filePath;
        $validated['translations'] = $request->input('translations', []);

        \App\Models\Publication::create($validated);

        return back()->with('success', 'Technical Publication & Whitepaper created successfully!');
    }

    public function updatePublication(Request $request, $id)
    {
        $pub = \App\Models\Publication::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:191',
            'category' => 'required|string|max:191',
            'summary' => 'nullable|string',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $pub->title = $request->input('title');
        $pub->category = $request->input('category');
        $pub->summary = $request->input('summary');

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $fileName = 'pub_' . time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.pdf';
            $destPath = public_path('docs');
            if (!file_exists($destPath)) {
                mkdir($destPath, 0777, true);
            }
            $file->move($destPath, $fileName);
            $pub->file = '/docs/' . $fileName;
        }

        $pub->translations = $request->input('translations', []);
        $pub->save();

        return back()->with('success', "Publication '{$pub->title}' and translations updated!");
    }

    /* Exhibitions CMS */
    public function exhibitions()
    {
        $exhibitions = \App\Models\Exhibition::orderBy('event_date', 'desc')->get();
        $locales = [
            'es' => 'Español (Spanish)',
            'ar' => 'العربية (Arabic)',
            'de' => 'Deutsch (German)',
            'zh' => '中文 (Chinese)',
            'ja' => '日本語 (Japanese)',
            'th' => 'ไทย (Thai)',
            'fr' => 'Français (French)',
            'it' => 'Italiano (Italian)',
            'ru' => 'Русский (Russian)',
            'pt' => 'Português (Portuguese)',
            'ko' => '한국어 (Korean)',
        ];

        return view('admin.exhibitions.index', compact('exhibitions', 'locales'));
    }

    public function storeExhibition(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'location' => 'required|string|max:191',
            'hall_booth' => 'nullable|string|max:191',
            'event_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $validated['translations'] = $request->input('translations', []);
        \App\Models\Exhibition::create($validated);

        return back()->with('success', 'Global Exhibition added successfully!');
    }

    public function updateExhibition(Request $request, $id)
    {
        $ex = \App\Models\Exhibition::findOrFail($id);
        $ex->title = $request->input('title');
        $ex->location = $request->input('location');
        $ex->hall_booth = $request->input('hall_booth');
        $ex->event_date = $request->input('event_date');
        $ex->description = $request->input('description');
        $ex->translations = $request->input('translations', []);
        $ex->save();

        return back()->with('success', "Exhibition '{$ex->title}' and translations updated!");
    }

    /* Dealers / Channel Partners Management */
    public function dealers()
    {
        $dealers = \App\Models\Dealer::orderBy('created_at', 'desc')->get();
        return view('admin.dealers.index', compact('dealers'));
    }

    public function storeDealer(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:191',
            'contact_person' => 'required|string|max:191',
            'email' => 'required|email|unique:dealers,email',
            'password' => 'required|string|min:6',
            'phone' => 'required|string|max:191',
            'country' => 'required|string|max:191',
            'tier' => 'required|string|max:191',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|string|in:Approved,Pending,Suspended',
            'account_manager' => 'nullable|string|max:191',
            'address' => 'nullable|string',
        ]);

        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        \App\Models\Dealer::create($validated);

        return back()->with('success', "Authorized Dealer '{$validated['company_name']}' created successfully!");
    }

    public function updateDealer(Request $request, $id)
    {
        $dealer = \App\Models\Dealer::findOrFail($id);

        $validated = $request->validate([
            'company_name' => 'required|string|max:191',
            'contact_person' => 'required|string|max:191',
            'email' => 'required|email|unique:dealers,email,' . $dealer->id,
            'phone' => 'required|string|max:191',
            'country' => 'required|string|max:191',
            'tier' => 'required|string|max:191',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'status' => 'required|string|in:Approved,Pending,Suspended',
            'account_manager' => 'nullable|string|max:191',
            'address' => 'nullable|string',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($request->input('password'));
        }

        $dealer->update($validated);

        return back()->with('success', "Dealer partner '{$dealer->company_name}' updated successfully!");
    }

    public function approveDealer($id)
    {
        $dealer = \App\Models\Dealer::findOrFail($id);
        $dealer->status = 'Approved';
        $dealer->save();

        return back()->with('success', "Dealer '{$dealer->company_name}' has been Approved for Extranet Access!");
    }

    public function deleteDealer($id)
    {
        $dealer = \App\Models\Dealer::findOrFail($id);
        $dealer->delete();

        return back()->with('success', 'Dealer partner removed successfully.');
    }

    /* System Users / Staff Management */
    public function users()
    {
        $users = \App\Models\User::orderBy('id', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        \App\Models\User::create($validated);

        return back()->with('success', "System administrator '{$validated['name']}' added successfully!");
    }

    public function updateUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($request->filled('password')) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($request->input('password'));
        }

        $user->update($validated);

        return back()->with('success', "User '{$user->name}' updated successfully!");
    }

    public function deleteUser($id)
    {
        $user = \App\Models\User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own active administrator session.']);
        }
        $user->delete();

        return back()->with('success', 'User removed successfully.');
    }

    /* Site Content & Global Configuration Management */
    public function settings()
    {
        $settingsGrouped = \App\Models\SiteSetting::all()->groupBy('group');
        $allSettings = \App\Models\SiteSetting::pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settingsGrouped', 'allSettings'));
    }

    public function updateSettings(Request $request)
    {
        $inputs = $request->except(['_token']);

        foreach ($inputs as $key => $value) {
            if ($key === '_token' || str_ends_with($key, '_file') || $value instanceof \Illuminate\Http\UploadedFile) {
                continue;
            }

            // If a file is uploaded for logo or favicon, don't overwrite with empty string
            if (in_array($key, ['site_logo', 'site_favicon'])) {
                if ($request->hasFile($key . '_file') || $request->hasFile($key)) {
                    continue;
                }
                if ($value === null || $value === '') {
                    continue;
                }
            }

            // Sanitize image paths if input contains full URL or version query strings
            if ((str_ends_with($key, '_image') || str_ends_with($key, '_photo') || in_array($key, ['site_logo', 'site_favicon'])) && is_string($value)) {
                $appUrl = config('app.url');
                if ($appUrl && str_starts_with($value, $appUrl)) {
                    $value = substr($value, strlen($appUrl));
                }
                if (str_contains($value, '?v=')) {
                    $value = explode('?v=', $value)[0];
                }
            }

            $setting = \App\Models\SiteSetting::where('key', $key)->first();
            if ($setting) {
                $setting->value = $value;
                $setting->save();
            } else {
                $group = in_array($key, ['site_logo', 'site_favicon', 'site_name', 'site_tagline', 'header_topbar_badge', 'header_topbar_text', 'header_topbar_link', 'header_cta_label', 'header_cta_link']) ? 'general' : 'about';
                \App\Models\SiteSetting::create([
                    'key' => $key,
                    'value' => $value,
                    'group' => $group,
                    'label' => ucwords(str_replace('_', ' ', $key)),
                ]);
            }
        }

        // Handle all dynamic file uploads ending in _file (e.g. tech_card_1_image_file, site_logo_file, site_favicon_file)
        // as well as direct file inputs (site_logo, site_favicon)
        foreach ($request->allFiles() as $fileKey => $file) {
            if ($file && $file->isValid()) {
                $targetKey = preg_replace('/_file$/', '', $fileKey);
                $ext = strtolower($file->getClientOriginalExtension());
                if (empty($ext) && str_contains($file->getClientOriginalName(), '.ico')) {
                    $ext = 'ico';
                }
                if (empty($ext)) {
                    $ext = 'png';
                }
                $cleanName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
                $fileName = $targetKey . '_' . time() . '_' . $cleanName . '.' . $ext;
                $dest = public_path('uploads/cms');
                if (!is_dir($dest)) {
                    mkdir($dest, 0777, true);
                }
                $file->move($dest, $fileName);
                $imagePath = '/uploads/cms/' . $fileName;

                $setting = \App\Models\SiteSetting::where('key', $targetKey)->first();
                if ($setting) {
                    $setting->value = $imagePath;
                    $setting->save();
                } else {
                    $group = in_array($targetKey, ['site_logo', 'site_favicon']) ? 'general' : 'about';
                    \App\Models\SiteSetting::create([
                        'key' => $targetKey,
                        'value' => $imagePath,
                        'group' => $group,
                        'label' => ucwords(str_replace('_', ' ', $targetKey)),
                        'type' => 'image',
                    ]);
                }
            }
        }

        \App\Models\SiteSetting::clearCache();

        return back()->with('success', 'Site content, configuration, and photos updated successfully!');
    }

    // FAQs Management
    public function faqs(Request $request)
    {
        $query = Faq::with(['category', 'product']);

        if ($request->filled('group')) {
            $query->where('group_name', $request->group);
        }

        if ($request->filled('category')) {
            $query->where('category_name', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%")
                  ->orWhere('category_name', 'like', "%{$search}%")
                  ->orWhere('group_name', 'like', "%{$search}%");
            });
        }

        $faqs = $query->orderBy('group_name')
                      ->orderBy('category_name')
                      ->orderBy('sort_order')
                      ->paginate(15)
                      ->onEachSide(1)
                      ->withQueryString();

        $groups = Faq::distinct('group_name')->orderBy('group_name')->pluck('group_name');
        $categoriesList = Faq::distinct('category_name')->orderBy('category_name')->pluck('category_name');
        $allCategories = Category::orderBy('name')->get();
        $totalFaqs = Faq::count();
        $activeFaqs = Faq::where('is_active', true)->count();
        $totalCategories = Faq::distinct('category_name')->count('category_name');

        return view('admin.faqs.index', compact(
            'faqs',
            'groups',
            'categoriesList',
            'allCategories',
            'totalFaqs',
            'activeFaqs',
            'totalCategories'
        ));
    }

    public function storeFaq(Request $request)
    {
        $validated = $request->validate([
            'group_name' => 'required|string|max:191',
            'category_name' => 'required|string|max:191',
            'category_intro' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        Faq::create($validated);

        return redirect()->route('admin.faqs')->with('success', 'Product FAQ created successfully.');
    }

    public function updateFaq(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'group_name' => 'required|string|max:191',
            'category_name' => 'required|string|max:191',
            'category_intro' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
            'question' => 'required|string',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $faq->update($validated);

        return redirect()->route('admin.faqs')->with('success', 'Product FAQ updated successfully.');
    }

    public function deleteFaq($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.faqs')->with('success', 'Product FAQ deleted successfully.');
    }

    public function toggleFaqStatus($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->is_active = !$faq->is_active;
        $faq->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json(['success' => true, 'is_active' => $faq->is_active]);
        }

        return redirect()->route('admin.faqs')->with('success', 'Product FAQ status updated successfully.');
    }
}
