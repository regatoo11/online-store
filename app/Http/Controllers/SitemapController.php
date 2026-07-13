<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        return response()->view('sitemap', [], 200)->header('Content-Type', 'text/xml');
    }
}
