<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Repositories\MenuProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class MenuCategoryController
 * @package App\Http\Controllers\API
 */
class MenuProductAPIController extends AppBaseController
{
    
    /** @var  MenuProductRepository */
    private $menuProductRepository;
    
    /**
     *
     * @SWG\Response(
     *          response="MenuProducts",
     *          description="Array of MenuProduct objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/MenuProductGet"),
     *              )
     *          )
     * ),
     * @SWG\Response(
     *          response="MenuProduct",
     *          ref="$/responses/200",
     *          description="MenuProduct object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/MenuProductGet"
     *              )
     *          )
     * ),
     */
    public function __construct(MenuProductRepository $menuProductRepo)
    {
        
        $this->menuProductRepository = $menuProductRepo;
    }
    
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/menu_products",
     *      summary="Get a listing of the MenuProducts.",
     *      tags={"Menu"},
     *      @SWG\Response(response=200, ref="#/responses/MenuProducts"),
     * )
     * @throws \Prettus\Repository\Exceptions\RepositoryException
     */
    
    public function index(Request $request)
    {
        $this->menuProductRepository->pushCriteria(new RequestCriteria($request));
        $menuProducts = $this->menuProductRepository->with('types')->all();
        
        return $this->sendResponse($menuProducts->toArray(), 'Menu Products retrieved successfully');
    }
    
    
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/menu_products/pdf",
     *      summary="Get a listing of the MenuProducts.",
     *      tags={"Menu"},
     *      @SWG\Response(
     *          response=200,
     *          ref="$/responses/200",
     *          description="Menu PDF link",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *                  @SWG\Property(
     *                      property="menu_url",
     *                      type="string",
     *                  )
     *              )
     *          )
     *      ),
     * )
     */
    public function getMenuPdf(Request $request)
    {
    
        $restaurant_id = $request->get('restaurant_id') ?? 1;
        $menu_pdf      = DB::table('menu_pdfs')->where(['restaurant_id' => $restaurant_id])->first();
        if (empty($menu_pdf)) {
            return $this->sendError('Menu not found');
        }
    
        $menu_pdf->menu_url = Storage::disk('local')->url(sprintf('img/menu_pdf/%d/%s', $menu_pdf->restaurant_id,
            $menu_pdf->image));
    
        // todo add actual menu link
        return $this->sendResponse(['menu_url' => 'queen.bigdig.com.ua/img/menu_pdf/1/menu.pdf'],
            'Menu pdf file retrieved successfully');
    }
}
