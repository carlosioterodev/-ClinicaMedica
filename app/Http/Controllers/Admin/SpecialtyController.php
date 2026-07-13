<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Specialty;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SpecialtyController extends Controller
{
    public function index()
    {
        $specialties = Specialty::latest()->paginate(15);
        return view('admin.specialties.index', compact('specialties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        Specialty::create($validated);

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidad creada.');
    }

    public function update(Request $request, Specialty $specialty)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:specialties,name,' . $specialty->id,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $specialty->update($validated);

        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidad actualizada.');
    }

    public function destroy(Specialty $specialty)
    {
        $specialty->delete();
        return redirect()->route('admin.specialties.index')
            ->with('success', 'Especialidad eliminada.');
    }
}
