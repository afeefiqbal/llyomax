<?php

namespace App\Models\Accounts;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
class Expense extends Model  implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    protected $fillable = [
        'expense_name',
        'date',
        'amount',
        'particulars',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('expense_bills');
    }
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('expense_bill')->optimize();

    }
    public function parseImage()
    {
        return [
            "url" => $this->hasMedia('expense_bills') ? $this->getFirstMediaUrl('expense_bill') : "https://2.bp.blogspot.com/-eu7iHP0VeHY/XHMby8JSRRI/AAAAAAAAKUo/zcLJN8JN1yoZ3q0eNxgmjPXRN9UbPFEwgCLcBGAs/s1600/red-velvet-macarons.jpg"
        ];
    }

}
