<?php

namespace App\Models\Admin;

use CodeIgniter\Model;


class Order_Items_Model extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'order_items';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [

        "id",
        "order_id",
        "product_variation_id",
        "qty",
        "location_id",
        "unit_price",
        "total_tax",
        "total_price",
        "reward_points",
        "is_refunded",
        "created_at",
        "updated_at",
        "tax_id",
        "discount",
        "discount_percentage",
        "tax_percentage",
        "unit_id"


    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    // public function salesorder()
    // {
    //     // $sql = "SELECT products.name as product_name, products.id as product_id, sum(order_items.qty) as sales FROM products,product_variations
    //     // LEFT JOIN order_items
    //     // ON order_items.product_variation_id = product_variations.id
    //     // where products.id = product_variations.product_id GROUP by products.id order by sales desc;";


    //     $sql = "SELECT products.name AS product_name, products.id AS product_id, COALESCE(SUM(order_items.qty), 0) AS sales FROM products LEFT JOIN product_variations
    //      ON products.id = product_variations.product_id LEFT JOIN order_items ON order_items.product_variation_id = product_variations.id 
    //      GROUP BY products.id ORDER BY sales DESC";


    //     return $this->query($sql)->getResultArray();
    // }



    public function salesorder()
{
    $sql = "SELECT 
                products.id AS product_id,
                products.name AS product_name,
                COALESCE(SUM(order_items.qty), 0) AS sales,
                products.created_at,
                media_managers.media_file AS thumbnail_image_url
            FROM 
                products
            LEFT JOIN 
                product_variations ON products.id = product_variations.product_id
            LEFT JOIN 
                order_items ON order_items.product_variation_id = product_variations.id 
            LEFT JOIN 
                media_managers ON products.thumbnail_image = media_managers.id
            GROUP BY 
                products.id
            ORDER BY 
                sales DESC";

    return $this->query($sql)->getResultArray();
}

    public function deleveryreport()
    {
        $sql = "SELECT delivery_status,COUNT(id) as total FROM `orders` GROUP BY delivery_status ORDER BY total DESC;";
        return $this->query($sql)->getResultArray();
    }
    public function getProductName($id)
    {

        $sql = "SELECT order_items.*, products.name as ProductName FROM order_items,product_variations, products WHERE order_items.product_variation_id = product_variations.product_id and product_variations.product_id = products.id and order_items.order_id=$id";
        return $this->query($sql)->getResultArray();
    }


    public function todaysales()
    {
        $sql = "SELECT
        products.id AS product_id,
        products.name AS product_name,
        SUM(order_items.qty) AS total_sales
    FROM
        products
    LEFT JOIN
        product_variations ON products.id = product_variations.product_id
    LEFT JOIN
        order_items ON product_variations.id = order_items.product_variation_id
    WHERE
        DATE(order_items.created_at) = CURDATE()
    GROUP BY
        products.id
    ORDER BY
        total_sales DESC
    LIMIT 1;";

        return $this->query($sql)->getResultArray();
    }

    public function lat7dayssales()
    {
        $sql = "SELECT
        products.id AS product_id,
        products.name AS product_name,
        SUM(order_items.qty) AS total_sales
    FROM
        products
    LEFT JOIN
        product_variations ON products.id = product_variations.product_id
    LEFT JOIN
        order_items ON product_variations.id = order_items.product_variation_id
    WHERE
        DATE(order_items.created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()
    GROUP BY
        products.id
    ORDER BY
        total_sales DESC
    LIMIT 1;";

        return $this->query($sql)->getResultArray();
    }

    public function lat30dayssales()
    {
        $sql = "SELECT
        products.id AS product_id,
        products.name AS product_name,
        SUM(order_items.qty) AS total_sales
    FROM
        products
    LEFT JOIN
        product_variations ON products.id = product_variations.product_id
    LEFT JOIN
        order_items ON product_variations.id = order_items.product_variation_id
    WHERE
        DATE(order_items.created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()
    GROUP BY
        products.id
    ORDER BY
        total_sales DESC
    LIMIT 1;";

        return $this->query($sql)->getResultArray();
    }

    public function thisyearsales()
    {
        $sql = "SELECT
        products.id AS product_id,
        products.name AS product_name,
        SUM(order_items.qty) AS total_sales
    FROM
        products
    LEFT JOIN
        product_variations ON products.id = product_variations.product_id
    LEFT JOIN
        order_items ON product_variations.id = order_items.product_variation_id
    WHERE
        YEAR(order_items.created_at) = YEAR(CURDATE())
    GROUP BY
        products.id
    ORDER BY
        total_sales DESC
    LIMIT 1;";

        return $this->query($sql)->getResultArray();
    }



    ////////highest selled product brand name and catogories
    public function TodayHighSaleBrandandCatagories()
    {
        $sql = "SELECT
        products.name AS product_name,
        brands.name AS brand_name,
        categories.name AS category_name,
        SUM(order_items.qty) AS total_sales
    FROM
        products
    LEFT JOIN
        product_variations ON products.id = product_variations.product_id
    LEFT JOIN
        order_items ON product_variations.id = order_items.product_variation_id
    LEFT JOIN
        brands ON products.brand_id = brands.id
    LEFT JOIN
        product_categories ON products.id = product_categories.product_id
    LEFT JOIN
        categories ON product_categories.category_id = categories.id
    WHERE
        DATE(order_items.created_at) = CURDATE()
    GROUP BY
        products.id
    ORDER BY
        total_sales DESC
    LIMIT 1;";

        return $this->query($sql)->getResultArray();
    }


    public function lat7daysHighSaleBrandandCatagories()
    {
        $sql = "SELECT
    products.name AS product_name,
    brands.name AS brand_name,
    categories.name AS category_name,
    SUM(order_items.qty) AS total_sales
FROM
    products
LEFT JOIN
    product_categories ON products.id = product_categories.product_id
LEFT JOIN
    categories ON product_categories.category_id = categories.id
LEFT JOIN
    brands ON products.brand_id = brands.id
LEFT JOIN
    product_variations ON products.id = product_variations.product_id
LEFT JOIN
    order_items ON product_variations.id = order_items.product_variation_id
WHERE
    DATE(order_items.created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()
GROUP BY
    products.id
ORDER BY
    total_sales DESC
LIMIT 1;
";

        return $this->query($sql)->getResultArray();
    }

    public function lat30daysHighSaleBrandandCatagories()
    {
        $sql = "SELECT
    products.name AS product_name,
    brands.name AS brand_name,
    categories.name AS category_name,
    SUM(order_items.qty) AS total_sales
FROM
    products
LEFT JOIN
    product_categories ON products.id = product_categories.product_id
LEFT JOIN
    categories ON product_categories.category_id = categories.id
LEFT JOIN
    brands ON products.brand_id = brands.id
LEFT JOIN
    product_variations ON products.id = product_variations.product_id
LEFT JOIN
    order_items ON product_variations.id = order_items.product_variation_id
WHERE
    DATE(order_items.created_at) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()
GROUP BY
    products.id
ORDER BY
    total_sales DESC
LIMIT 1;";

        return $this->query($sql)->getResultArray();
    }

    public function YearHighSaleBrandandCatagories()
    {
        $sql = "SELECT
    products.name AS product_name,
    brands.name AS brand_name,
    categories.name AS category_name,
    SUM(order_items.qty) AS total_sales
FROM
    products
LEFT JOIN
    product_categories ON products.id = product_categories.product_id
LEFT JOIN
    categories ON product_categories.category_id = categories.id
LEFT JOIN
    brands ON products.brand_id = brands.id
LEFT JOIN
    product_variations ON products.id = product_variations.product_id
LEFT JOIN
    order_items ON product_variations.id = order_items.product_variation_id
WHERE
    YEAR(order_items.created_at) = YEAR(CURDATE())
GROUP BY
    products.id
ORDER BY
    total_sales DESC
LIMIT 1;
";

        return $this->query($sql)->getResultArray();
    }
}