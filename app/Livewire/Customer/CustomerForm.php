<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;

class CustomerForm extends Component
{
    public $customer_id = null;
    
    // Các trường thông tin chính
    public $name;
    public $type = 'personal'; // Mặc định là cá nhân
    public $email;
    public $tax_code;
    public $representative_name;
    public $notes;
    public $classifications = ['customer']; // Mặc định là khách hàng

    // Danh sách liên hệ mở rộng (SĐT, Địa chỉ phụ...)
    public $contacts = []; 

    public function mount($id = null)
    {
        if ($id) {
            // EDIT MODE
            $this->customer_id = $id;
            $customer = Customer::with('contacts')->findOrFail($id);
            
            $this->name = $customer->name;
            $this->type = $customer->type;
            $this->email = $customer->email;
            $this->tax_code = $customer->tax_code;
            $this->representative_name = $customer->representative_name;
            $this->notes = $customer->notes;
            $this->classifications = $customer->classifications ?? ['customer'];
            
            foreach ($customer->contacts as $contact) {
                $this->contacts[] = [
                    'type' => $contact->type,
                    'value' => $contact->value,
                    'label' => $contact->label,
                    'is_primary' => $contact->is_primary
                ];
            }
        } else {
            // CREATE MODE: Thêm sẵn 1 dòng SĐT và 1 dòng Địa chỉ cho tiện
            $this->contacts[] = ['type' => 'phone', 'value' => '', 'label' => 'Di động', 'is_primary' => 1];
            $this->contacts[] = ['type' => 'address', 'value' => '', 'label' => 'Địa chỉ chính', 'is_primary' => 1];
        }
    }

    public function addContact($type)
    {
        $this->contacts[] = [
            'type' => $type,
            'value' => '',
            'label' => '',
            'is_primary' => 0
        ];
    }

    public function removeContact($index)
    {
        unset($this->contacts[$index]);
        $this->contacts = array_values($this->contacts);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|min:2',
            'email' => 'nullable|email',
            'contacts.*.value' => 'required',
            'classifications' => 'array',
        ], [
            'name.required' => 'Tên khách hàng là bắt buộc.',
            'email.email' => 'Email không đúng định dạng.',
            'contacts.*.value.required' => 'Không được để trống thông tin liên hệ.',
        ]);

        DB::transaction(function () {
            // 1. Lưu thông tin chính
            $customer = Customer::updateOrCreate(
                ['id' => $this->customer_id],
                [
                    'name' => $this->name,
                    'type' => $this->type,
                    'email' => $this->email,
                    'tax_code' => $this->tax_code,
                    'representative_name' => $this->representative_name,
                    'notes' => $this->notes,
                    'classifications' => $this->classifications,
                ]
            );

            // 2. Lưu danh sách liên hệ (Xóa cũ tạo mới)
            if ($this->customer_id) {
                CustomerContact::where('customer_id', $this->customer_id)->delete();
            }

            foreach ($this->contacts as $contact) {
                if (!empty($contact['value'])) {
                    CustomerContact::create([
                        'customer_id' => $customer->id,
                        'type' => $contact['type'],
                        'value' => $contact['value'],
                        'label' => $contact['label'] ?? null,
                        'is_primary' => $contact['is_primary'] ?? 0
                    ]);
                }
            }
        });

        session()->flash('success', $this->customer_id ? 'Cập nhật thành công!' : 'Thêm mới thành công!');
        return redirect()->route('admin.customers.index');
    }

    #[Layout('layouts.admin')]
    public function render()
    {
        return view('livewire.customer.customer-form');
    }
}