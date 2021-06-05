<?php

namespace App\Exports;

use App\Category;
use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class productsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $productsData = Product::select('category_id', 'product_name', 'product_code', 'product_color', 'description', 'care', 'sleeve', 'pattern', 'price', 'weight', 'image', 'video', 'feature_item', 'status', 'created_at', 'updated_at')->get();
        foreach ($productsData as $key => $productData)
        {
            $categoryName = Category::select('name')->where('id', $productData->category_id)->first();
            $productsData[$key]->category_id = $categoryName->name;
        }

        return $productsData;

        // Return All Products

        return Product::all();
    }

    // Add Header to Excel

    public function headings(): array
    {
        return ['Category ID', 'Product Name', 'Product Code', 'Product Color', 'Description', 'Care', 'Sleeve', 'Pattern', 'Price', 'Weight', 'Image', 'Video', 'Featured Item', 'Status', 'Created At', 'Last Updated At'];
    }
}
