<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('sort_order')->orderBy('id')->get();
        $panel = auth()->check() && auth()->user()->isAdmin() ? 'manager' : 'admin';

        return view("{$panel}.hero-slides", compact('slides'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:150',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $data['image_path'] = $request->file('image')->store('hero-slides', 'uploads');
        $data['sort_order'] = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : ((int) HeroSlide::max('sort_order') + 1);
        $data['is_active'] = $request->boolean('is_active', true);

        unset($data['image']);

        HeroSlide::create($data);

        return back()->with('success', 'Slide beranda berhasil ditambahkan.');
    }

    public function update(Request $request, int $id)
    {
        $slide = HeroSlide::findOrFail($id);

        $data = $request->validate([
            'title' => 'nullable|string|max:150',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'sort_order' => 'required|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteStoredFile($slide->image_path);
            $data['image_path'] = $request->file('image')->store('hero-slides', 'uploads');
        }

        $data['is_active'] = $request->boolean('is_active');
        unset($data['image']);

        $slide->update($data);

        return back()->with('success', 'Slide beranda berhasil diperbarui.');
    }

    public function destroy(int $id)
    {
        $slide = HeroSlide::findOrFail($id);

        $this->deleteStoredFile($slide->image_path);
        $slide->delete();

        return back()->with('success', 'Slide beranda berhasil dihapus.');
    }

    public function toggleStatus(int $id)
    {
        $slide = HeroSlide::findOrFail($id);
        $slide->update(['is_active' => !$slide->is_active]);

        return back()->with('success', 'Status slide berhasil diperbarui.');
    }

    private function deleteStoredFile(?string $path): void
    {
        if (!$path || str_starts_with($path, 'http')) {
            return;
        }

        Storage::disk('uploads')->delete($path);
        Storage::disk('public')->delete($path);
    }
}
