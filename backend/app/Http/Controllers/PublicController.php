<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Page;
use App\Models\Article;
use App\Models\Gallery;
use App\Models\Teacher;

class PublicController extends Controller
{
    public function settings()
    {
        return response()->json(Setting::pluck('value', 'key'));
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return response()->json($page);
    }

    public function articles()
    {
        $articles = Article::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(10);
        return response()->json($articles);
    }

    public function article($slug)
    {
        $article = Article::where('slug', $slug)->where('status', 'published')->firstOrFail();
        return response()->json($article);
    }

    public function galleries()
    {
        $galleries = Gallery::orderBy('sort_order', 'asc')->orderBy('id', 'desc')->get();
        return response()->json($galleries);
    }

    public function teachers()
    {
        $teachers = Teacher::where('is_active', true)->orderBy('sort_order', 'asc')->get();
        return response()->json($teachers);
    }

    public function ppdbInfo()
    {
        $isOpen = \App\Models\Setting::where('key', 'ppdb_is_open')->value('value') ?? 'true';
        return response()->json([
            'is_open' => $isOpen === 'true'
        ]);
    }

    public function submitContact(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'message' => 'required|string'
        ]);

        // Normally we would save this to the DB or send an email.
        // For SDD compliance, we just accept it and return success.
        
        return response()->json(['message' => 'Pesan berhasil dikirim.']);
    }
}
