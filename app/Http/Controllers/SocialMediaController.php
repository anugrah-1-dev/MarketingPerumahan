<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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
        $data = $this->validatePayload($request);
        $data = $this->storeFiles($request, $data);

        $data['is_active'] = true;
        SocialMedia::create($data);

        return back()->with('success', "Konten \"{$data['title']}\" berhasil ditambahkan!");
    }

    public function update(Request $request, $id)
    {
        $item = SocialMedia::findOrFail($id);

        $data = $this->validatePayload($request, $item);
        $data = $this->storeFiles($request, $data, $item);

        $item->update($data);

        return back()->with('success', "Konten \"{$item->title}\" berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $item = SocialMedia::findOrFail($id);

        $this->deleteStoredFile($item->thumbnail_url);
        $this->deleteStoredFile($item->media_path);

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

    private function validatePayload(Request $request, ?SocialMedia $item = null): array
    {
        $data = $request->validate([
            'platform'    => ['required', 'string', Rule::in(['youtube', 'tiktok', 'instagram'])],
            'title'       => 'required|string|max:150',
            'description' => 'nullable|string|max:300',
            'content_url' => ['nullable', 'url', 'max:500', 'starts_with:http://,https://'],
            'thumbnail'   => 'nullable|image|max:2048',
            'media_file'  => 'nullable|file|mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime|max:51200',
        ], [
            'media_file.mimetypes' => 'File media harus berupa foto atau video yang didukung.',
            'media_file.max' => 'Ukuran file media maksimal 50 MB.',
            'content_url.starts_with' => 'URL konten harus diawali http:// atau https://.',
        ]);

        $hasExistingMedia = $item && ($item->content_url || $item->media_path);
        if (!$request->filled('content_url') && !$request->hasFile('media_file') && !$hasExistingMedia) {
            throw ValidationException::withMessages([
                'content_url' => 'Isi URL konten atau upload foto/video terlebih dahulu.',
            ]);
        }

        return $data;
    }

    private function storeFiles(Request $request, array $data, ?SocialMedia $item = null): array
    {
        if ($request->hasFile('thumbnail')) {
            if ($item) {
                $this->deleteStoredFile($item->thumbnail_url);
            }

            $data['thumbnail_url'] = $request->file('thumbnail')->store('social-media', 'uploads');
        }

        if ($request->hasFile('media_file')) {
            if ($item) {
                $this->deleteStoredFile($item->media_path);
            }

            $file = $request->file('media_file');
            $data['media_path'] = $file->store('social-media', 'uploads');
            $data['media_type'] = str_starts_with((string) $file->getMimeType(), 'video/') ? 'video' : 'image';
        }

        unset($data['thumbnail'], $data['media_file']);

        return $data;
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
