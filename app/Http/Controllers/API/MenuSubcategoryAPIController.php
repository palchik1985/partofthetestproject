<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Repositories\MenuSubcategoryRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class MenuCategoryController
 * @package App\Http\Controllers\API
 */
class MenuSubcategoryAPIController extends AppBaseController
{
    
    /** @var  MenuSubcategoryRepository */
    private $menuSubcategoryRepository;
    
    /**
     *
     * @SWG\Response(
     *          response="MenuSubcategories",
     *          description="Array of MenuSubcategory objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/MenuSubcategoryGet"),
     *              )
     *          )
     * ),
     * @SWG\Response(
     *          response="MenuSubcategory",
     *          ref="$/responses/200",
     *          description="MenuSubcategory object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/MenuSubcategoryGet"
     *              )
     *          )
     * ),
     */
    public function __construct(MenuSubcategoryRepository $menuCategoryRepo)
    {
        
        $this->menuSubcategoryRepository = $menuCategoryRepo;
    }
    
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/menu_subcategories",
     *      summary="Get a listing of the MenuSubcategories.",
     *      tags={"Menu"},
     *      @SWG\Response(response=200, ref="#/responses/MenuSubcategories"),
     * )
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    
    public function index(Request $request)
    {
    
        // todo sort by order
        $this->menuSubcategoryRepository->pushCriteria(new RequestCriteria($request));
        $menuCategories = $this->menuSubcategoryRepository->all();
        
        return $this->sendResponse($menuCategories->toArray(), 'Menu Categories retrieved successfully');
    }
}
