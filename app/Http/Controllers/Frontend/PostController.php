<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Slug;
use App\Helpers\TocHelper;
use Illuminate\Http\Request;
use App\Models\PostCategory;
use App\Settings\PageSettings;

class PostController extends Controller
{
    /**
     * Resolve slug cho prefix /tin-tuc/{slug}.
     */
    public function resolveBySlug(string $slug)
    {
        $slugData = Slug::where('slug', $slug)->firstOrFail();
        $model = $slugData->sluggable;

        return match (true) {
            $model instanceof PostCategory => $this->postByCate($model),
            $model instanceof Post         => $this->detail($model),
            default => abort(404),
        };
    }

    public function index()
    {
        $pageSettings = app(PageSettings::class);
        // 1. Lấy các danh mục Root (parent_id = 0)
        $postCategories = PostCategory::whereNull("parent_id")
            ->where("status", 1)
            ->get();

        // 2. Xử lý lấy bài viết bao gồm cả danh mục con (Recursive)
        foreach ($postCategories as $category) {
            $childIds = PostCategory::getTreeIds($category->id);
            $posts = Post::whereIn('post_category_id', $childIds)
                ->where("status", 1)
                ->latest()
                ->take(9)
                ->get();
            $category->setRelation('posts', $posts);
        }

        // 3. Bài viết nổi bật (hiển thị hero riêng)
        $featuredPost = Post::where('status', 1)
            ->where('is_featured', 1)
            ->with(['image', 'category'])
            ->latest('updated_at')
            ->first();

        // 4. Danh sách bài viết (loại trừ bài nổi bật đã hiển thị riêng)
        $postsQuery = Post::where('status', 1)->with(['image', 'category'])->latest();
        if ($featuredPost) {
            $postsQuery->where('id', '!=', $featuredPost->id);
        }
        $posts = $postsQuery->paginate(12);

        return view('frontend.post.index', compact('postCategories', 'pageSettings', 'posts', 'featuredPost'));
    }

    public function detail(Post $post)
    {
        $post->load('category');
        
        // Lấy danh mục cha để làm menu (nếu cần)
        $allCategories = PostCategory::select("name","id")->where('parent_id', 0)
            ->orWhereNull('parent_id') // Handle cả trường hợp null cho chắc
            ->where('status', 1)
            ->get();

        // Lấy bài viết liên quan (cùng danh mục)
        $relatedPosts = Post::where('status', 1)
            ->where('post_category_id', $post->post_category_id)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(6)
            ->get();
        
        $processedData = TocHelper::process($post->content);
        $contentHtml = $processedData['html'];
        $tocList = $processedData['toc'];
        return view('frontend.post.detail', compact(
            "post",
            "allCategories",
            "relatedPosts",
            "contentHtml",
            "tocList",
        ));
    }

    public function postByCate(PostCategory $postCategory)
    {
        $allCategories = PostCategory::where('parent_id', 0)
            ->orWhereNull('parent_id')
            ->where('status', 1)
            ->get();

        // [FIX]: Thay thế đoạn if/else cũ bằng hàm getTreeIds mạnh mẽ hơn
        // Hàm này lấy được cả cháu, chắt... chứ không chỉ mỗi con trực tiếp
        $categoryIds = PostCategory::getTreeIds($postCategory->id);

        // Query bài viết
        $posts = Post::whereIn('post_category_id', $categoryIds)
            ->where('status', 1)
            ->latest()
            ->paginate(10);

        $featuredPosts = Post::where('status', 1)
            ->latest('updated_at')
            ->limit(5)
            ->get();

        return view('frontend.post.postByCate', [
            'category' => $postCategory,
            'posts' => $posts,
            'allCategories' => $allCategories,
            'featuredPosts' => $featuredPosts,
        ]);
    }
}