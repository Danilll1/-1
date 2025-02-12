<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use App\Models\Folder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function index()
    {
        $folders = Folder::where('user_id', Auth::user()->id)->get();
        $files = File::where('user_id', Auth::user()->id)->get();
        return view('files', compact('files', 'folders'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file'
        ]);
    
        $folderName = Auth::user()->name . '-' . Auth::user()->id . '-' . 'folder';
    
        if (!\Storage::disk('public')->exists('uploads/' . $folderName)) {
            \Storage::disk('public')->makeDirectory('uploads/' . $folderName);
        }
    
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $path = $file->storeAs('uploads/' . $folderName, $fileName, 'public');
        
        $userModel = Auth::user();
        $userModel->storage_name = $folderName;
        $userModel->save();

        $fileModel = new File;
        $fileModel->name = $fileName;
        $fileModel->path = $path;
        $fileModel->user_id = Auth::user()->id;
        $fileModel->save();
    
        return redirect()->back()->with('success', 'Файл успешно загружен!');
    }
    
    public function folderStore(Request $request) {
        $validatedData = $request->validate([
            'foldersName' => 'required|string|max:255'
        ]);
    
        $foldersName = $validatedData['foldersName'];
        $folderName = Auth::user()->name . '-' . Auth::user()->id . '-' . 'folder';

        if (!\Storage::disk('public')->exists('uploads/' . $folderName)) {
            \Storage::disk('public')->makeDirectory('uploads/' . $folderName);
        }

        $userModel = Auth::user();
        $userModel->storage_name = $folderName;
        $userModel->save();
        
        \Storage::disk('public')->makeDirectory('uploads/' . Auth::user()->storage_name . '/' . $foldersName);
        $folder = new Folder;
        $folder->name = $foldersName;
        $folder->user_id = Auth::user()->id;
        $folder->path = 'uploads/'. $folderName . '/' . $foldersName;
        $folder->save();
        return redirect()->back()->with('success', 'Папка успешно создана!');
    }

    public function folderAdd(Request $request)
    {
        $folderId = $request->folderId;
        $folder = Folder::find($folderId);
        $file = $request->file('file');
        $fileName = $file->getClientOriginalName();
        $folderName = Auth::user()->storage_name;
        $path = $file->storeAs($folderName . '/' . $folder->path, $fileName, 'public');

        $fileModel = new File;
        $fileModel->name = $fileName;
        $fileModel->path = $path;
        $fileModel->user_id = Auth::user()->id;
        $fileModel->folder_id = $folderId;
        $fileModel->save();
        return redirect()->back()->with('success', 'Файл успешно добавлен в папку!');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        $file = File::find($id);
        if ($file->user_id == Auth::user()->id) {
            if (\Storage::disk('public')->exists($file->path)) {
                \Storage::disk('public')->delete($file->path);
            }
            $file->delete();
            return redirect()->back()->with('success', 'Файл успешно удален!');
        }
    }
}
