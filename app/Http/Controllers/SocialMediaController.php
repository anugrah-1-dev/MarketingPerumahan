<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\SocialMedia;

class SocialMediaController extends Controller
{
    public function index()
    {
        $items = SocialMedia::latest()->get();
        $panel = auth()->check() && auth()->user()->isAdmin() ? 'manager' : 'admin';
        return view("{$panel}.social-media", compact('items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'platform'    => 'required|string|in:youtube,tiktok,instagram',
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:300',
            'content_url' => 'required|url|max:500',
            'thumbnail'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_url'] = $request->file('thumbnail')
                ->store('social-media', 'uploads');
        }
        unset($data['thumbnail']);

        $data['is_active'] = true;
        SocialMedia::create($data);

        return back()->with('success', "Konten \"{$data['title']}\" berhasil ditambahkan!");
    }

    public function update(Request $request, $id)
    {
        $item = SocialMedia::findOrFail($id);

        $data = $request->validate([
            'platform'    => 'required|string|in:youtube,tiktok,instagram',
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:300',
            'content_url' => 'required|url|max:500',
            'thumbnail'   => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail if it exists in storage
            if ($item->thumbnail_url && !str_starts_with($item->thumbnail_url, 'http')) {
                Storage::disk('uploads')->delete($item->thumbnail_url);
                Storage::disk('public')->delete($item->thumbnail_url);
            }
            $data['thumbnail_url'] = $request->file('thumbnail')
                ->store('social-media', 'uploads');
        }
        unset($data['thumbnail']);

        $item->update($data);

        return back()->with('success', "Konten \"{$item->title}\" berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $item = SocialMedia::findOrFail($id);

        if ($item->thumbnail_url && !str_starts_with($item->thumbnail_url, 'http')) {
            Storage::disk('uploads')->delete($item->thumbnail_url);
            Storage::disk('public')->delete($item->thumbnail_url);
        }

        $title = $item->title;
        $item->delete();

        return back()->with('success', "Konten \"{$title}\" berhasil dihapus!");
    }

    public function toggleStatus($id)
    {
        $item = SocialMedia::findOrFail($id);
        $item->update(['is_active' => !$item->is_active]);

        $status = $item->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "\"{$item->title}\" berhasil {$status}.");
    }
}
