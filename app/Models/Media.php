<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Support\Facades\Storage;

/**
 * // toDo: set only fillable fields "EventSet" block. And all other in EventGet block.
 * @SWG\Definition(
 *      definition="MediaSet",
 *      type="object",
 *      required={""},
 *      @SWG\Property(
 *          property="title",
 *          description="title",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="type",
 *          description="type",
 *          type="string",
 *          enum={"image", "video"}
 *      ),
 * ),
 * @SWG\Definition(
 *  definition="MediaGet",
 *  allOf={
 *    @SWG\Schema(ref="#/definitions/MediaSet"),
 *    @SWG\Schema(
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="path",
 *          type="string",
 *      ),
 *      @SWG\Property(
 *          property="url",
 *          type="string",
 *      ),
 *      @SWG\Property(
 *          property="mediable_id",
 *          description="mediable_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="mediable_type",
 *          type="string",
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 *    ),
 *  }
 * ),
 */
class Media extends Model
{
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
    /**
     * Validation rules for create Model
     *
     * @var array
     */
    public static $create_rules = [
        'type'          => 'required|in:image,video',
        'file'          => 'image|required_if:type,image',
        'mediable_type' => 'required|string|in:event',
        'mediable_id'   => 'required|integer',
        'video_link'    => 'url|required_if:type,video',
    ];
    /**
     * Validation rules for update Model
     *
     * @var array
     */
    public static $update_rules = [
        //        'type'          => 'required|in:image,video',
        //        'file'          => 'image|required_if:type,image',
        //        'mediable_type' => 'required|string|in:event',
        //        'mediable_id'   => 'required|integer',
        //        'video_link'    => 'url|required_if:type,video',
    ];
    public        $table        = 'media';
    public        $fillable     = [
        'title',
        'type',
        'mediable_type',
        'mediable_id',
        'path',
    ];
    protected     $appends      = ['url'];
    protected     $hidden       = ['mediable_type', 'mediable_id', 'path'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'    => 'integer',
        'title' => 'string',
        'type'  => 'string',
        'path'  => 'string',
    ];
    
    /**
     * Get the owning commentable model.
     */
    public function mediable()
    {
        
        return $this->morphTo();
    }
    
    public function getUrlAttribute()
    {
        
        if ($this->type == 'image') {
            return $this->attributes[ 'url' ] = Storage::disk('local')->url($this->path);
        }
    
        if ($this->type == 'video') {
            return $this->attributes[ 'url' ] = $this->path;
        }
        
        return null;
    }
}
