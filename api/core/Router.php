<?php
class Router
{
    private $controllerName = "index";
    private $actionName = "index";
    private $routes = [
        'order' => 'OrderController@showOrders',
        'order/create' => 'OrderController@createOrder',
        'order/update' => 'OrderController@updateOrder',
        'order/([A-Z0-9]+)' => 'OrderController@showOrderDetails',
        'order/delete/(\d+)' => 'OrderController@deleteOrder',
        'order/invoice-update/(\d+)' => 'OrderController@invoiceUpdate',
        'customer/addresses' => 'CustomerController@showCustomerAddresses',
        'customer/(\d+)' => 'CustomerController@showCustomerAddresses',
        'product/create' => 'ProductController@createProduct',
        'product/update' => 'ProductController@updateProduct',
        'product/(\d+)' => 'ProductController@showProductDetails',
        'product/delete/(\d+)' => 'ProductController@deleteProduct',
        'product/upc/(\d+)' => 'ProductController@getProductByUPC',
        'load/create' => 'LoadController@createLoad',
        'load/update' => 'LoadController@updateLoad',
        'load/(\d+)' => 'LoadController@showLoadDetails',
        'load/delete/(\d+)' => 'LoadController@deleteLoad',
        'user/create' => 'UserController@createUser',
        'user/update' => 'UserController@updateUser',
        'user/(\d+)' => 'UserController@showUserDetails',
        'user/delete/(\d+)' => 'UserController@deleteUser',
        'customer/create' => 'CustomerController@createCustomer',
        'customer/update' => 'CustomerController@updateCustomer',
        'customer/(\d+)' => 'CustomerController@showCustomerDetails',
        'customer/delete/(\d+)' => 'CustomerController@deleteCustomer',
        'customer/import-customer' => 'CustomerController@importCustomer',
        'province/create' => 'ProvinceController@createProvince',
        'province/update' => 'ProvinceController@updateProvince',
        'province/(\d+)' => 'ProvinceController@showProvinceDetails',
        'province/delete/(\d+)' => 'ProvinceController@deleteProvince',
        'city/create' => 'CityController@createCity',
        'city/update' => 'CityController@updateCity',
        'city/(\d+)' => 'CityController@showCityDetails',
        'city/delete/(\d+)' => 'CityController@deleteCity',
        'unit/create' => 'UnitController@createUnit',
        'unit/update' => 'UnitController@updateUnit',
        'unit/(\d+)' => 'UnitController@showUnitDetails',
        'unit/delete/(\d+)' => 'UnitController@deleteUnit',
        'address-type/create' => 'AddressTypeController@createAddressType',
        'address-type/update' => 'AddressTypeController@updateAddressType',
        'address-type/(\d+)' => 'AddressTypeController@showAddressTypeDetails',
        'address-type/delete/(\d+)' => 'AddressTypeController@deleteAddressType',
        'shipvia/create' => 'ShipviaController@createShipvia',
        'shipvia/update' => 'ShipviaController@updateShipvia',
        'shipvia/(\d+)' => 'ShipviaController@showShipviaDetails',
        'shipvia/delete/(\d+)' => 'ShipviaController@deleteShipvia',
        'contact-method/create' => 'ContactMethodController@createContactMethod',
        'contact-method/update' => 'ContactMethodController@updateContactMethod',
        'contact-method/(\d+)' => 'ContactMethodController@showContactMethodDetails',
        'contact-method/delete/(\d+)' => 'ContactMethodController@deleteContactMethod',
        'contact-type/create' => 'ContactTypeController@createContactType',
        'contact-type/update' => 'ContactTypeController@updateContactType',
        'contact-type/(\d+)' => 'ContactTypeController@showContactTypeDetails',
        'contact-type/delete/(\d+)' => 'ContactTypeController@deleteContactType',
        'product-category/create' => 'ProductCategoryController@createProductCategory',
        'product-category/update' => 'ProductCategoryController@updateProductCategory',
        'product-category/(\d+)' => 'ProductCategoryController@showProductCategoryDetails',
        'product-category/delete/(\d+)' => 'ProductCategoryController@deleteProductCategory',
        'product-sub-category/create' => 'ProductSubCategoryController@createProductSubCategory',
        'product-sub-category/update' => 'ProductSubCategoryController@updateProductSubCategory',
        'product-sub-category/(\d+)' => 'ProductSubCategoryController@showProductSubCategoryDetails',
        'product-sub-category/delete/(\d+)' => 'ProductSubCategoryController@deleteProductSubCategory',
        'product-sub-category/get-category' => 'ProductSubCategoryController@getProductSubCategory'
    ];

    public function route($url)
    {
        $url = $this->getControllerAndAction($url);
        // var_dump($url);
        foreach ($this->routes as $routePattern => $controllerAction) {
            // var_dump(preg_match('#^' . $routePattern . '$#', $url, $matches));
            // var_dump('#^' . $routePattern . '$#');
            // var_dump($matches);
            if (preg_match('#^' . $routePattern . '$#', $url, $matches)) {
                $parts = explode('@', $controllerAction);
                $mkey = array_search($url, $matches);
                if ($mkey !== false) {
                    unset($matches[$mkey]);
                }
                // var_dump($parts);
                // var_dump($matches);
                // var_dump($url);

                if (!empty($parts)) {
                    $this->controllerName = $parts[0];
                    $this->actionName = $parts[1];
                }

                include_once(APP_PATH . "/controllers/{$this->controllerName}.php");
                $controller = new $this->controllerName();

                // Pass the captured parameters to the action
                $actionName = $this->actionName;
                $controller->$actionName(...$matches);
                return;
            }
        }

        // Handle 404 Not Found
        $response = new Response();
        $response->setStatus(false);
        $response->setCode(404);
        $response->setData([]);
        $response->setShiftArray(false);
        echo $response->getResponseData();
        die();
    }

    private function getControllerAndAction($url)
    {
        $baseUrl = Helper::baseUrl();
        $baseUrl = str_replace("public/", "", $baseUrl);
        $url = str_replace($baseUrl, "", $url);
        // var_dump($url);

        // Parse the URL
        $parsedUrl = parse_url($url);
        // var_dump($parsedUrl);

        return (isset($parsedUrl["path"]) && !empty($parsedUrl["path"])) ? $parsedUrl["path"] : $url;
    }

    public function getControllerName()
    {
        return $this->controllerName;
    }

    public function getActionName()
    {
        return $this->actionName;
    }

    public function setControllerName($controllerName)
    {
        return $this->controllerName = $controllerName;
    }

    public function setActionName($actionName)
    {
        return $this->actionName = $actionName;
    }
}
