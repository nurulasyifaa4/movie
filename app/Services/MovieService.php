<?php

namespace App\Services;

use App\Interfaces\MovieRepositoryInterface;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MovieService
{
    protected $movieRepository;

    public function __construct(MovieRepositoryInterface $movieRepository) {
        $this->movieRepository = $movieRepository;
    }

    public function index($search) {
        return $this->movieRepository->getAllMovies($search);
    }

    public function getFormCreateData() {
        return $this->movieRepository->getAllCategories();
    }

    public function getMovieById($id) {
        return $this->movieRepository->getMovieById($id);
    }

    public function store($data) {
        if (isset($data['foto_sampul'])) {
            $data['foto_sampul'] = $data['foto_sampul']->store('movie_covers', 'public');
        }
        return $this->movieRepository->storeMovie($data);
    }

    public function update($id, $data) {
        $movie = $this->movieRepository->getMovieById($id);

        if (isset($data['foto_sampul'])) {
            // Logika hapus foto lama dan simpan yang baru
            $fileName = Str::uuid().'.'.$data['foto_sampul']->getClientOriginalExtension();
            $data['foto_sampul']->move(public_path('images'), $fileName);

            if (File::exists(public_path('images/'.$movie->foto_sampul))) {
                File::delete(public_path('images/'.$movie->foto_sampul));
            }
            $data['foto_sampul'] = $fileName;
        }

        return $this->movieRepository->updateMovie($id, $data);
    }

    public function delete($id) {
        $movie = $this->movieRepository->getMovieById($id);
        if (File::exists(public_path('images/'.$movie->foto_sampul))) {
            File::delete(public_path('images/'.$movie->foto_sampul));
        }
        return $this->movieRepository->deleteMovie($id);
    }

    /**
     * CLEAN CODE: Fungsi privat untuk menghapus file agar tidak duplikasi kode
     */
    private function deleteImageFile($fileName) {
        if ($fileName && File::exists(public_path('images/'.$fileName))) {
            File::delete(public_path('images/'.$fileName));
    }
}
}
