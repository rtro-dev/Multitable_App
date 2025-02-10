<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\User;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
        //$this->middleware('auth')->only('purchase');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Sale::with(['category', 'images']);

        $sales = $query->latest()->get();
        $user = Auth::user();

        return view('sales.index', compact('sales', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $maxFiles = Setting::first()->maxFiles;
        return view('sales.create', compact('categories', 'maxFiles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $maxFiles = Setting::first()->maxFiles;
        $request->validate([
            'product' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|gt:0',
            'category_id' => 'required|exists:categories,id',
            'images' => "array|max:$maxFiles",
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $sale = Sale::create([
            'product' => $request->product,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
            'isSold' => false,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                // Cambiamos la forma de almacenar la imagen
                $path = $image->store('images', 'public');
                Image::create([
                    'sale_id' => $sale->id,
                    'route' => $path,
                    'is_main' => ($request->input('main_image') == $key)
                ]);
            }
        }

        return redirect()->route('sales.index')->with('success', 'Product created successfully.');
    }

    public function shop(Sale $sale)
{
    // Verificar si el producto ya está vendido
    if ($sale->isSold) {
        return redirect()->route('sales.index')
            ->with('error', 'This product has already been sold.');
    }

    // Actualizar el estado del producto a vendido
    $sale->update([
        'isSold' => true
    ]);

    return redirect()->route('sales.index')
        ->with('success', 'Product bought successfully!');
}

    /**
     * Display the specified resource.
     */
    public function show(Sale $sale)
    {
        $sale = Sale::with('category', 'user', 'images')->findOrFail($id);

        if ($sale->isSold && Auth::id() != $sale->user_id) {
            return redirect()->route('sales.index')
                ->with('error', 'Not available anymore.');
        }

        return view('sales.show', compact('sale'));
    }

    public function showUserSales(User $user)
    {
        $sales = Sale::where('user_id', $user->id)
            ->with(['category', 'images'])
            ->latest()
            ->get();

        return view('sales.index', [
            'sales' => $sales,
            'user' => Auth::user()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        $categories = Category::all();
        $maxFiles = Setting::first()->maxFiles;
        return view('sales.edit', compact('sale', 'categories', 'maxFiles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        $maxFiles = Setting::first()->maxFiles;
        $request->validate([
            'product' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|gt:0',
            'category_id' => 'required|exists:categories,id',
            'images' => "array|max:$maxFiles",
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'isSold' => 'required|boolean',
            'main_image' => 'nullable|exists:images,id'
        ]);

        $sale->update([
            'product' => $request->product,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'isSold' => $request->isSold
        ]);

        // Manejar imágenes nuevas si se suben
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('images', 'public');
                Image::create([
                    'sale_id' => $sale->id,
                    'route' => $path
                ]);
            }
        }

        // Actualizar imagen principal si se selecciona
        if ($request->has('main_image')) {
            // Primero, quitar la marca de principal de todas las imágenes
            $sale->images()->update(['is_main' => false]);
            // Luego, marcar la nueva imagen principal
            Image::where('id', $request->main_image)
             ->where('sale_id', $sale->id) // Añadir esta condición por seguridad
             ->update(['is_main' => true]);
        }

        return redirect()->route('sales.index')
            ->with('success', 'Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        $sale->delete();
        return redirect()->route('sales.index')->with('success', 'Product deleted.');
    }
}
