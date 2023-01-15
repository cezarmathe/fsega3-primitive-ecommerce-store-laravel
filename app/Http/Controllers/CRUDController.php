<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;

class CRUDController extends Controller
{
    public function index()
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return redirect()->route('products:index');
        }
        else if (!$user->is_admin)
        {
            return redirect()->route('products:index');
        }

        $products = Product::latest()->paginate(5);

        return view('products.index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return redirect()->route('products:index');
        }
        else if (!$user->is_admin)
        {
            return redirect()->route('products:index');
        }

        return view('products.create');
    }

    public function store(Request $request)
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return redirect()->route('products:index');
        }
        else if (!$user->is_admin)
        {
            return redirect()->route('products:index');
        }

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return redirect()->route('products:index');
        }
        else if (!$user->is_admin)
        {
            return redirect()->route('products:index');
        }

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return redirect()->route('products:index');
        }
        else if (!$user->is_admin)
        {
            return redirect()->route('products:index');
        }

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return redirect()->route('products:index');
        }
        else if (!$user->is_admin)
        {
            return redirect()->route('products:index');
        }

        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'image' => 'required',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $user = User::find(auth()->id());
        if (!$user) {
            return redirect()->route('products:index');
        }
        else if (!$user->is_admin)
        {
            return redirect()->route('products:index');
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully');
    }
}
