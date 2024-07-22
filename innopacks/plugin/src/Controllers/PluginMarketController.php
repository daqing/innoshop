<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Plugin\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use InnoShop\Plugin\Services\MarketplaceService;
use InnoShop\Plugin\Services\PaginatorService;

class PluginMarketController
{
    /**
     * Get plugins market categories and items.
     *
     * @param  Request  $request
     * @param  PaginatorService  $paginatorService
     * @return mixed
     * @throws ConnectionException
     */
    public function index(Request $request, PaginatorService $paginatorService): mixed
    {
        $categorySlug  = $request->get('category');
        $marketService = MarketplaceService::getInstance()
            ->setPage($request->get('page', 1))
            ->setPerPage($request->get('per_page', 15));

        if ($categorySlug) {
            $products = $marketService->getMarketProducts($categorySlug);
        } else {
            $products = $marketService->getPluginProducts();
        }

        $paginator = $paginatorService->makePaginator($products['data']);

        $data = [
            'categories' => $marketService->getPluginCategories(),
            'products'   => $paginator,
        ];

        return inno_view('plugin::plugin_market.index', $data);
    }

    /**
     * @param  int  $slug
     * @return mixed
     * @throws ConnectionException
     */
    public function show(int $slug): mixed
    {
        $marketService = MarketplaceService::getInstance();
        $data          = [
            'categories' => $marketService->getPluginCategories(),
            'product'    => $marketService->getProductDetail($slug),
        ];

        return inno_view('plugin::plugin_market.detail', $data);
    }
}
