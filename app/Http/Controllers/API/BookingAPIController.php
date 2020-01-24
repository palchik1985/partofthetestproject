<?php

namespace App\Http\Controllers\API;

use App\Criteria\FindTablesForDateCriteria;
use App\Http\Controllers\AppBaseController;
use App\Models\Reserve;
use App\Models\RestaurantSpace;
use App\Models\Table;
use App\Models\TablePreset;
use App\Repositories\EventRepository;
use App\Repositories\ReserveRepository;
use App\Repositories\TablesForDateRepository;
use Illuminate\Http\Request;
use Prettus\Repository\Exceptions\RepositoryException;
use Response;

/**
 * Class ReserveController
 * @package App\Http\Controllers\API
 */
class BookingAPIController extends AppBaseController
{
    
    /** @var  ReserveRepository */
    private $reserveRepository;
    private $tablesForDateRepository;
    /** @var  EventRepository */
    private $eventRepository;
    
    /**
     *
     * @SWG\Tag(
     *   name="Booking",
     *   description="Operations with the Tables Booking for date"
     * ),
     * @SWG\Response(
     *          response="Bookings",
     *          description="Array of Reserve objects",
     *          ref="$/responses/200",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *                  @SWG\Property(property="records", type="integer"),
     *                  @SWG\Property(property="perPage", type="integer"),
     *                  @SWG\Property(property="totalPages", type="integer"),
     *                  @SWG\Property(property="page", type="integer"),
     *                  @SWG\Property(
     *                       property="items",
     *                       type="array",
     *                      @SWG\Items(ref="#/definitions/ReserveGet"),
     *                 )
     *             )
     *         )
     * ),
     * @SWG\Response(
     *          response="Booking",
     *          ref="$/responses/200",
     *          description="Reserve object",
     *          @SWG\Schema(
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/ReserveGet"
     *              )
     *          )
     * ),
     */
    public function __construct(
        ReserveRepository $reserveRepo,
        TablesForDateRepository $tablesForDateRepository,
        EventRepository $eventRepo
    )
    {
    
        $this->eventRepository         = $eventRepo;
        $this->reserveRepository       = $reserveRepo;
        $this->tablesForDateRepository = $tablesForDateRepository;
    }
    
    /**
     * @param int     $restaurant_space_id
     * @param         $date
     * @param Request $request
     *
     * @return Response
     *
     * @throws RepositoryException
     * @SWG\Get(
     *      path="/booking/{restaurant_space_id}/{date}",
     *      summary="Get a listing of the Reserves and TablesForDate to specified date.",
     *      tags={"Booking"},
     *      @SWG\Parameter(
     *          name="restaurant_space_id",
     *          in="path",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="date",
     *          in="path",
     *          type="string",
     *          format="2000-12-30",
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Reserves"),
     * )
     */
    
    public function index(int $restaurant_space_id, $date, Request $request)
    {
        
        $tablesForDate = $this->tablesForDateRepository
            ->getByCriteria(new FindTablesForDateCriteria($restaurant_space_id, $date))
            ->first();
    
        $preset_id = ! empty($tablesForDate) ? $tablesForDate->table_preset_id : null;
        
        if ( ! empty($preset_id)) {
            $preset = TablePreset::where('id', '=', $preset_id)->with('tables')->first();
        } else {
            $preset = TablePreset::getDefault($restaurant_space_id);
            if (empty($preset)) {
                return $this->sendError('Default preset not found', 409);
            }
        }
        
        $data[ 'tablesForDate' ]   = $tablesForDate;
        $data[ 'tablePreset' ]     = $preset;
        $data[ 'restaurantSpace' ] = RestaurantSpace::find($restaurant_space_id);
    
        //get all available tables
        $additionalTables = ! empty($tablesForDate->additional_tables) ? $tablesForDate->additional_tables->pluck('id')->toArray() : [];
        $mainTables       = ! empty($preset->tables) ? $preset->tables->pluck('id')->toArray() : [];
        $tables           = array_merge($mainTables, $additionalTables);
    
        $data[ 'reserves' ]         = Reserve::getItems($date, $tables);
        $data[ 'overbookReserves' ] = Reserve::getItems($date, $tables, true);
    
        $data[ 'events' ] = $this->eventRepository->todayEvents($date, $restaurant_space_id);
        
        return $this->sendResponse($data, 'Reserves retrieved successfully');
    }
    
    
    /**
     * @param int     $restaurant_space_id
     * @param         $date
     * @param Request $request
     *
     * @return Response
     *
     * @throws RepositoryException
     * @SWG\Get(
     *      path="/booking_from_mobile/{restaurant_space_id}/{date}",
     *      summary="Get a listing of the Reserves and TablesForDate to specified date.",
     *      tags={"Booking"},
     *      @SWG\Parameter(
     *          name="restaurant_space_id",
     *          in="path",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="date",
     *          in="path",
     *          type="string",
     *          format="2000-12-30",
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Reserves"),
     * )
     */
    
    public function bookingFromMobile(int $restaurant_space_id, $date, Request $request)
    {
        
        $tablesForDate = $this->tablesForDateRepository
            ->getByCriteria(new FindTablesForDateCriteria($restaurant_space_id, $date))
            ->first();
        
        $preset_id = ! empty($tablesForDate) ? $tablesForDate->table_preset_id : null;
        
        if ( ! empty($preset_id)) {
            $preset = TablePreset::where('id', '=', $preset_id)->with('tables')->first();
        } else {
            $preset = TablePreset::getDefault($restaurant_space_id);
            if (empty($preset)) {
                return $this->sendError('Default preset not found', 409);
            }
        }
    
        $data                       = $tablesForDate;
        $data[ 'table_preset' ]     = $preset;
        $data[ 'restaurant_space' ] = RestaurantSpace::find($restaurant_space_id);
        $data[ 'events' ]           = $this->eventRepository->todayEvents($date, $restaurant_space_id);
        
        return $this->sendResponse($data, 'Reserves retrieved successfully');
    }
    
    
    /**
     * @param int $id
     *
     * @return Response
     *
     * @SWG\Delete(
     *      path="/booking/{date}/{id}",
     *      summary="Remove the specified Reserve from storage",
     *      tags={"Booking"},
     *      @SWG\Parameter(ref="$/parameters/id_in_path_required", description="id of Reserve"),
     *      @SWG\Response(response=200, ref="#/responses/200"),
     *      @SWG\Response(response="404", description="Reserve not found"),
     * )
     */
    
    public function destroy($id)
    {
        
        /** @var Reserve $reserve */
        $reserve = $this->reserveRepository->find($id);
        
        if (empty($reserve)) {
            return $this->sendError('Reserve not found');
        }
        
        $reserve->delete();
        
        return $this->sendResponse($id, 'Reserve deleted successfully');
    }
    
    
    /**
     * @SWG\Get(
     *      path="/available_tables_for_add_additional/{restaurant_space_id}/{date}",
     *      summary="Get a listing of the Tables which not placed in any space to the specified date.",
     *      tags={"Booking"},
     *      @SWG\Parameter(
     *          name="restaurant_space_id",
     *          in="path",
     *          type="integer",
     *      ),
     *      @SWG\Parameter(
     *          name="date",
     *          in="path",
     *          type="string",
     *          format="2000-12-30",
     *      ),
     *      @SWG\Response(response=200, ref="#/responses/Reserves"),
     * )
     */
    public function getAvailableTablesForAddAdditional(int $restaurant_space_id, $date)
    {
        
        // получить все залы ресторана
        $restaurant_space  = RestaurantSpace::find($restaurant_space_id);
        $restaurant_spaces = RestaurantSpace::where('restaurant_id', '=',
            $restaurant_space->restaurant_id)->pluck('id');
        
        $tables = collect([]);
        foreach ($restaurant_spaces as $space_id) {
            
            // получить все столы в зале
            
            // получить доп столы
            $tablesForDate = $this->tablesForDateRepository
                ->getByCriteria(new FindTablesForDateCriteria($space_id, $date))
                ->first();
            
            if ( ! empty($tablesForDate)) {
                $additionalTables = ! empty($tablesForDate->additional_tables) ? $tablesForDate->additional_tables->pluck('id')->toArray() : [];
                $tables           = $tables->merge($additionalTables);
            }
            
            // получить основные столы
            $preset_id = ! empty($tablesForDate) ? $tablesForDate->table_preset_id : null;
            
            if ( ! empty($preset_id)) {
                $preset = TablePreset::where('id', '=', $preset_id)->with('tables')->first();
            } else {
                $preset = TablePreset::getDefault($restaurant_space_id);
            }
            $mainTables = ! empty($preset->tables) ? $preset->tables->pluck('id')->toArray() : [];
            $tables     = $tables->merge($mainTables);
        }
        
        $availableTables = Table::whereNotIn('id', $tables->unique())->get();
        
        return $this->sendResponse($availableTables, 'Available tables retrieved successfully');
        
    }
}
