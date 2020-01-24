<?php

namespace App\Http\Controllers\API;

use App\Criteria\PaginateCriteria;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateRestaurantAPIRequest;
use App\Http\Requests\API\UpdateRestaurantAPIRequest;
use App\Models\Restaurant;
use App\Repositories\RestaurantRepository;
use Illuminate\Http\Request;
use Response;

/**
 * Class RestaurantController
 * @package App\Http\Controllers\API
 */
class RestaurantAPIController extends AppBaseController
{
    
    /** @var  RestaurantRepository */
    private $restaurantRepository;
    
    /**
     *
     * @SWG\Tag(
     *   name="Restaurant",
     *   description="Operations with the Restaurants"
     * ),
     * @SWG\Response(
     *          response="Restaurants",
     *          description="Array of Restaurant objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  @SWG\Items(ref="#/definitions/RestaurantGet"),
     *              )
     *          )
     * ),
     * @SWG\Response(
     *          response="Restaurant",
     *          ref="$/responses/200",
     *          description="Restaurant object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/RestaurantGet"
     *              )
     *          )
     * ),
     */
    public function __construct(RestaurantRepository $restaurantRepo)
    {
        
        $this->restaurantRepository = $restaurantRepo;
    }
    
    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/restaurants",
     *      summary="Get a listing of the Restaurants.",
     *      tags={"Restaurant"},
     *      @SWG\Response(response=200, ref="#/responses/Restaurants"),
     * )
     */
    
    public function index(Request $request)
    {
        
        $this->restaurantRepository->pushCriteria(new PaginateCriteria($request));
        $restaurants = $this->restaurantRepository->all();
        
        return $this->sendResponse($restaurants->toArray(), 'Restaurants retrieved successfully');
    }
    
    /**
     * @param CreateRestaurantAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/restaurants",
     *      summary="Store a newly created Restaurant in storage",
     *      tags={"Restaurant"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Restaurant that should be stored",
     *          @SWG\Schema(ref="#/definitions/RestaurantSet")
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Restaurant"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     */
    
    public function store(CreateRestaurantAPIRequest $request)
    {
        
        $input = $request->all();
        
        $restaurant = $this->restaurantRepository->create($input);
        
        return $this->sendResponse($restaurant->toArray(), 'Restaurant saved successfully');
    }
    
    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/restaurants/{id}",
     *      summary="Display the specified Restaurant",
     *      tags={"Restaurant"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Restaurant"),
     *      @SWG\Response(response=200, ref="#/responses/Restaurant"),
     *      @SWG\Response(response="404", description="Restaurant not found"),
     * )
     */
    
    public function show($id)
    {
        
        /** @var Restaurant $restaurant */
        $restaurant = $this->restaurantRepository->find($id);
        
        if (empty($restaurant)) {
            return $this->sendError('Restaurant not found');
        }
        
        return $this->sendResponse($restaurant->toArray(), 'Restaurant retrieved successfully');
    }
    
    /**
     * @param int $id
     * @param UpdateRestaurantAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/restaurants/{id}",
     *      summary="Update the specified Restaurant in storage",
     *      tags={"Restaurant"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Restaurant"),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Restaurant that should be updated",
     *          @SWG\Schema(ref="#/definitions/RestaurantSet")
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Restaurant"),
     *      @SWG\Response(response="404", description="Restaurant not found"),
     *      @SWG\Response(response="422", ref="#/responses/422"),
     * )
     */
    
    public function update($id, UpdateRestaurantAPIRequest $request)
    {
        
        $input = $request->all();
        
        /** @var Restaurant $restaurant */
        $restaurant = $this->restaurantRepository->find($id);
        
        if (empty($restaurant)) {
            return $this->sendError('Restaurant not found');
        }
        
        $restaurant = $this->restaurantRepository->update($input, $id);
        
        return $this->sendResponse($restaurant->toArray(), 'Restaurant updated successfully');
    }
    
    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/restaurants/{id}",
     *      summary="Remove the specified Restaurant from storage",
     *      tags={"Restaurant"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Restaurant"),
     *      @SWG\Response(response=200, ref="#/responses/200"),
     *      @SWG\Response(response="404", description="Restaurant not found"),
     * )
     */
    
    public function destroy($id)
    {
        
        /** @var Restaurant $restaurant */
        $restaurant = $this->restaurantRepository->find($id);
        
        if (empty($restaurant)) {
            return $this->sendError('Restaurant not found');
        }
        
        $restaurant->delete();
        
        return $this->sendResponse($id, 'Restaurant deleted successfully');
    }
}
