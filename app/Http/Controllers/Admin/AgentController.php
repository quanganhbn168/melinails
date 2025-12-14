<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\AgencyRequest;

class AgentController extends Controller
{
    public function index(Request $request)
    {
        $query = AgencyRequest::query();

        if ($request->has('keyword') && $request->keyword != '') {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%")
                  ->orWhere('email', 'like', "%{$keyword}%")
                  ->orWhere('shop_name', 'like', "%{$keyword}%");
            });
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.agents.index', compact('items'));
    }

    public function show($id)
    {
        $item = AgencyRequest::findOrFail($id);
        
        // Mark as reviewed if status is new
        if ($item->status == 'new') {
            $item->update(['status' => 'reviewed']);
        }

        return view('admin.agents.show', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = AgencyRequest::findOrFail($id);
        $item->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function destroy($id)
    {
        $item = AgencyRequest::findOrFail($id);
        $item->delete();

        return redirect()->route('admin.agents.index')->with('success', 'Xóa yêu cầu thành công!');
    }

    public function bulkAction(Request $request)
    {
        $ids = explode(',', $request->ids);
        $action = $request->action;

        if ($action == 'delete') {
            AgencyRequest::whereIn('id', $ids)->delete();
            return response()->json(['success' => true, 'message' => 'Xóa thành công!']);
        }

        return response()->json(['success' => false, 'message' => 'Hành động không hợp lệ!']);
    }
}
