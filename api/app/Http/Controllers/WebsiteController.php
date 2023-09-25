<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    private $allowedParamsNewWebsite = ['name', 'user_id'];
    public function index(Request $request)
    {
        $isSuper = checkSuperAdmin($request);
        if (!$isSuper["isSuperAdmin"]) {
            return sendError("You do not have permission to do this.", 403);
        }

        return response()->json(Website::all());
    }

    public function show(Request $request)
    {
        $isSuper = checkSuperAdmin($request);
        if ($isSuper["isSuperAdmin"]) {
            return response()->json(Website::findOrFail($isSuper["websiteId"]));
        } else {
            return response()->json(Website::findOrFail($request->user()->website->id));
        }
    }

    public function store(Request $request)
    {
        if ($request->user()->right->right !== "super-admin") {
            return sendError("You do not have permission to do this.", 403);
        }

        if ($request->missing(['name'])) {
            return sendError("Missing required parameters.", 422);
        }

        $filteredRequest = $request->only($this->allowedParamsNewWebsite);

        $website = new Website();
        $website->name = $filteredRequest['name'];
        $website->user_id = $filteredRequest['user_id'];
        $website->save();

        return sendSuccess("Website created.");
    }
}
