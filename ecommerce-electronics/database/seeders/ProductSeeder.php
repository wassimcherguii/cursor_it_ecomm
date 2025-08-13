<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $products = [
            // Computers & Laptops
            [
                'name' => 'MacBook Pro 16-inch',
                'slug' => 'macbook-pro-16-inch',
                'description' => 'Apple MacBook Pro with M2 Pro chip, 16-inch Liquid Retina XDR display, 16GB RAM, 512GB SSD. Perfect for professional work and creative tasks.',
                'short_description' => 'Powerful MacBook Pro with M2 Pro chip and 16-inch display',
                'price' => 2499.00,
                'sale_price' => 2299.00,
                'sku' => 'MBP-16-M2-512',
                'stock_quantity' => 25,
                'brand' => 'Apple',
                'weight' => 2.15,
                'dimensions' => '35.57 x 24.81 x 1.68 cm',
                'specifications' => [
                    'Processor' => 'Apple M2 Pro chip',
                    'Memory' => '16GB unified memory',
                    'Storage' => '512GB SSD',
                    'Display' => '16-inch Liquid Retina XDR',
                    'Graphics' => 'Integrated GPU',
                    'Connectivity' => 'Wi-Fi 6E, Bluetooth 5.3'
                ],
                'images' => ['/images/products/macbook-pro-16.jpg'],
                'category_id' => $categories['computers-laptops']->id,
            ],
            [
                'name' => 'Dell XPS 13',
                'slug' => 'dell-xps-13',
                'description' => 'Dell XPS 13 laptop with Intel Core i7 processor, 13.4-inch InfinityEdge display, 16GB RAM, 1TB SSD. Ultra-portable design with premium build quality.',
                'short_description' => 'Premium ultrabook with Intel Core i7 and stunning display',
                'price' => 1699.00,
                'sku' => 'DELL-XPS13-I7',
                'stock_quantity' => 18,
                'brand' => 'Dell',
                'weight' => 1.27,
                'dimensions' => '29.58 x 19.85 x 1.48 cm',
                'specifications' => [
                    'Processor' => 'Intel Core i7-1360P',
                    'Memory' => '16GB LPDDR5',
                    'Storage' => '1TB PCIe SSD',
                    'Display' => '13.4-inch FHD+ InfinityEdge',
                    'Graphics' => 'Intel Iris Xe',
                    'Connectivity' => 'Wi-Fi 6E, Bluetooth 5.2'
                ],
                'images' => ['/images/products/dell-xps-13.jpg'],
                'category_id' => $categories['computers-laptops']->id,
            ],

            // Smartphones & Tablets
            [
                'name' => 'iPhone 15 Pro',
                'slug' => 'iphone-15-pro',
                'description' => 'Apple iPhone 15 Pro with A17 Pro chip, 6.1-inch Super Retina XDR display, Pro camera system, and titanium design.',
                'short_description' => 'Latest iPhone with titanium design and Pro camera system',
                'price' => 999.00,
                'sale_price' => 949.00,
                'sku' => 'IP15-PRO-128',
                'stock_quantity' => 45,
                'brand' => 'Apple',
                'weight' => 0.187,
                'dimensions' => '14.67 x 7.08 x 0.83 cm',
                'specifications' => [
                    'Processor' => 'A17 Pro chip',
                    'Storage' => '128GB',
                    'Display' => '6.1-inch Super Retina XDR',
                    'Camera' => 'Pro camera system',
                    'Connectivity' => '5G, Wi-Fi 6E, Bluetooth 5.3'
                ],
                'images' => ['/images/products/iphone-15-pro.jpg'],
                'category_id' => $categories['smartphones-tablets']->id,
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'slug' => 'samsung-galaxy-s24-ultra',
                'description' => 'Samsung Galaxy S24 Ultra with Snapdragon 8 Gen 3, 6.8-inch Dynamic AMOLED display, S Pen, and advanced camera system.',
                'short_description' => 'Premium Android flagship with S Pen and advanced cameras',
                'price' => 1199.00,
                'sku' => 'SGS24-ULTRA-256',
                'stock_quantity' => 32,
                'brand' => 'Samsung',
                'weight' => 0.232,
                'dimensions' => '16.27 x 7.9 x 0.86 cm',
                'specifications' => [
                    'Processor' => 'Snapdragon 8 Gen 3',
                    'Memory' => '12GB RAM',
                    'Storage' => '256GB',
                    'Display' => '6.8-inch Dynamic AMOLED 2X',
                    'Camera' => '200MP main camera',
                    'Connectivity' => '5G, Wi-Fi 7, Bluetooth 5.3'
                ],
                'images' => ['/images/products/galaxy-s24-ultra.jpg'],
                'category_id' => $categories['smartphones-tablets']->id,
            ],

            // Computer Components
            [
                'name' => 'NVIDIA RTX 4080 Graphics Card',
                'slug' => 'nvidia-rtx-4080',
                'description' => 'NVIDIA GeForce RTX 4080 graphics card with 16GB GDDR6X memory, Ray tracing, and DLSS 3 technology for ultimate gaming performance.',
                'short_description' => 'High-end graphics card with ray tracing and DLSS 3',
                'price' => 1199.00,
                'sale_price' => 1099.00,
                'sku' => 'RTX-4080-16GB',
                'stock_quantity' => 15,
                'brand' => 'NVIDIA',
                'weight' => 2.2,
                'dimensions' => '30.4 x 13.7 x 6.1 cm',
                'specifications' => [
                    'GPU' => 'NVIDIA GeForce RTX 4080',
                    'Memory' => '16GB GDDR6X',
                    'Memory Interface' => '256-bit',
                    'Base Clock' => '2205 MHz',
                    'Boost Clock' => '2505 MHz',
                    'Power Consumption' => '320W'
                ],
                'images' => ['/images/products/rtx-4080.jpg'],
                'category_id' => $categories['computer-components']->id,
            ],

            // Accessories
            [
                'name' => 'Logitech MX Master 3S',
                'slug' => 'logitech-mx-master-3s',
                'description' => 'Logitech MX Master 3S wireless mouse with precision tracking, customizable buttons, and multi-device connectivity.',
                'short_description' => 'Premium wireless mouse with precision tracking',
                'price' => 99.99,
                'sku' => 'LGT-MX3S-BLK',
                'stock_quantity' => 67,
                'brand' => 'Logitech',
                'weight' => 0.141,
                'dimensions' => '12.4 x 8.4 x 5.1 cm',
                'specifications' => [
                    'Sensor' => 'Darkfield high precision',
                    'DPI' => '200-8000 DPI',
                    'Buttons' => '7 customizable buttons',
                    'Battery' => '70 days on full charge',
                    'Connectivity' => 'Bluetooth, USB-C receiver'
                ],
                'images' => ['/images/products/mx-master-3s.jpg'],
                'category_id' => $categories['accessories']->id,
            ],
            [
                'name' => 'Sony WH-1000XM5 Headphones',
                'slug' => 'sony-wh-1000xm5',
                'description' => 'Sony WH-1000XM5 wireless noise-canceling headphones with industry-leading noise cancellation and 30-hour battery life.',
                'short_description' => 'Premium noise-canceling wireless headphones',
                'price' => 399.99,
                'sale_price' => 349.99,
                'sku' => 'SONY-WH1000XM5',
                'stock_quantity' => 41,
                'brand' => 'Sony',
                'weight' => 0.25,
                'dimensions' => '26.4 x 19.5 x 8.0 cm',
                'specifications' => [
                    'Driver' => '30mm dome type',
                    'Frequency Response' => '4Hz-40kHz',
                    'Battery Life' => '30 hours',
                    'Noise Cancellation' => 'Industry-leading ANC',
                    'Connectivity' => 'Bluetooth 5.2, NFC'
                ],
                'images' => ['/images/products/sony-wh1000xm5.jpg'],
                'category_id' => $categories['accessories']->id,
            ],

            // Cables & Adapters
            [
                'name' => 'USB-C to USB-C Cable 2m',
                'slug' => 'usb-c-to-usb-c-2m',
                'description' => 'High-quality USB-C to USB-C cable, 2 meters long, supports fast charging up to 100W and data transfer up to 10Gbps.',
                'short_description' => '2m USB-C cable with fast charging and data transfer',
                'price' => 24.99,
                'sku' => 'USBC-USBC-2M',
                'stock_quantity' => 156,
                'brand' => 'TechCables',
                'weight' => 0.15,
                'dimensions' => '200cm cable length',
                'specifications' => [
                    'Length' => '2 meters',
                    'Power Delivery' => 'Up to 100W',
                    'Data Transfer' => 'Up to 10Gbps',
                    'Compatibility' => 'USB-C devices',
                    'Material' => 'Braided nylon'
                ],
                'images' => ['/images/products/usb-c-cable.jpg'],
                'category_id' => $categories['cables-adapters']->id,
            ],

            // Gaming
            [
                'name' => 'PlayStation 5',
                'slug' => 'playstation-5',
                'description' => 'Sony PlayStation 5 gaming console with ultra-high speed SSD, ray tracing, and immersive 3D audio. Includes DualSense wireless controller.',
                'short_description' => 'Next-gen gaming console with ultra-fast SSD',
                'price' => 499.99,
                'sku' => 'PS5-CONSOLE',
                'stock_quantity' => 8,
                'brand' => 'Sony',
                'weight' => 4.5,
                'dimensions' => '39.0 x 26.0 x 10.4 cm',
                'specifications' => [
                    'CPU' => 'Custom 8-core AMD Zen 2',
                    'GPU' => 'Custom AMD RDNA 2',
                    'Memory' => '16GB GDDR6',
                    'Storage' => '825GB Custom SSD',
                    'Optical Drive' => '4K UHD Blu-ray',
                    'Audio' => 'Tempest 3D AudioTech'
                ],
                'images' => ['/images/products/playstation-5.jpg'],
                'category_id' => $categories['gaming']->id,
            ],

            // Storage
            [
                'name' => 'Samsung 980 PRO 2TB SSD',
                'slug' => 'samsung-980-pro-2tb',
                'description' => 'Samsung 980 PRO NVMe M.2 SSD with 2TB capacity, PCIe 4.0 interface, and read speeds up to 7,000 MB/s.',
                'short_description' => 'High-performance NVMe SSD with 2TB capacity',
                'price' => 199.99,
                'sku' => 'SAM-980PRO-2TB',
                'stock_quantity' => 73,
                'brand' => 'Samsung',
                'weight' => 0.008,
                'dimensions' => '8.0 x 2.2 x 0.15 cm',
                'specifications' => [
                    'Capacity' => '2TB',
                    'Interface' => 'PCIe 4.0 NVMe M.2',
                    'Sequential Read' => 'Up to 7,000 MB/s',
                    'Sequential Write' => 'Up to 6,900 MB/s',
                    'Form Factor' => 'M.2 2280',
                    'Warranty' => '5 years'
                ],
                'images' => ['/images/products/samsung-980-pro.jpg'],
                'category_id' => $categories['storage']->id,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
