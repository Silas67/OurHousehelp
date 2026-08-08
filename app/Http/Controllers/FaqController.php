<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    // Public-facing FAQ page
    public function publicIndex()
    {
        $categories = [
            'general' => Faq::where('category', 'general')->get(),
            'clients' => Faq::where('category', 'clients')->get(),
            'workers' => Faq::where('category', 'workers')->get(),
            'platform' => Faq::where('category', 'platform')->get(),
            'skilled' => Faq::where('category', 'skilled')->get(),
        ];

        return view('faq', compact('categories'));
    }

    // Admin FAQ management
    public function index()
    {
        $faqs = Faq::latest()->get();
        return view('admin.faqs.index', compact('faqs'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'question' => 'required|string|min:10|max:255',
            'answer' => 'required|string|min:20',
        ]);

        // Create the FAQ entry
        Faq::create($validated);

        // Redirect with success message
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ created successfully!');
    }

    public function show($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faqs.show', compact('faq'));
    }
    

    public function update(Request $request, $id)
    {
        $faq = Faq::findOrFail($id); // Ensure you are retrieving the correct FAQ record
    
        $validated = $request->validate([
            'category' => 'required|in:general,clients,workers,platform,skilled',
            'question' => 'required|string|min:10|max:255',
            'answer' => 'required|string|min:20',
        ]);
    
        $faq->update($validated);
    
        // Redirect with success message
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ Updated successfully!');
    }
    

    public function destroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();
    
        return redirect()->route('admin.faqs.index')->with('success', 'FAQ deleted successfully.');
    }
    
}