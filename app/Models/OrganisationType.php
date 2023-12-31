<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class OrganisationType extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['name'];

    public function organisations()
    {
        return $this->hasMany(Organisation::class);
    }

    public function children()
    {
        return $this->belongsToMany(OrganisationType::class, 'organisation_type_organisation_type', 'organisation_type_id', 'child_id')->withPivot('notes');
    }

    public function parents()
    {
        return $this->belongsToMany(OrganisationType::class, 'organisation_type_organisation_type', 'child_id', 'organisation_type_id')->withPivot('notes');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
