<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ReserveSmsId extends Pivot
{
    
    public static $create_rules = [
        'reserve_id' => 'required',
        'sms_id'     => 'required',
    ];
    public        $table        = 'reserve_sms';
    public        $timestamps   = false;
    public        $fillable     = [
        'reserve_id',
        'sms_id',
    ];
    protected     $casts        = [
        'id'         => 'integer',
        'reserve_id' => 'integer',
        'sms_id'     => 'integer',
    ];
    
    public function reserve()
    {
        
        return $this->belongsTo('App\Models\Reserve');
        
    }
    
    public function sms()
    {
        
        return SMS::find($this->sms_id);
        
    }
}
