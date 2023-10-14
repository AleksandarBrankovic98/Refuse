<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Contact extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $guarded = [];
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
    public function getLeadCategory()
    {
        $sms = Sms::where('client_number', $this->number)->first();
        if ($sms == null) {
            $leadCategory = "Not Contacted Yet";
        } else {
            $leadCategory = $sms->getLeadName();
        }

        return $leadCategory;
    }
    
}
