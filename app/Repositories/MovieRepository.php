<?php

namespace App\Repositories;

use App\Interfaces\MovieRepositoryInterface;
use App\Models\Movie;
use App\Models\Category;

class MovieRepository implements MovieRepositoryInterface
{
    public function getAllMovies($search = null) {
        $query = Movie::latest();
        if ($search) {
            $query->where('judul', 'like', '%' . $search . '%')
                  ->orWhere('sinopsis', 'like', '%' . $search . '%');
        }
        return $query->paginate(6)->withQueryString();
    }

    public function getMovieById($id) {
        return Movie::findOrFail($id);
    }

    public function getAllCategories() {
        return Category::all();
    }

    public function storeMovie(array $data) {
        return Movie::create($data);
    }

    public function updateMovie($id, array $data) {
        $movie = Movie::findOrFail($id);
        $movie->update($data);
        return $movie;
    }

    public function deleteMovie($id) {
        return Movie::destroy($id);
    }
}
