<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\Slug;
use App\Settings\GeneralSettings;
use App\Settings\PageSettings;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Resolve slug cho prefix /du-an/{slug}.
     */
    public function resolveBySlug(string $slug)
    {
        $slugData = Slug::where('slug', $slug)->firstOrFail();
        $model = $slugData->sluggable;

        return match (true) {
            $model instanceof ProjectCategory => $this->byCategory($model),
            $model instanceof Project         => $this->detail($model),
            default => abort(404),
        };
    }

    public function byCategory(ProjectCategory $category)
    {
        $setting = app(\App\Settings\GeneralSettings::class);
        $pageTitle = $category->name;
        $bannerUrl = $category->image ? $category->image->path : ($setting->banner ?? asset('images/setting/no-banner.png'));
        $breadcrumbs = [
            ['label' => 'Dự án', 'url' => route('frontend.projects.index')],
            ['label' => $pageTitle, 'url' => '']
        ];

        $projectFeature = Project::with(['image', 'banner', 'slugData'])
            ->where('status', 1)
            ->where('project_category_id', $category->id)
            ->where('is_home', 1)
            ->first() 
            ?? 
            Project::where('status', 1)
            ->where('project_category_id', $category->id)
            ->latest()
            ->first();

        $query = Project::with(['image', 'slugData'])
            ->where('status', 1)
            ->where('project_category_id', $category->id)
            ->latest();

        if ($projectFeature) {
            $query->where('id', '!=', $projectFeature->id);
        }

        $projects = $query->paginate(10);

        $metaDescription = isset($setting->projects_description) ? \Illuminate\Support\Str::limit(strip_tags($setting->projects_description), 155) : '';

        return view('frontend.projects.index', [
            'projects'       => $projects,
            'projectFeature' => $projectFeature,
            'category'       => $category,
            'setting'        => $setting,
            'pageTitle'      => $pageTitle,
            'bannerUrl'      => $bannerUrl,
            'breadcrumbs'    => $breadcrumbs,
            'metaDescription'=> $metaDescription
        ]);
    }

    public function index()
    {
        $pageSettings = app(PageSettings::class);
        $setting      = app(GeneralSettings::class);

        $pageTitle    = $pageSettings->projects_title    ?: ($setting->projects_title ?? 'Dự án tiêu biểu');
        $pageSubtitle = $pageSettings->projects_headline ?: null;
        $bannerUrl    = $setting->banner ?? asset('images/setting/no-banner.png');
        $breadcrumbs  = [['label' => $pageTitle]];

        $projectFeature = Project::with(['image', 'banner', 'slugData'])->where('is_home', 1)->where('status', 1)->first()
            ?? Project::with(['image', 'banner', 'slugData'])->where('status', 1)->first();

        $query = Project::with(['image', 'slugData'])->where('status', 1)->latest();
        if ($projectFeature) {
            $query->where('id', '!=', $projectFeature->id);
        }
        $projects = $query->paginate(10);
        $metaDescription = isset($setting->projects_description) ? \Illuminate\Support\Str::limit(strip_tags($setting->projects_description), 155) : '';

        return view('frontend.projects.index', compact(
            'projectFeature', 'projects', 'setting',
            'pageTitle', 'pageSubtitle', 'bannerUrl', 'breadcrumbs', 'metaDescription'
        ));
    }

    /**
     * (Hàm này giữ nguyên để SlugController sử dụng)
     * Hiển thị trang CHI TIẾT một dự án.
     * @param Project $project
     * @return \Illuminate\View\View
     */
    public function detail(Project $project)
    {
        // Lấy các dự án liên quan (trừ dự án đang xem)
        $relatedProjects = Project::with(['image', 'slugData'])
            ->where("status", 1)
            ->where("id", '!=', $project->id)
            ->latest()
            ->limit(6)
            ->get();

        $setting = app(\App\Settings\GeneralSettings::class);
        $pageTitle = $project->name;
        $bannerUrl = $project->banner ? $project->banner->path : ($project->image ? $project->image->path : ($setting->banner ?? asset('images/setting/no-banner.png')));
        $breadcrumbs = [
            ['label' => 'Dự án', 'url' => route('frontend.projects.index')],
            ['label' => $project->name, 'url' => ''],
        ];

        $images = collect();
        if ($project->gallery && is_array($project->gallery)) {
            $first = reset($project->gallery);
            if (is_numeric($first)) {
                $medias = \Awcodes\Curator\Models\Media::whereIn('id', $project->gallery)->get();
                foreach ($medias as $media) {
                    $images->push($media->path);
                }
            } else {
                foreach ($project->gallery as $galImg) {
                    $url = is_string($galImg) ? $galImg : ($galImg['url'] ?? $galImg['path'] ?? null);
                    if ($url) {
                        $images->push($url);
                    }
                }
            }
        }
        $images = $images->filter()->values();
        $metaDescription = \Illuminate\Support\Str::limit(strip_tags($project->description ?? ''), 155);

        return view("frontend.projects.detail", compact(
            "project",
            "relatedProjects",
            "pageTitle",
            "bannerUrl",
            "breadcrumbs",
            "setting",
            "images",
            "metaDescription"
        ));
    }
}