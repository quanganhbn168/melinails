<?php

namespace App\Livewire\Tag;

use App\Models\Tag;
use App\Enums\TagType;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Str;

class TagManager extends Component
{
    public $type = 'work_order'; // Filter theo type
    public $showModal = false;
    public $editingId = null;

    // Form fields
    public $name = '';
    public $color = '#6c757d';
    public $description = '';

    protected $rules = [
        'name' => 'required|min:2|max:50',
        'color' => 'required|regex:/^#[0-9A-Fa-f]{6}$/',
        'description' => 'nullable|max:255',
    ];

    #[Layout('layouts.admin')]
    public function render()
    {
        $tags = Tag::where('type', $this->type)
            ->ordered()
            ->get();

        $types = TagType::cases();

        return view('livewire.tag.tag-manager', [
            'tags' => $tags,
            'types' => $types,
        ]);
    }

    public function openModal($id = null)
    {
        $this->resetForm();
        
        if ($id) {
            $tag = Tag::find($id);
            if ($tag) {
                $this->editingId = $id;
                $this->name = $tag->name;
                $this->color = $tag->color;
                $this->description = $tag->description;
            }
        }
        
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->color = '#6c757d';
        $this->description = '';
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => Str::slug($this->name),
            'type' => $this->type,
            'color' => $this->color,
            'description' => $this->description,
        ];

        if ($this->editingId) {
            $tag = Tag::find($this->editingId);
            $tag->update($data);
            session()->flash('success', 'Đã cập nhật tag!');
        } else {
            Tag::create($data);
            session()->flash('success', 'Đã tạo tag mới!');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $tag = Tag::find($id);
        if ($tag) {
            $tag->delete();
            session()->flash('success', 'Đã xóa tag!');
        }
    }

    public function updateOrder($items)
    {
        foreach ($items as $item) {
            Tag::where('id', $item['value'])->update(['sort_order' => $item['order']]);
        }
    }
}
