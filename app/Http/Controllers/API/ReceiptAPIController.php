<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\DefaultAPIRequest;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;

class ReceiptAPIController extends AppBaseController
{
    
    /**
     * @param Request $request
     *
     * @return Response
     *
     * @SWG\Get(
     *      path="/receipts",
     *      summary="Get a listing of the Receipts of given client ID.",
     *      tags={"Reserve"},
     *      @SWG\Parameter(
     *          in="query",
     *          name="client_id",
     *          type="integer",
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Events"),
     * )
     * @throws RepositoryException
     */
    
    public function index(DefaultAPIRequest $request)
    {
        
        $client_id = $request->get('client_id');
        if (empty($client_id)) {
            return $this->sendError('User ID is required', 422);
        }
        $receipts = Receipt::getAll($client_id);
        
        $data[ 'records' ] = $receipts->count();
        $data[ 'items' ]   = $receipts->toArray();
        
        return $this->sendResponse($data, 'Receipts retrieved successfully');
    }
}
