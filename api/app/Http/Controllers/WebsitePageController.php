<?php

namespace App\Http\Controllers;

use App\Models\Website;
use App\Models\WebsitePage;
use Illuminate\Http\Request;

class WebsitePageController extends Controller
{
    private $allowedParamsNewPage = ['title', 'meta_title', 'meta_description'];

    public function store(Request $request)
    {
        $isSupper = checkSuperAdmin($request);
        $filteredRequest = $request->only($this->allowedParamsNewPage);
        $page = new WebsitePage();

        if ($request->user()->right->right === "editor") {
            return sendError("You do not have permission to do this.", 403);
        }

        if ($request->missing($this->allowedParamsNewPage)) {
            return sendError("Missing required parameters.", 422);
        }

        $page->fill($filteredRequest);

        if (!$isSupper["isSuperAdmin"]) {
            $page->website_id = $request->user()->website->id;
        } else {
            $page->website_id = Website::findOrFail($isSupper["websiteId"])->id;
        }

        $page->save();

        return sendSuccess("Website page created.");
    }
}
