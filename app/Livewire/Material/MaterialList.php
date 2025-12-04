<?php

namespace App\Livewire\Material;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Material;

class MaterialList extends Component
{
    use WithPagination;

    public $search = '';
    public $name, $code, $short_name, $unit = 'cái', $price; // Form input
    public $is_edit = false;
    public $edit_id;

    // Reset form
    public function resetForm() {
        $this->reset(['name', 'code', 'short_name', 'unit', 'price', 'is_edit', 'edit_id']);
    }

    public function save() {
        $this->validate([
            'name' => 'required',
            'code' => 'nullable|unique:materials,code,' . $this->edit_id,
        ]);

        $data = [
            'name' => $this->name,
            'code' => $this->code,
            'short_name' => $this->short_name, // Quan trọng: Nhập từ khóa viết tắt vào đây
            'unit' => $this->unit,
            'price' => $this->price ?? 0,
        ];

        if ($this->is_edit) {
            Material::find($this->edit_id)->update($data);
        } else {
            Material::create($data);
        }

        $this->resetForm();
        session()->flash('success', 'Đã lưu vật tư!');
    }

    public function edit($id) {
        $m = Material::find($id);
        $this->edit_id = $m->id;
        $this->name = $m->name;
        $this->code = $m->code;
        $this->short_name = $m->short_name;
        $this->unit = $m->unit;
        $this->price = $m->price;
        $this->is_edit = true;
    }

    public function render() {
        $materials = Material::search($this->search)->orderBy('id', 'desc')->paginate(10);
        return view('livewire.material.material-list', ['materials' => $materials])
            ->layout('layouts.admin'); // Nhớ thay bằng layout chuẩn của anh
    }
}