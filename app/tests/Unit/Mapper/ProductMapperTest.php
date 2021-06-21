<?php declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Mapper\ProductMapper;
use PHPUnit\Framework\TestCase;
use \SimpleXMLElement;

final class ProductMapperTest extends TestCase
{
    public function testTransformXmlToProduct(): void
    {
        $xmlElement = $this->createXmlElement();
        $productMapper = new ProductMapper();
        $dto = $productMapper->transformXmlToProduct($xmlElement);

        $this->assertSame($dto->entityId, "340");
        $this->assertSame($dto->categoryName, "Green Mountain Ground Coffee");
        $this->assertSame($dto->sku, "20");
        $this->assertSame($dto->name, "Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag");
        $this->assertSame($dto->description, "");
        $this->assertSame($dto->shortDesc, "\n                Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag steeps cup after cup of smoky-sweet, complex dark roast coffee from Green Mountain Ground Coffee.");
        $this->assertSame($dto->price, "41.6000");
        $this->assertSame($dto->link, "http://www.coffeeforless.com/green-mountain-coffee-french-roast-ground-coffee-24-2-2oz-bag.html");
        $this->assertSame($dto->image, "http://mcdn.coffeeforless.com/media/catalog/product/images/uploads/intro/frac_box.jpg");
        $this->assertSame($dto->brand, "Green Mountain Coffee");
        $this->assertSame($dto->rating, "0");
        $this->assertSame($dto->caffeineType, "Caffeinated");
        $this->assertSame($dto->count, "24");
        $this->assertSame($dto->flavored, "No");
        $this->assertSame($dto->seasonal, "No");
        $this->assertSame($dto->inStock, "Yes");
        $this->assertSame($dto->facebook, "1");
        $this->assertSame($dto->isKCup, "0");
    }

    public function testTransformDtoToArray(): void
    {
        $xmlElement = $this->createXmlElement();
        $productMapper = new ProductMapper();
        $dto = $productMapper->transformXmlToProduct($xmlElement);
        $transformedArray = $productMapper->transformDtoToArray([$dto]);
        $transformedFirstRow = $transformedArray[0];

        $this->assertSame($transformedFirstRow[0], "340");
        $this->assertSame($transformedFirstRow[1], "Green Mountain Ground Coffee");
        $this->assertSame($transformedFirstRow[2], "20");
        $this->assertSame($transformedFirstRow[3], "Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag");
        $this->assertSame($transformedFirstRow[4], "");
        $this->assertSame($transformedFirstRow[5], "\n                Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag steeps cup after cup of smoky-sweet, complex dark roast coffee from Green Mountain Ground Coffee.");
        $this->assertSame($transformedFirstRow[6], "41.6000");
        $this->assertSame($transformedFirstRow[7], "http://www.coffeeforless.com/green-mountain-coffee-french-roast-ground-coffee-24-2-2oz-bag.html");
        $this->assertSame($transformedFirstRow[8], "http://mcdn.coffeeforless.com/media/catalog/product/images/uploads/intro/frac_box.jpg");
        $this->assertSame($transformedFirstRow[9], "Green Mountain Coffee");
        $this->assertSame($transformedFirstRow[10], "0");
        $this->assertSame($transformedFirstRow[11], "Caffeinated");
        $this->assertSame($transformedFirstRow[12], "24");
        $this->assertSame($transformedFirstRow[13], "No");
        $this->assertSame($transformedFirstRow[14], "No");
        $this->assertSame($transformedFirstRow[15], "Yes");
        $this->assertSame($transformedFirstRow[16], "1");
        $this->assertSame($transformedFirstRow[17], "0");
    }

    private function createXmlElement(): SimpleXMLElement
    {
        $str = "
        <item>
            <entity_id>340</entity_id>
            <CategoryName><![CDATA[Green Mountain Ground Coffee]]></CategoryName>
            <sku>20</sku>
            <name><![CDATA[Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag]]></name>
            <description></description>
            <shortdesc>
                <![CDATA[Green Mountain Coffee French Roast Ground Coffee 24 2.2oz Bag steeps cup after cup of smoky-sweet, complex dark roast coffee from Green Mountain Ground Coffee.]]></shortdesc>
            <price>41.6000</price>
            <link>http://www.coffeeforless.com/green-mountain-coffee-french-roast-ground-coffee-24-2-2oz-bag.html</link>
            <image>http://mcdn.coffeeforless.com/media/catalog/product/images/uploads/intro/frac_box.jpg</image>
            <Brand><![CDATA[Green Mountain Coffee]]></Brand>
            <Rating>0</Rating>
            <CaffeineType>Caffeinated</CaffeineType>
            <Count>24</Count>
            <Flavored>No</Flavored>
            <Seasonal>No</Seasonal>
            <Instock>Yes</Instock>
            <Facebook>1</Facebook>
            <IsKCup>0</IsKCup>
        </item>";

        return new SimpleXMLElement($str);
    }
}
