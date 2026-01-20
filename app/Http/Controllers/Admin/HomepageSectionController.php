<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\HomepageSectionService;
use App\Models\HomepageSection;
use Illuminate\Http\Request;

class HomepageSectionController extends Controller
{
    protected HomepageSectionService $service;

    public function __construct(HomepageSectionService $service)
    {
        $this->service = $service;
    }

    /**
     * Danh sách các sections - Visual Builder với preview giống trang chủ
     */
    public function index()
    {
        $sections = $this->service->getAll()->keyBy('key');
        
        // Load tất cả dữ liệu cần thiết cho preview (giống HomeController)
        $slides = \App\Models\Slide::where("status", 1)
            ->where("type", \App\Enums\SliderType::HOME)->get();
        $slide_banners = \App\Models\Slide::where("status", 1)
            ->where("type", \App\Enums\SliderType::BANNER_AD)->get();
        $introMain = \App\Models\Intro::find(1);
        $homeFields = \App\Models\FieldCategory::whereNull("parent_id")
            ->where("status", 1)
            ->with(['fields' => function ($query) {
                $query->where('status', 1);
            }])
            ->get();
        $homeProjectCategories = \App\Models\ProjectCategory::where("status", 1)
            ->where("is_home", 1)
            ->with(["projects" => function ($query) {
                $query->where("status", 1);
            }])->get();
        $homeProjects = $homeProjectCategories->pluck('projects')->flatten();
        $allPosts = \App\Models\Post::where('status', 1)->latest()->take(3)->get();
        $brands = \App\Models\Brand::get();
        $testimonials = \App\Models\Testimonial::where('status', 1)->latest('id')->get();
        $setting = \App\Models\Setting::first();
        $sodem = \App\Models\Page::where('slug', 'counter')->first()->features ?? [];
        $tuyendung = \App\Models\Page::where('slug', 'tuyen-dung')->first();
        $daily = \App\Models\Page::where('slug', 'dai-ly')->first();

        return view('admin.homepage-sections.index', compact(
            'sections',
            'slides',
            'slide_banners',
            'introMain',
            'homeFields',
            'homeProjectCategories',
            'homeProjects',
            'allPosts',
            'brands',
            'testimonials',
            'setting',
            'sodem',
            'tuyendung',
            'daily'
        ));
    }

    /**
     * Form chỉnh sửa section (full page)
     */
    public function edit(int $id)
    {
        $section = $this->service->getById($id);
        
        if (!$section) {
            return redirect()->route('admin.homepage-sections.index')
                ->with('error', 'Không tìm thấy section.');
        }

        $settingsFields = $this->service->getSettingsFieldsForSection($section->key);

        return view('admin.homepage-sections.edit', compact('section', 'settingsFields'));
    }

    /**
     * Form chỉnh sửa section (AJAX partial)
     */
    public function editForm(int $id)
    {
        $section = $this->service->getById($id);
        
        if (!$section) {
            return response()->json(['error' => 'Không tìm thấy section'], 404);
        }

        $settingsFields = $this->service->getSettingsFieldsForSection($section->key);

        return view('admin.homepage-sections.edit-form', compact('section', 'settingsFields'));
    }

    /**
     * Lưu thay đổi section
     */
    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'background_image' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'settings' => 'nullable|array',
        ]);

        // Xử lý checkbox is_active
        $validated['is_active'] = $request->has('is_active');

        $this->service->update($id, $validated);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật section thành công!'
            ]);
        }

        return redirect()->route('admin.homepage-sections.index')
            ->with('success', 'Đã cập nhật section thành công!');
    }

    /**
     * Toggle trạng thái active (AJAX)
     */
    public function toggleActive(int $id)
    {
        $section = $this->service->toggleActive($id);

        return response()->json([
            'success' => true,
            'is_active' => $section->is_active,
            'message' => $section->is_active ? 'Đã bật section' : 'Đã tắt section',
        ]);
    }

    /**
     * Sắp xếp lại thứ tự sections (AJAX)
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:homepage_sections,id',
        ]);

        $this->service->reorder($request->order);

        return response()->json([
            'success' => true,
            'message' => 'Đã sắp xếp lại thứ tự sections.',
        ]);
    }
}
