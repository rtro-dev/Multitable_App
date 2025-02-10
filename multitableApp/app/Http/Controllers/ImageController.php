<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Image $image)
    {
        try {
            // Verificar si es la última imagen
            if ($image->sale->images->count() <= 1) {
                return response()->json([
                    'success' => false,
                    'error' => 'No se puede eliminar la última imagen del producto.'
                ]);
            }
    
            // Si la imagen era la principal, hacer principal otra imagen
            if ($image->is_main) {
                $newMainImage = $image->sale->images->where('id', '!=', $image->id)->first();
                if ($newMainImage) {
                    $newMainImage->update(['is_main' => true]);
                }
            }
    
            // Eliminar el archivo físico
            if (!Storage::disk('public')->delete($image->route)) {
                return response()->json([
                    'success' => false,
                    'error' => 'No se pudo eliminar el archivo de imagen.'
                ]);
            }
            
            // Eliminar el registro de la base de datos
            $image->delete();
    
            return response()->json(['success' => true]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error al eliminar la imagen: ' . $e->getMessage()
            ]);
        }
    }
}
