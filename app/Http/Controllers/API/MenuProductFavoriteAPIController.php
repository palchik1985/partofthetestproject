<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateProductFavoriteAPIRequest;
use App\Http\Requests\API\SearchProductFavoriteAPIRequest;
use App\Models\Client;
use App\Models\Menu\ProductFavorite;
use App\Repositories\ClientRepository;
use App\Repositories\ProductFavoriteRepository;
use Illuminate\Http\Request;
use Response;

/**
 * Class ProductFavoriteController
 * @package App\Http\Controllers\API
 */
class MenuProductFavoriteAPIController extends AppBaseController
{
    
    /** @var  ProductFavoriteRepository */
    private $productFavoriteRepository;
    
    /** @var  ClientRepository */
    private $clientRepository;
    
    /**
     *
     * @SWG\Tag(
     *   name="ProductFavorite",
     *   description="Operations with the ProductFavorites"
     * ),
     * @SWG\Response(
     *          response="ProductFavorites",
     *          description="Array of ProductFavorite objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/MenuProductFavoriteGet"),
     *              )
     *          )
     * ),
     * @SWG\Response(
     *          response="ProductFavorite",
     *          ref="$/responses/200",
     *          description="ProductFavorite object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/MenuProductFavoriteGet"
     *              )
     *          )
     * ),
     */
    public function __construct(ProductFavoriteRepository $productFavoriteRepo, ClientRepository $clientRepo)
    {
        
        $this->productFavoriteRepository = $productFavoriteRepo;
        $this->clientRepository          = $clientRepo;
    }
    
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/menu_products/favorites",
     *      summary="Get a listing of the User's ProductFavorites.",
     *      tags={"Menu"},
     *      @SWG\Parameter(
     *          name="client_id",
     *          in="query",
     *          type="integer",
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/ProductFavorites"),
     * )
     */
    
    public function index(SearchProductFavoriteAPIRequest $request)
    {
        
        /** @var Client $client */
        $client = $this->clientRepository->find($request->get('client_id'));
        
        $productFavorites = $client->menu_product_favorites;
        
        return $this->sendResponse($productFavorites->toArray(), 'Product Favorites retrieved successfully');
    }
    
    /**
     * @param CreateProductFavoriteAPIRequest $request
     *
     * @return Response
     *
     * @SWG\Post(
     *      path="/menu_products/favorites",
     *      summary="Store a newly created ProductFavorite in storage",
     *      tags={"Menu"},
     *      @SWG\Parameter(
     *          name="client_id",
     *          in="query",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="menu_product_id",
     *          in="query",
     *          type="integer",
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/ProductFavorite"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     */
    
    public function store(CreateProductFavoriteAPIRequest $request)
    {
        
        $input = $request->all();
        
        $productFavorite = ProductFavorite::updateOrInsert($input);
        
        //        $productFavorite = $this->productFavoriteRepository->create($input);
        
        return $this->sendResponse($input, 'Product Favorite saved successfully');
    }
    
    
    /**
     * @param CreateProductFavoriteAPIRequest $request
     *
     * @return Response
     *
     * @throws \Exception
     * @SWG\Delete(
     *      path="/menu_products/favorites",
     *      summary="Remove the specified ProductFavorite from storage",
     *      tags={"Menu"},
     *      @SWG\Parameter(
     *          name="client_id",
     *          in="query",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="menu_product_id",
     *          in="query",
     *          type="integer",
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/200"),
     *      @SWG\Response(response="404", description="ProductFavorite not found"),
     * )
     */
    
    public function destroy(CreateProductFavoriteAPIRequest $request)
    {
        
        $input = $request->all();
        
        /** @var ProductFavorite $productFavorite */
        $productsFavorite = $this->productFavoriteRepository->deleteWhere($input);
        
        return $this->sendResponse($productsFavorite, 'Product Favorite deleted successfully');
    }
}
