<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Repositories\MenuCategoryRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class MenuCategoryController
 * @package App\Http\Controllers\API
 */
class MenuCategoryAPIController extends AppBaseController
{
    
    /** @var  MenuCategoryRepository */
    private $menuCategoryRepository;
    
    /**
     *
     * @SWG\Tag(
     *   name="Menu",
     *   description="Operations with the Menu"
     * ),
     * @SWG\Response(
     *          response="MenuCategories",
     *          description="Array of MenuCategory objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/MenuCategoryGet"),
     *              )
     *          )
     * ),
     * @SWG\Response(
     *          response="MenuCategory",
     *          ref="$/responses/200",
     *          description="MenuCategory object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/MenuCategoryGet"
     *              )
     *          )
     * ),
     */
    public function __construct(MenuCategoryRepository $menuCategoryRepo)
    {
        
        $this->menuCategoryRepository = $menuCategoryRepo;
    }
    
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/menu_categories",
     *      summary="Get a listing of the MenuCategories.",
     *      tags={"Menu"},
     *      @SWG\Response(response=200, ref="#/responses/MenuCategories"),
     * )
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    
    public function index(Request $request)
    {
    
        // todo sort by order
        $this->menuCategoryRepository->pushCriteria(new RequestCriteria($request));
        $menuCategories = $this->menuCategoryRepository->all();
        
        return $this->sendResponse($menuCategories->toArray(), 'Menu Categories retrieved successfully');
    }
}
