<?php
namespace App\Http\Requests;
use App\Models\Category; 
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        $categoryId = $this->route('category')?->id;
        return [
            'name' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories')->ignore($categoryId)],
            'image' => 'sometimes|image|mimes:jpeg,png,webp|max:1024',
            'banner' => 'sometimes|image|mimes:jpeg,png,webp|max:2048',
            'parent_id' => [
                'nullable',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !Category::where('id', $value)->exists()) {
                        $fail('Danh mục cha được chọn không hợp lệ.');
                    }
                },
            ],
            'status' => 'boolean',
            'attributes' => 'nullable|array',
            'attributes.*' => 'integer|exists:attributes,id',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords' => 'nullable|string',
            'meta_image' => 'nullable|string',
        ];
    }
}