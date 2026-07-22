<?php

namespace App\Imports;

use Modules\Common\Entities\Product;
use Modules\Common\Entities\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ProductsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected int $rowCount = 0;
    protected array $missingImages = [];

    // Folder where images were uploaded beforehand
    protected string $sourceFolder = 'imports/temp';

    public function model(array $row)
    {
        $imagePath = null;

        if (!empty($row['image'])) {
            $imagePath = $this->moveImage(trim($row['image']));
        }

        $product = Product::create([
            'name'        => $row['name'],
            'subtitle'    => $row['subtitle'] ?? null,
            'description' => $row['description'] ?? null,
            'price'       => $row['price'],
            'base'        => $row['base'] ?? null,
            'style'       => $row['style'] ?? null,
            'served'      => $row['served'] ?? null,
            'status'      => $row['status'] ?? 'active',
            'image'       => $imagePath,
        ]);

        if (!empty($row['category'])) {
            $category = Category::firstOrCreate(['name' => trim($row['category'])]);
            $product->categories()->attach($category->id);
        }

        $this->rowCount++;

        return $product;
    }

    protected function moveImage(string $filename): ?string
    {
        $sourcePath = $this->sourceFolder . '/' . $filename;

        if (!Storage::disk('public')->exists($sourcePath)) {
            $this->missingImages[] = $filename;
            return null;
        }

        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $newFilename = Str::random(20) . '.' . $extension;
        $destPath = 'products/' . $newFilename;

        Storage::disk('public')->copy($sourcePath, $destPath);

        return $destPath;
    }

    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    public function getMissingImages(): array
    {
        return $this->missingImages;
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ];
    }
}
