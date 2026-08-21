<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Page;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\PpdbRegistration;

class AdminController extends Controller
{
    public function dashboardStats()
    {
        return response()->json([
            'students' => Student::count(),
            'teachers' => Teacher::count(),
            'articles' => Article::count(),
            'ppdb' => PpdbRegistration::count()
        ]);
    }

    // Settings
    public function updateSettings(Request $request)
    {
        $settings = $request->all();
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return response()->json(['message' => 'Pengaturan berhasil disimpan.']);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120' // Max 5MB
        ]);

        $path = $request->file('image')->store('uploads', 'public');
        return response()->json(['url' => 'storage/' . $path]);
    }

    // Pages
    public function updatePage(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        
        $validated = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        $page->update($validated);
        return response()->json(['message' => 'Halaman berhasil diperbarui.']);
    }

    // Articles
    public function storeArticle(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => 'required|string|unique:articles,slug',
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:2048'
        ]);
        
        $validated['author_id'] = $request->user()->id;
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }
        
        // Remove 'image' from array before create if necessary, though it's not in fillable anyway since we use guarded=[]
        unset($validated['image']);

        $article = Article::create($validated);
        return response()->json($article);
    }

    public function updateArticle(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string',
            'slug' => 'required|string|unique:articles,slug,' . $article->id,
            'content' => 'required|string',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        unset($validated['image']);
        
        $article->update($validated);
        return response()->json($article);
    }

    public function destroyArticle($id)
    {
        Article::findOrFail($id)->delete();
        return response()->json(['message' => 'Berita berhasil dihapus.']);
    }

    // Teachers
    public function storeTeacher(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'nip' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $teacher = Teacher::create($validated);
        return response()->json($teacher);
    }

    public function updateTeacher(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string',
            'position' => 'required|string',
            'nip' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        $teacher->update($validated);
        return response()->json($teacher);
    }

    public function destroyTeacher($id)
    {
        Teacher::findOrFail($id)->delete();
        return response()->json(['message' => 'Guru berhasil dihapus.']);
    }

    // Galleries
    public function storeGallery(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'type' => 'required|in:image,video',
            'youtube_url' => 'nullable|string',
            'sort_order' => 'integer'
        ]);
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('galleries', 'public');
            $validated['image_path'] = 'storage/' . $path;
        }

        $gallery = Gallery::create($validated);
        return response()->json($gallery);
    }

    public function destroyGallery($id)
    {
        Gallery::findOrFail($id)->delete();
        return response()->json(['message' => 'Galeri berhasil dihapus.']);
    }
}
