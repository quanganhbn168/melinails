<?php

namespace Database\Seeders;

use App\Models\BeautyStaff;
use App\Models\Branch;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Support\SlugGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class MelinailsSeeder extends Seeder
{
    public function run(): void
    {
        $slugger = app(SlugGenerator::class);

        $this->resetMelinailsDomain();

        $categories = collect([
            ['key' => 'massage', 'name' => 'Masáže', 'description' => 'Relaxace, regenerace a péče o tělo.', 'position' => 10],
            ['key' => 'cosmetics', 'name' => 'Kosmetika', 'description' => 'Ošetření pleti, masky, séra a jemná masáž obličeje.', 'position' => 20],
            ['key' => 'depilation', 'name' => 'Depilace', 'description' => 'Šetrná depilace obličeje i těla.', 'position' => 30],
            ['key' => 'manicure', 'name' => 'Manikúra', 'description' => 'Klasická manikúra, gel lak a péče o ruce.', 'position' => 40],
            ['key' => 'pedicure', 'name' => 'Pedikúra', 'description' => 'Péče o chodidla, lakování a relaxační masáž nohou.', 'position' => 50],
            ['key' => 'nailart', 'name' => 'Zdobení nehtů', 'description' => 'Nail art, chrome, třpytky a individuální design.', 'position' => 60],
            ['key' => 'modelage', 'name' => 'Modeláž nehtů', 'description' => 'Gelové a akrylové nehty, nové sety i doplnění.', 'position' => 70],
            ['key' => 'browslashes', 'name' => 'Obočí & řasy', 'description' => 'Lash lifting, brow lifting, barvení a prodlužování řas.', 'position' => 80],
            ['key' => 'voucher', 'name' => 'Poukazy', 'description' => 'Dárkové poukazy na služby nebo hodnotu.', 'position' => 90],
        ])->mapWithKeys(function (array $data) use ($slugger) {
            $category = ServiceCategory::updateOrCreate(
                ['name' => $data['name']],
                [
                    'description' => $data['description'],
                    'content' => $data['description'],
                    'position' => $data['position'],
                    'parent_id' => 0,
                    'status' => true,
                    'is_home' => true,
                    'is_menu' => true,
                    'is_footer' => true,
                ]
            );
            $slugger->syncModel($category, $category->name);

            return [$data['key'] => $category];
        });

        $serviceRows = [
            ['massage-body-60', 'massage', 'Masáž těla 60 min', 'Tradiční vietnamská nebo relaxační masáž těla.', 850, 60],
            ['massage-body-90', 'massage', 'Masáž těla 90 min', 'Delší masáž pro hlubší uvolnění a regeneraci.', 1200, 90],
            ['massage-special-90', 'massage', 'Meli Special masáž 90 min', 'Speciální kombinovaná masáž pro maximální odpočinek.', 1450, 90],
            ['massage-back-neck-30', 'massage', 'Masáž zad & šíje 30 min', 'Cílená péče na záda, krk a šíji.', 650, 30],
            ['massage-feet-60', 'massage', 'Masáž nohou 60 min', 'Kompletní péče pro unavené nohy.', 850, 60],
            ['massage-couple-60', 'massage', 'Párová masáž 60 min', 'Společná relaxace ve dvou podle výběru typu masáže.', 1700, 60],
            ['cos-basic', 'cosmetics', 'Základní kosmetické ošetření 45 min', 'Očištění pleti, peeling, masáž obličeje, maska, sérum a krém.', 550, 45],
            ['cos-special', 'cosmetics', 'Speciální kosmetické ošetření 60 min', 'Hloubková péče včetně ultrazvukové špachtle, masky a séra.', 850, 60],
            ['cos-detox', 'cosmetics', 'Detoxikační ošetření 60 min', 'Detox péče s maskou, masáží, acid sérem a zklidňujícím krémem.', 900, 60],
            ['dep-face', 'depilation', 'Depilace obličeje 45 min', 'Komplexní depilace obličejových partií.', 600, 45],
            ['dep-brow', 'depilation', 'Depilace / úprava obočí 15 min', 'Rychlá úprava obočí depilací.', 180, 15],
            ['dep-lip', 'depilation', 'Depilace horního rtu 15 min', 'Jemná depilace horního rtu.', 170, 15],
            ['dep-armpit', 'depilation', 'Depilace podpaží 15 min', 'Rychlá depilace podpaží.', 400, 15],
            ['dep-back', 'depilation', 'Depilace zad 60-90 min', 'Depilace zad podle rozsahu.', 680, 75],
            ['mani-woman', 'manicure', 'Manikúra klasik dámská', 'Klasická úprava nehtů a kůžičky.', 200, 30],
            ['mani-man', 'manicure', 'Manikúra klasik pánská', 'Klasická pánská manikúra.', 200, 30],
            ['mani-gel-lak', 'manicure', 'Manikúra & Gel lak', 'Manikúra s aplikací gel laku.', 380, 60],
            ['mani-removal', 'manicure', 'Odstranění nehtů', 'Šetrné odstranění staré modeláže nebo gel laku.', 200, 30],
            ['pedi-woman', 'pedicure', 'Pedikúra klasik dámská', 'Klasická dámská pedikúra.', 420, 60],
            ['pedi-man', 'pedicure', 'Pedikúra klasik pánská', 'Klasická pánská pedikúra.', 450, 60],
            ['pedi-gel-lak', 'pedicure', 'Pedikúra & Gel lak', 'Pedikúra s aplikací gel laku.', 500, 75],
            ['pedi-feet-massage', 'pedicure', 'Masáž nohou 20 min', 'Krátká relaxační masáž nohou.', 290, 20],
            ['art-handpaint', 'nailart', 'Ruční malování podle složitosti', 'Individuální ruční nail art podle náročnosti.', 10, 15, '10-250 Kč'],
            ['art-one-nail', 'nailart', 'Třpytky nebo jiná barva na 1 nehet', 'Zdobení jednoho nehtu.', 10, 5],
            ['art-chrome-all', 'nailart', 'Chrome na všechny nehty', 'Chrome efekt na všechny nehty.', 100, 15],
            ['art-cat-eye', 'nailart', 'Cat Eye na všechny nehty', 'Magnetický Cat Eye efekt.', 50, 10],
            ['gel-new-color', 'modelage', 'Nové gelové nehty s lakováním', 'Nová gelová modeláž s barevným lakováním.', 520, 90],
            ['gel-new-french', 'modelage', 'Nové gelové nehty s francií', 'Nová gelová modeláž s francouzskou manikúrou.', 550, 100],
            ['acryl-new-color', 'modelage', 'Nové akrylové nehty s lakováním', 'Nová akrylová modeláž s barevným lakováním.', 500, 90],
            ['gel-fill-color', 'modelage', 'Doplnění gelových nehtů s lakováním', 'Doplnění gelové modeláže s lakováním.', 490, 80],
            ['acryl-fill-color', 'modelage', 'Doplnění akrylových nehtů s lakováním', 'Doplnění akrylové modeláže s lakováním.', 480, 80],
            ['brow-color', 'browslashes', 'Barvení obočí', 'Barvení obočí pro zvýraznění výrazu.', 100, 15],
            ['lash-color', 'browslashes', 'Barvení řas', 'Barvení řas.', 100, 15],
            ['brow-shape-color', 'browslashes', 'Úprava & barvení obočí', 'Kompletní úprava a barvení obočí.', 150, 25],
            ['lash-lifting', 'browslashes', 'Lash lifting + barvení řas', 'Lash lifting s barvením řas.', 500, 60],
            ['brow-lifting', 'browslashes', 'Brow lifting / laminace obočí', 'Laminace obočí pro upravený a plnější vzhled.', 500, 60],
            ['lash-11-new', 'browslashes', 'Prodlužování řas řasa na řasu - nový set', 'Nový set metodou řasa na řasu.', 750, 100],
            ['lash-volume-new', 'browslashes', 'Volume řasy 3D-5D - nový set', 'Nový set objemových řas.', 900, 120],
            ['lash-mega-new', 'browslashes', 'Mega Volume 6D-7D - nový set', 'Nový set Mega Volume řas.', 1000, 130],
            ['voucher-custom', 'voucher', 'Dárkový poukaz na vybranou hodnotu', 'Poukaz na libovolnou částku nebo konkrétní proceduru.', 0, 10, 'dle hodnoty'],
        ];

        $services = collect($serviceRows)->mapWithKeys(function (array $row, int $index) use ($categories, $slugger) {
            [$code, $categoryKey, $name, $description, $price, $duration] = $row;
            $service = Service::updateOrCreate(
                ['code' => $code],
                [
                    'service_category_id' => $categories[$categoryKey]->id,
                    'name' => $name,
                    'description' => $description,
                    'content' => $description,
                    'price' => $price,
                    'duration_minutes' => $duration,
                    'sort_order' => ($index + 1) * 10,
                    'status' => true,
                    'is_home' => $index < 12,
                    'is_menu' => true,
                    'is_footer' => false,
                ]
            );
            $slugger->syncModel($service, $service->name);

            return [$code => $service];
        });

        $branches = collect([
            'uherske-hradiste' => [
                'name' => 'Meli Nails & Beauty Uherské Hradiště',
                'city' => 'Uherské Hradiště',
                'address' => 'Mlýnská 1295, Uherské Hradiště, 686 01',
                'phone' => '+420 777 768 681',
                'email' => 'info@melinails.cz',
                'map_url' => 'https://www.google.com/maps?q=Ml%C3%BDnsk%C3%A1%201295%2C%20Uhersk%C3%A9%20Hradi%C5%A1t%C4%9B%20686%2001&output=embed',
            ],
            'zlin' => [
                'name' => 'Meli Nails & Beauty Zlín',
                'city' => 'Zlín',
                'address' => 'Rašínova 522, Zlín, 760 01',
                'phone' => '+420 777 768 626',
                'email' => 'zlin@melinails.cz',
                'map_url' => 'https://www.google.com/maps?q=Ra%C5%A1%C3%ADnova%20522%2C%20Zl%C3%ADn%20760%2001&output=embed',
            ],
        ])->mapWithKeys(function (array $data, string $slug) {
            $branch = Branch::updateOrCreate(
                ['phone' => $data['phone']],
                $data + [
                    'slug' => $slug,
                    'timezone' => 'Asia/Ho_Chi_Minh',
                    'opening_time' => '08:00:00',
                    'closing_time' => '20:00:00',
                    'status' => true,
                ]
            );

            return [$slug => $branch];
        });

        $branchServiceRules = [
            'uherske-hradiste' => [
                'exclude' => ['lash-mega-new'],
                'price_delta' => 0,
            ],
            'zlin' => [
                'exclude' => ['massage-couple-60', 'dep-back', 'acryl-new-color', 'acryl-fill-color'],
                'price_delta' => 40,
                'custom' => [
                    'mani-gel-lak' => ['price' => 420, 'duration' => 60],
                    'pedi-gel-lak' => ['price' => 540, 'duration' => 75],
                    'lash-volume-new' => ['price' => 950, 'duration' => 120],
                ],
            ],
        ];

        foreach ($branches as $branchKey => $branch) {
            foreach ($services as $code => $service) {
                if (in_array($code, $branchServiceRules[$branchKey]['exclude'] ?? [], true)) {
                    continue;
                }

                $custom = $branchServiceRules[$branchKey]['custom'][$code] ?? [];
                $price = $custom['price'] ?? max(0, (int) $service->price + ($branchServiceRules[$branchKey]['price_delta'] ?? 0));
                $duration = $custom['duration'] ?? $service->duration_minutes;
                $priceText = $serviceRows[array_search($code, array_column($serviceRows, 0), true)][6] ?? ($price > 0 ? number_format($price, 0, ',', ' ') . ' Kč' : 'dle hodnoty');

                $branch->services()->syncWithoutDetaching([
                    $service->id => [
                        'price' => $price,
                        'price_text' => $priceText,
                        'duration_minutes' => $duration,
                        'is_available' => true,
                    ],
                ]);
            }
        }

        $staffRows = [
            ['branch' => 'uherske-hradiste', 'name' => 'Ngoc Tran', 'role' => 'Nails specialistka', 'groups' => ['manicure', 'pedicure', 'nailart', 'modelage']],
            ['branch' => 'uherske-hradiste', 'name' => 'Linh Nguyen', 'role' => 'Beauty specialistka', 'groups' => ['cosmetics', 'depilation', 'browslashes']],
            ['branch' => 'uherske-hradiste', 'name' => 'Mai Pham', 'role' => 'Masérka', 'groups' => ['massage']],
            ['branch' => 'zlin', 'name' => 'Hoa Le', 'role' => 'Nails specialistka', 'groups' => ['manicure', 'pedicure', 'nailart', 'modelage']],
            ['branch' => 'zlin', 'name' => 'Aneta Novak', 'role' => 'Beauty specialistka', 'groups' => ['cosmetics', 'depilation', 'browslashes']],
            ['branch' => 'zlin', 'name' => 'Thao Vu', 'role' => 'Masérka', 'groups' => ['massage']],
        ];

        foreach ($staffRows as $row) {
            $branch = $branches[$row['branch']];
            $person = BeautyStaff::updateOrCreate(
                ['slug' => Str::slug($branch->city . '-' . $row['name'])],
                [
                    'branch_id' => $branch->id,
                    'name' => $row['name'],
                    'role' => $row['role'],
                    'working_days' => ['mon', 'tue', 'wed', 'thu', 'fri', 'sat'],
                    'status' => true,
                ]
            );

            $allowedCategoryIds = collect($row['groups'])
                ->map(fn (string $key) => $categories[$key]?->id)
                ->filter()
                ->all();

            $serviceIds = $services
                ->filter(fn (Service $service) => in_array($service->service_category_id, $allowedCategoryIds, true))
                ->filter(fn (Service $service) => $branch->services()->whereKey($service->id)->exists())
                ->pluck('id')
                ->all();

            if (empty($serviceIds)) {
                $serviceIds = $services
                    ->filter(fn (Service $service) => in_array($service->service_category_id, $allowedCategoryIds, true))
                    ->pluck('id')
                    ->all();
            }

            $person->services()->sync($serviceIds);
        }
    }

    protected function resetMelinailsDomain(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            foreach ([
                'melinails_appointment_segments',
                'melinails_appointments',
                'beauty_staff_service',
                'branch_service',
                'branch_service_category',
                'beauty_staff',
                'branches',
                'services',
                'service_categories',
            ] as $table) {
                if (Schema::hasTable($table)) {
                    DB::table($table)->truncate();
                }
            }

            if (Schema::hasTable('slugs')) {
                DB::table('slugs')
                    ->whereIn('sluggable_type', [
                        Service::class,
                        ServiceCategory::class,
                    ])
                    ->delete();
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
