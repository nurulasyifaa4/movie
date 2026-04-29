<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Services\MovieService;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    protected $movieService;

    public function __construct(MovieService $movieService)
    {
        $this->movieService = $movieService;
    }

    public function index(Request $request)
    {
        // Pindah logika search ke Service
        $movies = $this->movieService->index($request->search);
        return view('homepage', compact('movies'));
    }

    public function detail($id)
    {
        $movie = $this->movieService->getMovieById($id);
        return view('detail', compact('movie'));
    }

    public function create()
    {
        // Ambil kategori melalui Service
        $categories = $this->movieService->getFormCreateData();
        return view('input', compact('categories'));
    }

    public function store(StoreMovieRequest $request)
    {
        // Logika upload file dan create sudah dihandle di Service
        $this->movieService->store($request->validated());
        return redirect('/')->with('success', 'Film berhasil ditambahkan.');
    }

    public function data(Request $request)
    {
        // Gunakan fungsi index yang sama di service untuk konsistensi
        $movies = $this->movieService->index($request->search, 10);
        return view('data-movies', compact('movies'));
    }

    public function form_edit($id)
    {
        $movie = $this->movieService->getMovieById($id);
        $categories = $this->movieService->getFormCreateData();
        return view('form-edit', compact('movie', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'sinopsis' => 'required|string',
            'tahun' => 'required|integer',
            'pemain' => 'required|string',
            'foto_sampul' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $this->movieService->update($id, $validated);

        return redirect('/movies/data')->with('success', 'Data berhasil diperbarui');
    }

    public function delete($id)
    {
        // CLEAN CODE: Semua logika hapus file dan DB sudah di Service
        $this->movieService->delete($id);

        return redirect('/movies/data')->with('success', 'Data berhasil dihapus');
    }
}
