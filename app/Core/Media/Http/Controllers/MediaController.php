<?php

namespace App\Core\Media\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Core\Media\Models\Media;
use App\Core\Media\Models\MediaFolder;
use App\Core\Media\Services\MediaService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function __construct(
        protected MediaService $mediaService
    ) {}

    public function index(Request $request)
    {
        $folderId = $request->input('folder_id');
        $contents = $this->mediaService->getFolderContents($folderId);

        if ($request->expectsJson()) {
            return response()->json(['data' => $contents]);
        }

        return view('pages.media.index', [
            'folders' => $contents['folders'],
            'items' => $contents['media'],
            'currentFolder' => $folderId ? MediaFolder::find($folderId) : null,
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'folder_id' => 'nullable|exists:media_folders,id',
        ]);

        $media = $this->mediaService->upload(
            $request->file('file'),
            $request->input('folder_id'),
            $request->user()->id
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $media], 201);
        }

        return back()->with('success', 'File uploaded successfully.');
    }

    public function show(Request $request, Media $media)
    {
        if ($request->expectsJson()) {
            return response()->json(['data' => $media]);
        }

        return view('pages.media.show', compact('media'));
    }

    public function update(Request $request, Media $media)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $media->update(['name' => $request->name]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $media]);
        }

        return back()->with('success', 'Media updated successfully.');
    }

    public function destroy(Request $request, Media $media)
    {
        $this->mediaService->delete($media);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Media deleted successfully.');
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $results = $this->mediaService->search(
            $request->input('q'),
            $request->input('type')
        );

        return response()->json(['data' => $results]);
    }

    public function storeFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:media_folders,id',
        ]);

        $folder = $this->mediaService->createFolder(
            $request->name,
            $request->input('parent_id'),
            $request->user()->id
        );

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'data' => $folder], 201);
        }

        return back()->with('success', 'Folder created successfully.');
    }

    public function destroyFolder(Request $request, MediaFolder $folder)
    {
        $this->mediaService->deleteFolder($folder);

        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Folder deleted successfully.');
    }

    public function picker(Request $request)
    {
        $query = Media::query();

        if ($type = $request->input('type')) {
            $query->where('mime_type', 'LIKE', "{$type}%");
        }

        if ($search = $request->input('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $media = $query->latest()->paginate(20);

        return response()->json(['data' => $media]);
    }
}
