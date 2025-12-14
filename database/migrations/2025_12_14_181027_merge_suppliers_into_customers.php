<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add columns to customers
        Schema::table('customers', function (Blueprint $table) {
            $table->boolean('is_supplier')->default(false)->after('type');
            $table->string('code')->nullable()->after('name'); // Mã NCC/Khách hàng
            $table->string('bank_account')->nullable()->after('tax_code');
            $table->string('bank_name')->nullable()->after('bank_account');
            $table->unsignedBigInteger('type_tag_id')->nullable()->after('is_supplier'); // Tag phân loại NCC
        });

        // 2. Drop Foreign Key on returned_items
        Schema::table('returned_items', function (Blueprint $table) {
            // Drop FK if exists.
            $table->dropForeign(['supplier_id']);
        });

        // 3. Migrate Data
        $suppliers = DB::table('suppliers')->get();
        foreach ($suppliers as $supplier) {
            // Create new Customer record for each Supplier.
            $customerId = DB::table('customers')->insertGetId([
                'name' => $supplier->name,
                'code' => $supplier->code,
                'email' => $supplier->email,
                'tax_code' => $supplier->tax_code,
                'representative_name' => $supplier->contact_name, // Map contact_name -> representative_name
                'notes' => $supplier->note,
                'is_supplier' => true,
                'type_tag_id' => $supplier->type_tag_id,
                'bank_account' => $supplier->bank_account,
                'bank_name' => $supplier->bank_name,
                'type' => 'company',
                'created_at' => $supplier->created_at,
                'updated_at' => $supplier->updated_at,
            ]);

            // Create Contacts (Phone/Address)
            if ($supplier->phone) {
                DB::table('customer_contacts')->insert([
                    'customer_id' => $customerId,
                    'type' => 'phone',
                    'value' => $supplier->phone,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            if ($supplier->address) {
                DB::table('customer_contacts')->insert([
                    'customer_id' => $customerId,
                    'type' => 'address',
                    'value' => $supplier->address,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Update ReturnedItems
            DB::table('returned_items')
                ->where('supplier_id', $supplier->id)
                ->update(['supplier_id' => $customerId]);
        }

        // 4. Drop Suppliers Table
        Schema::dropIfExists('suppliers');

        // 5. Add new Foreign Key to returned_items
        Schema::table('returned_items', function (Blueprint $table) {
            $table->foreign('supplier_id')->references('id')->on('customers')->nullOnDelete(); 
        });
    }

    public function down(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('type_tag_id')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('tax_code')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('note')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['is_supplier', 'code', 'bank_account', 'bank_name', 'type_tag_id']);
        });
    }
};
