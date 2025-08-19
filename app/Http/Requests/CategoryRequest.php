<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;
use Illuminate\Validation\Rule;
class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $category = $this->route('category');
        $categoryId = $category ? $category->id : null;
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($categoryId),
            ],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($categoryId),
            ],
            'parent_id' => [
                'nullable',
                'integer',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    if ($category && $value) {
                        if ($value == $category->id) {
                            $fail('Không thể chọn chính danh mục này làm danh mục cha.');
                        }
                        if (in_array($value, $category->getAllDescendantIds())) {
                             $fail('Không thể chọn một danh mục con làm danh mục cha.');
                        }
                    }
                },
            ],
            'cate_type' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'banner' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'status' => ['boolean'],
            'is_home' => ['boolean'],
            'is_menu' => ['boolean'],
            'is_footer' => ['boolean'],
            'position' => ['nullable', 'integer'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'attributes' => ['nullable', 'array'],
            'attributes.*' => ['integer', 'exists:attributes,id'], 
        ];
    }
    /**
     * Get the custom error messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.unique' => 'Tên danh mục này đã tồn tại.',
            'slug.unique' => 'Đường dẫn tĩnh (slug) này đã tồn tại.',
            'parent_id.exists' => 'Danh mục cha được chọn không hợp lệ.',
            'image.image' => 'File phải là hình ảnh.',
            'image.mimes' => 'Hình ảnh phải có định dạng: jpeg, png, jpg, webp.',
            'image.max' => 'Hình ảnh không được lớn hơn 2MB.',
            'icon.image' => 'File icon phải là hình ảnh.',
            'icon.mimes' => 'Icon phải có định dạng: jpeg, png, jpg, webp.',
            'icon.max' => 'Icon không được lớn hơn 2MB.',
            'banner.image' => 'File banner phải là hình ảnh.',
            'banner.mimes' => 'Banner phải có định dạng: jpeg, png, jpg, webp.',
            'banner.max' => 'Banner không được lớn hơn 2MB.',
            'attributes.*.exists' => 'Thuộc tính được chọn không hợp lệ.'
        ];
    }
}