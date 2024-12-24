<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;
use App\Models\DocumentCategory;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Seed the application's document categories and subcategories.
     *
     * @return void
     */
    public function run()
    {
        // 1) Qonun xujjatlar
        $qonunCategory = DocumentCategory::create(['name' => 'Qonun xujjatlar']);
        $this->createSubcategories($qonunCategory, [
            'O‘zbekiston Respublikasining Konstitutsiyasi',
            'O‘zbekiston Respublikasining qonunlari',
            'O‘zbekiston Respublikasi Oliy Majlisi palatalarining qarorlari',
            'O‘zbekiston Respublikasi Prezidentining farmonlari va qarorlari',
            'O‘zbekiston Respublikasi Vazirlar Mahkamasining qarorlari',
            'Vazirliklar va idoralarning buyruqlari hamda qarorlari',
        ]);

        // 2) Yuqorida turuvchi tashkilotlar
        $yuqoridaCategory = DocumentCategory::create(['name' => 'Yuqorida turuvchi tashkilotlar']);
        $this->createSubcategories($yuqoridaCategory, [
            'Oliy Majlis',
            'Prezident Administratsiyasi',
            'Vazirlar Mahkamasi',
            'Vazirlar idoras + Vazirliklar ro’yxatii',
            'Shahar Hokimligi',
            
        ]);

        // 3) Vazirlik va Idoralar murojatlari
        $vazirlikCategory = DocumentCategory::create(['name' => 'Toshkent shahar tuman hokimliklari murojaatlari']);
        $this->createSubcategories($vazirlikCategory, [
            'Uchtepa Hokimligi', 
            'Bektemir Hokimligi',
            'Chilonzor Hokimligi',
            'Yashnobod Hokimligi',
            'Yakkasaroy Hokimligi',
            'Sergeli Hokimligi',
            'Yunusobod Hokimligi',
            'Olmazor Hokimligi',
            'Mirzo Ulug‘bek Hokimligi',
            'Shayxontohur Hokimligi',
            'Mirobod Hokimligi',
            'Yangihayot Hokimligi',
        ]);

        // 4) Kompaniya boshqaruv orgnlari topshiriqlari
        $kompaniyaCategory = DocumentCategory::create(['name' => 'Kompaniya boshqaruv organlari topshiriqlari']);
        $this->createSubcategories($kompaniyaCategory, [
            'Yagona Aksiyador (Shahar Hokimi)',
            'Kuzatuv Kengashi',
        ]);

        $kompaniyaCategory = DocumentCategory::create(['name' => 'Korxona va fuqorolar murojatlar']);
        $this->createSubcategories($kompaniyaCategory, [
            'Tadbirkorlar',
        ]);

        // $document = Document::create([
        //     'title' => '"IT SOLUTIONS" mas’uliyati cheklangan jamiyatning 2024-yil 23-dekabrdagi №42-sonli xati oʻrnatilgan tartibda koʻrib chiqilsin.',
        //     'xat' => 'исх № 42 от 23.12.2024',
        //     'qabul_sana' => '24.12.2024 11:18',
        //     'asosiy_categorya' => 'yuqorda turuvchi tashkilotlar',
        //     'qoshimcha catororya' => 'kuzatuv kengashi'
        // ]);

        // $product = [
        //     'Тошкент шаҳар кенгашидан лойиҳаси ўтмаганлик сабабли тўланган маблағни қайтариш.'
        //     muddati = 25 dekabr 
        //     iskandaraka, baxtyoraka
        // ]
    }

    /**
     * Create subcategories for a given parent category.
     *
     * @param  \App\Models\DocumentCategory  $parentCategory
     * @param  array  $subcategories
     * @return void
     */
    private function createSubcategories(DocumentCategory $parentCategory, array $subcategories)
    {
        foreach ($subcategories as $subcategory) {
            DocumentCategory::create([
                'name' => $subcategory,
                'parent_id' => $parentCategory->id,
            ]);
        }
    }
}
