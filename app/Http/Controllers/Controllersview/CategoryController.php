<?php
namespace App\Http\Controllers\Controllersview;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Organizer;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $categories = Category::with('organizer')->get();
            return view('categories.index', ['categories' => $categories]);
        } catch (\Exception $e) {
            return view('error', ['message' => 'Gagal mengambil data kategori: ' . $e->getMessage()]);
        }
    }
    public function create()
    {
        try {
            $organizers = Organizer::all();
            return view('categories.create', ['organizers' => $organizers]);
        } catch (\Exception $e) {
            return view('error', ['message' => 'Gagal memuat form kategori: ' . $e->getMessage()]);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'organizer_id' => 'required|exists:organizers,id',
            ], [
                'name.required' => 'Nama kategori harus diisi.',
                'name.unique' => 'Nama kategori sudah digunakan.',
                'organizer_id.required' => 'Organizer harus dipilih.',
                'organizer_id.exists' => 'Organizer yang dipilih tidak valid.',
            ]);

            $category = Category::create([
                'name' => $request->input('name'),
                'organizer_id' => $request->input('organizer_id'),
            ]);

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
        } catch (\Exception $e) {
            return view('error', ['message' => 'Gagal menambahkan kategori: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::with('organizer')->findOrFail($id);
            return view('categories.show', ['category' => $category]);
        } catch (\Exception $e) {
            return view('error', ['message' => 'Kategori tidak ditemukan: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            $organizers = Organizer::all();
            return view('categories.edit', ['category' => $category, 'organizers' => $organizers]);
        } catch (\Exception $e) {
            return view('error', ['message' => 'Gagal memuat form edit kategori: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:categories,name,' . $id,
                'organizer_id' => 'required|exists:organizers,id',
            ], [
                'name.required' => 'Nama kategori harus diisi.',
                'name.unique' => 'Nama kategori sudah digunakan.',
                'organizer_id.required' => 'Organizer harus dipilih.',
                'organizer_id.exists' => 'Organizer yang dipilih tidak valid.',
            ]);

            $category = Category::findOrFail($id);
            $category->update([
                'name' => $request->input('name'),
                'organizer_id' => $request->input('organizer_id'),
            ]);

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil diubah.');
        } catch (\Exception $e) {
            return view('error', ['message' => 'Gagal mengubah kategori: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();

            return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
        } catch (\Exception $e) {
            return view('error', ['message' => 'Gagal menghapus kategori: ' . $e->getMessage()]);
        }
    }
}
