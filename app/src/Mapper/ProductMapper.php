<?php

declare(strict_types=1);

namespace App\Mapper;

use App\DTO\ProductDTO;
use \SimpleXMLElement;

class ProductMapper
{
    public function transformXmlToProduct(SimpleXMLElement $xml): ProductDTO
    {
        $product = new ProductDTO();
        $product->entityId = $xml->entity_id->__toString();
        $product->categoryName = $xml->CategoryName->__toString();
        $product->sku = $xml->sku->__toString();
        $product->name = $xml->name->__toString();
        $product->description = $xml->description->__toString();
        $product->shortDesc = $xml->shortdesc->__toString();
        $product->price = $xml->price->__toString();
        $product->link = $xml->link->__toString();
        $product->image = $xml->image->__toString();
        $product->brand = $xml->Brand->__toString();
        $product->rating = $xml->Rating->__toString();
        $product->caffeineType = $xml->CaffeineType->__toString();
        $product->count = $xml->Count->__toString();
        $product->flavored = $xml->Flavored->__toString();
        $product->seasonal = $xml->Seasonal->__toString();
        $product->inStock = $xml->Instock->__toString();
        $product->facebook = $xml->Facebook->__toString();
        $product->isKCup = $xml->IsKCup->__toString();

        return $product;
    }

    /**
     * @param ProductDTO[] $products
     */
    public function transformDtoToArray(array $products): array
    {
        $rows = [];
        foreach ($products as $product) {
            $row = (array)($product);
            $row = array_values($row);
            $rows[] = $row;
        }

        return $rows;
    }
}
