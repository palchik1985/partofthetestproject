<?php

namespace App\Http\Controllers;

use App\Criteria\PaginateCriteria;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;

/**
 * @SWG\Swagger(
 *   basePath="/api",
 *   @SWG\Info(
 *     title="Laravel Generator APIs",
 *     version="1.0.0",
 *   )
 * )
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{
    
    
    /**
     * @SWG\Parameter(
     *          parameter="id_in_path_required",
     *          name="id",
     *          type="integer",
     *          required=true,
     *          in="path"
     * ),
     *
     * @SWG\Parameter(
     *          parameter="page",
     *          name="page",
     *          type="integer",
     *          required=false,
     *          in="query"
     * ),
     */
    
    /**
     * @SWG\Response(
     *          response="200",
     *          description="Successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="success", type="boolean"),
     *              @SWG\Property(property="data"),
     *              @SWG\Property(property="message", type="string"),
     *          )
     * )
     */
    public function sendResponse($result, $message)
    {
        
        return Response::json(ResponseUtil::makeResponse($message, $result));
    }
    
    
    /**
     * @SWG\Response(
     *          response="404",
     *          description="Not Found"
     * ),
     * @SWG\Response(
     *          response="422",
     *          description="Validation Error"
     * ),
     * @SWG\Response(
     *         response="401",
     *         description="Unauthorized user"
     * ),
     *
     * @param     $error
     * @param int $code
     *
     * @return JsonResponse
     */
    public function sendError($error, $code = 404)
    {
        
        return Response::json(ResponseUtil::makeError($error), $code);
    }
    
    
    public function formDataArrayForResponse($items, $request, $repository)
    {
        
        $totalCountItems = $repository->popCriteria(new PaginateCriteria($request))->count();
        
        if(!is_array($items)){
            $items = $items->toArray();
        }
        
        $data[ 'items' ]   = $items;
        $data[ 'records' ] = $totalCountItems;
        if ( ! empty($request->get('page'))) {
            $data[ 'perPage' ]    = $request->get('per_page') ?? 20;
            $data[ 'totalPages' ] = intval(ceil($data[ 'records' ] / $data[ 'perPage' ]));
            $data[ 'page' ]       = $request->get('page');
        }
        
        return $data;
    }
}
