<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use Auth;

class IssueController extends Controller
{
    public function index()
    {
        return Issue::orderBy('status', 'desc')->with('user:id,name')->get();
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $inputs = $request->all();
        $inputs['submitted_by'] = $user->id;
        $inputs['status'] = 0;

        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable',
            'description_markdown' => 'nullable',
            'solution' => 'nullable',
        ]);

        Issue::create($inputs);

        return response()->json(['message' => 'Issue has been created']);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $inputs = $request->all();
        $id = $request->id;
        $inputs['status'] = $request->status == 'true' ? 1 : 0;
        
        $this->validate($request, [
            'name' => 'required',
            'description' => 'nullable',
            'description_markdown' => 'nullable',
            'solution' => 'nullable',
            'status' => 'required'
        ]);

        Issue::where('id', $id)->update($inputs);

        return response()->json(['message' => 'Issue has been updated']);
    }

    public function destroy($id)
    {
        $issue = Issue::find($id);

        if ($issue->delete()) {
            return response()->json(['message' => 'Issue has been deleted!']);
        }
    }
}
