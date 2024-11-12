<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Ticket;
use App\Services\FrontService;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    protected $frontService;

    public function __construct(FrontService $frontService)
    {
        $this->frontService = $frontService;
    }

    public function index()
    {
        $data = $this->frontService->getFrontPageData();
        //        dd($data);
        return view('front.index', $data);
    }

    // Model binding
    public function details(Ticket $ticket)
    {
        // dd($ticket);
        $googleMapsApiKey = env('GOOGLE_MAPS_API_KEY');
        return view('front.details', compact('ticket','googleMapsApiKey'));
    }

    public function category(Category $category)
    {
        // dd($category);
        return view('front.category', compact('category'));
    }
}
