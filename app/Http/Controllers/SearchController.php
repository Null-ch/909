<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use Illuminate\Http\RedirectResponse;

/**
 * The site header's search box has always posted here, but no route ever
 * answered it (confirmed 404s in the nginx access log for `/search?q=...`).
 * Rather than duplicate the Catalog Livewire page's filtering/sorting/
 * pagination UI, this redirects into it with `search` pre-filled — the
 * Catalog component already binds that query param via #[Url].
 */
class SearchController extends Controller
{
    public function __invoke(SearchRequest $request): RedirectResponse
    {
        $term = $request->term();

        return redirect()->to(
            $term === '' ? route('catalog') : route('catalog').'?'.http_build_query(['search' => $term])
        );
    }
}
