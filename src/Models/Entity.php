<?php

namespace Kusikusi\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use PUGX\Shortid\Shortid;
use Ankurk91\Eloquent\BelongsToOne;
use Kusikusi\Extensions\EntityCollection;
use Kusikusi\Relations\PluckedHasMany;
use Kusikusi\Models\Traits\UsesShortId;
use Kusikusi\Models\EntityRelation;
use Kusikusi\Models\EntityArchive;
use Kusikusi\Models\EntityRoute;
use Kusikusi\Casts\Json;
use Illuminate\Support\Carbon;


class Entity extends Model
{
    use BelongsToOne;
    use HasFactory;
    use UsesShortId;
    use SoftDeletes;

    protected $table = 'entities';

    /* protected $guarded = ['created_at', 'updated_at']; */
    protected $fillable = ['id', 'model', 'view', 'properties', 'visibility', 'parent_entity_id', 'created_at', 'published_at', 'unpublished_at'];
    protected $contentFields = [];
    protected $propertiesFields = [];
    // protected $touches = ['entities_relating'];
    protected $ancestorsRelations = true;

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = []) {
        return new EntityCollection($models);
    }

    /**
    * @var array A list of columns from the entities tables and other joins needs to be casted
    */
    protected $casts = [
        'properties' => Json::class,
        'published_at' => 'datetime',
        'unpublished_at' => 'datetime',
        'langs' => 'array'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Kusikusi\Database\Factories\EntityFactory::new();
    }

    /**
     * Prepare a date for array / JSON serialization.
     *
     * @param  \DateTimeInterface  $date
     * @return string
     */
    protected function serializeDate($date)
    {
        return $date->format('Y-m-d\TH:i:sP');
    }


    /**
     * RELATIONS
     */

    /**
     * Get the entities this entity is calling
     */
    public function entities_related($kind = null, $modelClassPath = 'Kusikusi\Models\Entity')
    {
        return $this->belongsToMany($modelClassPath, (new EntityRelation())->getTable(), 'caller_entity_id', 'called_entity_id')
            ->using('Kusikusi\Models\EntityRelation')
            ->as('relation')
            ->withPivot('kind', 'position', 'depth', 'tags')
            ->when($kind, function ($q) use ($kind) {
                return $q->where('kind', $kind);
            })
            ->when($kind === null, function ($q) use ($kind) {
                return $q->where('kind', '!=', EntityRelation::RELATION_ANCESTOR);
            })
            ->withTimestamps();
    }

    /**
     * Get the entities this entity is calling with kind medium
     */
    public function media() {
        return $this->entities_related(EntityRelation::RELATION_MEDIA, 'App\Models\Medium');
    }

    /**
     * Get only one entity this entity is calling with kind medium
     */
    public function medium($tag = null, $lang = null) {
        return $this->belongsToOne('App\Models\Medium', 'entities_relations', 'caller_entity_id', 'called_entity_id')
            ->using('Kusikusi\Models\EntityRelation')
            ->as('relation')
            ->withPivot('kind', 'position', 'depth', 'tags')
            ->where('kind', EntityRelation::RELATION_MEDIA);
    }

    /**
     * Get the other entities calling this entity
     */
    public function entities_relating($kind = null, $modelClassPath = 'Kusikusi\Models\Entity') {
        return $this->belongsToMany($modelClassPath, (new EntityRelation())->getTable(), 'called_entity_id', 'caller_entity_id')
            ->using('Kusikusi\Models\EntityRelation')
            ->as('relation')
            ->withPivot('kind', 'position', 'depth', 'tags')
            ->when($kind, function ($q) use ($kind) {
                return $q->where('kind', $kind);
            })
            ->when($kind === null, function ($q) use ($kind) {
                return $q->where('kind', '!=', EntityRelation::RELATION_ANCESTOR);
            })
            ->withTimestamps();
    }
    /**
     * Get the records in the entities_relations table this entity is using
     */
    public function entity_relations() {
        return $this->hasMany(EntityRelation::class, 'caller_entity_id', 'id')
            ->where('kind', '!=', EntityRelation::RELATION_ANCESTOR);
    }
    /**
     * Get the raw contents related to this entity
     */
    public function contents() {
        return $this->hasMany(EntityContent::class, 'entity_id', 'id');
    }
    /**
     * Get the contents by field name (plucked)
     */
    public function content() {
        return $this->pluckedHasMany(EntityContent::class, 'entity_id', 'id', 'text', 'field');
    }
    /**
     * Get the archives of this entity
     */
    public function archives() {
        return $this->hasMany(EntityArchive::class, 'entity_id', 'id');
    }
    /**
     * Get the routes of this entity
     */
    public function routes() {
        return $this->hasMany(EntityRoute::class, 'entity_id', 'id');
    }
    /**
     * Get one routes of this entity, main if no other defined
     */
    public function route() {
        return $this->hasOne(EntityRoute::class, 'entity_id', 'id');
    }

    /**
     * SCOPES
     */

    /**
     * Scope to include contents relation, plucked (field->value) filtered by lang and fields.
     *
     * @param  Builder $query
     * @param  string $lang
     * @param  array $fields
     * @return Builder
     */
    public function scopeWithContent($query, $lang = null, $fields = null)
    {
        return $query->with(['content' => function($q) use ($lang, $fields) {
            $q->when($lang !== null, function ($q) use ($lang, $fields) {
                return $q->where('lang', $lang);
            });
            $q->when($fields !== null, function ($q) use ($fields) {
                if (is_array($fields)) return $q->whereIn('field', $fields);
                if (is_string($fields)) return $q->where('field', $fields);
            });
        }]);
    }

    /**
     * Scope to include contents relation, filtered by lang and fields.
     *
     * @param  Builder $query
     * @param  string $lang
     * @param  array $fields
     * @return Builder
     */
    public function scopeWithContents($query, $lang = null, $fields = null)
    {
        return $query->with(['contents' => function($q) use ($lang, $fields) {
            $q->when($lang !== null, function ($q) use ($lang, $fields) {
                return $q->where('lang', $lang);
            });
            $q->when($fields !== null, function ($q) use ($fields) {
                if (is_array($fields)) return $q->whereIn('field', $fields);
                if (is_string($fields)) return $q->where('field', $fields);
            });
        }]);
    }

    /**
     * Scope a query to only include entities of a given modelId.
     *
     * @param  Builder $query
     * @param  mixed $modelId
     * @return Builder
     */
    public function scopeOfModel($query, $modelId)
    {
        // TODO: Accept array of model ids
        return $query->where('model', $modelId);
    }

    /**
     * Scope a query to only include published entities.
     *
     * @param  Builder $query
     * @return Builder
     */
    public function scopeIsPublished($query)
    {
        return $query->where('visibility', 'public')
            ->whereDate('published_at', '<=', Carbon::now()->setTimezone('UTC')->format('Y-m-d\TH:i:s'))
            ->where(function($query) {
                $query->whereDate('unpublished_at', '>', Carbon::now()->setTimezone('UTC')->format('Y-m-d\TH:i:s'))
                    ->orWhereNull('unpublished_at');
            })
            ->whereNull('deleted_at');
    }

    /**
     * Scope a query to only include children of a given parent id.
     *
     * @param Builder $query
     * @param integer $entity_id The id of the parent entity
     * @param string $tag Filter by one tag
     * @return Builder
     * @throws \Exception
     */
    public function scopeChildrenOf($query, $entity_id, $tag = null)
    {
        $query->join('entities_relations as child', function ($join) use ($entity_id, $tag) {
            $join->on('child.caller_entity_id', '=', 'entities.id')
                ->where('child.called_entity_id', '=', $entity_id)
                ->where('child.depth', '=', 1)
                ->where('child.kind', '=', EntityRelation::RELATION_ANCESTOR)
                ->when($tag, function ($q) use ($tag) {
                    return $q->whereJsonContains('child.tags', $tag);
                });
            ;
        })
            ->addSelect('id')
            ->addSelect('child.relation_id as child.relation_id')
            ->addSelect('child.position as child.position')
            ->addSelect('child.depth as child.depth')
            ->addSelect('child.tags as child.tags');
    }

    /**
     * Scope a query to only include the parent of the given id.
     *
     * @param Builder $query
     * @param number $entity_id The id of the parent entity
     * @return Builder
     * @throws \Exception
     */
    public function scopeParentOf($query, $entity_id)
    {
        $query->join('entities_relations as parent', function ($join) use ($entity_id) {
            $join->on('parent.called_entity_id', '=', 'entities.id')
                ->where('parent.caller_entity_id', '=', $entity_id)
                ->where('parent.depth', '=', 1)
                ->where('parent.kind', '=', EntityRelation::RELATION_ANCESTOR)
            ;
        })
            ->addSelect('id')
            ->addSelect('parent.relation_id as parent.relation_id')
            ->addSelect('parent.position as parent.position')
            ->addSelect('parent.depth as parent.depth')
            ->addSelect('parent.tags as parent.tags');
    }

    /**
     * Scope a query to only include ancestors of a given entity.
     *
     * @param Builder $query
     * @param number $entity_id The id of the parent entity
     * @return Builder
     * @throws \Exception
     */
    public function scopeAncestorsOf($query, $entity_id)
    {
        $query->join('entities_relations as ancestor', function ($join) use ($entity_id) {
            $join->on('ancestor.called_entity_id', '=', 'entities.id')
                ->where('ancestor.caller_entity_id', '=', $entity_id)
                ->where('ancestor.kind', '=', EntityRelation::RELATION_ANCESTOR)
            ;
        })
            ->addSelect('id')
            ->addSelect('ancestor.relation_id as ancestor.relation_id')
            ->addSelect('ancestor.position as ancestor.position')
            ->addSelect('ancestor.depth as ancestor.depth')
            ->addSelect('ancestor.tags as ancestor.tags');
    }

    /**
     * Scope a query to only include descendants of a given entity id.
     *
     * @param Builder $query
     * @param number $entity_id The id of the  entity
     * @return Builder
     * @throws \Exception
     */
    public function scopeDescendantsOf($query, $entity_id, $depth = 99)
    {
        $query->join('entities_relations as descendant', function ($join) use ($entity_id, $depth) {
            $join->on('descendant.caller_entity_id', '=', 'entities.id')
                ->where('descendant.called_entity_id', '=', $entity_id)
                ->where('descendant.kind', '=', EntityRelation::RELATION_ANCESTOR)
                ->where('descendant.depth', '<=', $depth);
        })
            ->addSelect('id')
            ->addSelect('descendant.relation_id as descendant.relation_id')
            ->addSelect('descendant.position as descendant.position')
            ->addSelect('descendant.depth as descendant.depth')
            ->addSelect('descendant.tags as descendant.tags');
    }

    /**
     * Scope a query to only include descendants of a given entity id.
     *
     * @param Builder $query
     * @param number $entity_id The id of the  entity
     * @param string $tag Filter by tag in the relation with the parent
     * @return Builder
     * @throws \Exception
     */
    public function scopeSiblingsOf($query, $entity_id, $tag = null)
    {
        $parent_entity = Entity::find($entity_id);
        $query->join('entities_relations as sibling', function ($join) use ($parent_entity, $tag) {
            $join->on('sibling.caller_entity_id', '=', 'entities.id')
                ->where('sibling.called_entity_id', '=', $parent_entity->parent_entity_id)
                ->where('sibling.depth', '=', 1)
                ->where('sibling.kind', '=', EntityRelation::RELATION_ANCESTOR)
                ->when($tag, function ($q) use ($tag) {
                    return $q->whereJsonContains('sibling.tags', $tag);
                });
            ;
        })
            ->addSelect('id')
            ->where('entities.id', '!=', $entity_id)
            ->addSelect('sibling.relation_id as sibling.relation_id')
            ->addSelect('sibling.position as sibling.position')
            ->addSelect('sibling.depth as sibling.depth')
            ->addSelect('sibling.tags as sibling.tags');
    }

    /**
     * Scope a query to only get entities being called by.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $entity_id The id of the entity calling the relations
     * @param  string $kind Filter by type of relation, if ommited all relations but 'ancestor' will be included
     * @param  string $tag Filter by tag in relation
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRelatedBy($query, $entity_id, $kind = null, $tag = null)
    {
        $query->join('entities_relations as related_by', function ($join) use ($entity_id, $kind, $tag) {
            $join->on('related_by.called_entity_id', '=', 'entities.id')
                ->where('related_by.caller_entity_id', '=', $entity_id)
                ->when($tag, function ($q) use ($tag) {
                    return $q->whereJsonContains('related_by.tags', $tag);
                });;
            if ($kind === null) {
                $join->where('related_by.kind', '!=', 'ancestor');
            } else {
                $join->where('related_by.kind', '=', $kind);
            }
        })
        ->addSelect('id')
        ->addSelect('related_by.relation_id as related_by.relation_id',
        'related_by.kind as related_by.kind',
        'related_by.position as related_by.position',
        'related_by.depth as related_by.depth',
        'related_by.tags as related_by.tags');
    }

    /**
     * Scope a query to only get entities calling.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $entity_id The id of the entity calling the relations
     * @param string $kind Filter by type of relation, if ommited all relations but 'ancestor' will be included
     * @param string $tag Filter by tag in relation
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function scopeRelating($query, $entity_id, $kind = null, $tag = null)
    {
        $query->join('entities_relations as relating', function ($join) use ($entity_id, $kind, $tag) {
            $join->on('relating.caller_entity_id', '=', 'entities.id')
                ->where('relating.called_entity_id', '=', $entity_id)
                ->when($tag, function ($q) use ($tag) {
                    return $q->whereJsonContains('relating.tags', $tag);
                });;
            if ($kind === null) {
                $join->where('relating.kind', '!=', 'ancestor');
            } else {
                $join->where('relating.kind', '=', $kind);
            }
        })
        ->addSelect('id')
        ->addSelect('relating.relation_id as relating.relation_id',
        'relating.kind as relating.kind',
        'relating.position as relating.position',
        'relating.depth as relating.depth',
        'relating.tags as relating.tags');
    }

    /**
     * Scope a query to include only entities with a language active.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $lang The lang to scope the query
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function scopeHaveLang($query, $lang = '')
    {
        return $query->whereJsonContains('langs', $lang);
    }

    /**
     * Scope a query include routes.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $entity_id The id of the entity calling the relations
     * @param string $kind Filter by type of relation, if ommited all relations but 'ancestor' will be included
     * @param string $tag Filter by tag in relation
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function scopeWithRoutes($query, $lang = null, $kind = null)
    {
        return $query->with(['routes' => function($q) use ($lang, $kind) {
            $q->when($lang !== null, function ($q) use ($lang) {
                return $q->where('lang', $lang);
            });
            $q->when($kind !== null, function ($q) use ($kind) {
                return $q->where('kind', $kind);
            });
        }]);
    }

    /**
     * Scope a query include a route, main by default.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $entity_id The id of the entity calling the relations
     * @param string $kind Filter by type of relation, if ommited all relations but 'ancestor' will be included
     * @param string $tag Filter by tag in relation
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \Exception
     */
    public function scopeWithRoute($query, $lang = null, $kind = 'main')
    {
        return $query->with(['route' => function($q) use ($lang, $kind) {
            $q->when($lang !== null, function ($q) use ($lang) {
                return $q->where('lang', $lang);
            })
            ->where('kind', $kind);
        }]);
    }


    /**
     * Scope to append just one entity related to the result.
     *
     * @param  Builder $query
     * @param  string $kind The kind of relation
     * @param  string $tag Select the first related media that has this tag, the first medium if ommitted
     * @param  string $fields An array of fields of the Medium entities to include, ['id', 'properties'] if omitted. To automatically generate urls, please include id, and properties
     * @param  string $lang Language of the content fields of the medium
     * @param  string $content_fields Restrict the content fields of the media, or 'title' if ommited
     * @return Builder
     */

    public function scopeWithRelation($query, $kind, $tag = null, $fields = ['id', 'model', 'properties'], $lang = null, $content_fields = 'title') {
        $lang = $lang ?? Config::get('kusikusi_website.langs', [''])[0];
        $query->with([$kind => function ($relation) use ($lang, $tag, $fields, $content_fields) {
            $relation->select($fields)
            ->when(isset($tag), function ($q) use ($tag) {
                $q->whereJsonContains('tags', $tag);
                $q->orderBy('position', 'asc');
            })->when(isset($content_fields), function ($q) use ($lang, $content_fields) {
                $q->withContent($lang);
            });
        }]);
    }

    /**
     * Scope to append a medium to the result.
     *
     * @param  Builder $query
     * @param  string $tag Select the first related media that has this tag, the first medium if ommitted
     * @param  string $fields An array of fields of the Medium entities to include, ['id', 'properties'] if omitted. To automatically generate urls, please include id, and properties
     * @param  string $lang Language of the content fields of the medium
     * @param  string $content_fields Restrict the content fields of the media, or 'title' if ommited
     * @return Builder
     */

    public function scopeWithMedium($query, $tag = null, $fields = null, $lang = null, $content_fields = 'title') {
        $fields = $fields === null ? ['id', 'properties->format', 'properties->mimeType', 'properties->isWebImage', 'properties->isImage', 'properties->isAudio', 'properties->isWebAudio', 'properties->isVideo', 'properties->isWebVideo', 'properties->isDocument'] : $fields;
        $query->withRelation('medium', $tag, $fields, $lang, $content_fields);
    }

    /**
     * Scope to include only entities that has a medium of certain tag.
     *
     * @param  Builder $query
     * @param  string $tag Tag to search for
     * @return Builder
     */

    public function scopeWhereHasMediumWithTag($query, $tag = null) {
        $query->whereHas('medium', function (Builder $query) use ($tag) {
            $query->whereJsonContains('tags', $tag);
        });
    }

    /**
     * Scope to append the entities related to the result, including title a properties.
     *
     * @param  Builder $query
     * @param  string $tag Select the related media that has this tag, or all if ommited
     * @param  string $fields An array of fields of the Medium entities to include, ['id', 'properties'] if omitted. To automatically generate urls, please include id, and properties
     * @param  string $lang Language of the content fields of the medium
     * @param  string $content_fields Restrict the content fields of the media, or 'title' if ommited
     * @return Builder
     */
    public function scopeWithRelations($query, $kind = null, $tag = null, $fields = ['id', 'properties'], $lang = null, $content_fields = 'title') {
        $lang = $lang ?? Config::get('kusikusi_website.langs', [''])[0];
        $query->with(['entities_related' => function ($relation) use ($kind, $tag, $fields, $lang, $content_fields) {
            $relation->where('kind', $kind);
            $relation->select($fields)
            ->when(isset($tag), function ($q) use ($tag) {
                $q->whereJsonContains('tags', $tag);
                $q->orderBy('position', 'asc');
            })->when(isset($content_fields), function ($q) use ($lang, $content_fields) {
                $q->withContent($lang);
            });
        }]);
    }

    /**
     * Scope to append a media relation to the result, including title and medium properties.
     *
     * @param  Builder $query
     * @param  string $tag Select the related media that has this tag, or all if ommited
     * @param  string $fields An array of fields of the Medium entities to include, ['id', 'properties'] if omitted. To automatically generate urls, please include id, and properties
     * @param  string $lang Language of the content fields of the medium
     * @param  string $content_fields Restrict the content fields of the media, or 'title' if ommited
     * @return Builder
     */
    public function scopeWithMedia($query, $tag = null, $fields = null, $lang = null, $content_fields = 'title') {
        $fields = $fields === null ? ['id', 'properties->format', 'properties->mimeType', 'properties->isWebImage', 'properties->isImage', 'properties->isAudio', 'properties->isWebAudio', 'properties->isVideo', 'properties->isWebVideo', 'properties->isDocument'] : $fields;
        $lang = $lang ?? Config::get('kusikusi_website.langs', [''])[0];
        $query->with(['media' => function ($relation) use ($tag, $fields, $lang, $content_fields) {
            $relation->select($fields)
            ->when(isset($tag), function ($q) use ($tag) {
                $q->whereJsonContains('tags', $tag);
                $q->orderBy('position', 'asc');
            })->when(isset($content_fields), function ($q) use ($lang, $content_fields) {
                $q->withContent($lang);
            });
        }]);
    }

    /**
     * Scope a query to only get entities being called by another of type medium.
     *
     * @param Builder $query
     * @param number $entity_id The id of the entity calling the media
     * @return Builder
     * @throws \Exception
     */
    public function scopeMediaOf($query, $entity_id, $tag = null)
    {
        $query->join('entities_relations as relation_media', function ($join) use ($entity_id, $tag) {
            $join->on('relation_media.called_entity_id', '=', 'entities.id')
                ->where('relation_media.caller_entity_id', '=', $entity_id)
                ->where('relation_media.kind', '=', EntityRelation::RELATION_MEDIA)
                ->when($tag, function ($q) use ($tag) {
                    return $q->whereJsonContains('relation_media.tags', $tag);
                });
        })
            ->addSelect( 'relation_media.relation_id as medium.relation_id', 'relation_media.position as medium.position', 'relation_media.depth as medium.depth', 'relation_media.tags as medium.tags');
    }

    /**
     * Scope to order the result by a content field.
     *
     * @param Builder $query
     * @param number $entity_id The id of the entity calling the media
     * @return Builder
     * @throws \Exception
     */
    public function scopeOrderByContent($query, $field, $order = 'ASC', $lang = null)
    {
        $query->leftJoin("entities_contents as content_{$field}", function ($join) use ($field, $lang, $order) {
            $join->on("content_{$field}.entity_id", "entities.id")
                ->where("content_{$field}.field", $field)
                ->where("content_{$field}.lang", $lang)
            ;
        })
        ->orderBy("content_{$field}.text", $order);
    }
    public function scopeOrderByContents($query, $field, $order, $lang = null) {
        return $this->scopeOrderByContent($query, $field, $order, $lang);
    }

    /**
     * Scope to filter by a content field.
     *
     * @param Builder $query
     * @return Builder
     * @throws \Exception
     */
    public function scopeWhereContent($query, $field, $param2, $param3, $param4)
    {
        if (Str::contains($param2, ['=', '<', '>', 'like'])) {
            $operator = $param2;
            $value = $param3;
            $lang = $param4;
        } else {
            $operator = '=';
            $value = $param2;
            $lang = $param3;
        }
        $query->leftJoin("entities_contents as content_{$field}", function ($join) use ($field, $lang) {
            $join->on("content_{$field}.entity_id", "entities.id")
                ->where("content_{$field}.field", $field)
                ->where("content_{$field}.lang", $lang)
            ;
        })
        ->where("content_{$field}.text", $operator, $value);
    }
    public function scopeWhereContents($query, $field, $param2, $param3, $param4) {
        return $this->scopeWhereContent($query, $field, $param2, $param3, $param4);
    }

    /**
     * Scope for full text search.
     *
     * @param Builder $query
     * @return Builder
     * @throws \Exception
     */
    public function scopeSearchContent($query, $textToSearch, $fields, $lang)
    {
        switch (env('DB_CONNECTION')) {
            case 'mysql':
                // $fulltextSearchQuery = 'MATCH (content.text) AGAINST ("'.$textToSearch.'" IN NATURAL LANGUAGE MODE)';
                $fulltextSearchQuery = 'MATCH (content.text) AGAINST ("'.$textToSearch.'*" IN BOOLEAN MODE)';
                break;
        }
        $query->join("entities_contents as content", function ($join) use ($textToSearch, $fields, $lang, $fulltextSearchQuery) {
            $join->on("content.entity_id", "entities.id")
            ->where("content.lang", $lang)
            ->whereRaw($fulltextSearchQuery); //mysql
            if ($fields) {
                if (is_array($fields)) return $join->whereIn('content.field', $fields);
                if (is_string($fields)) return $join->where('content.field', $fields);
            }
        })
        ->addSelect(DB::raw($fulltextSearchQuery.' as score')); //mysql
    }

    /**
     * AGGREGATES
     */


    /**
     * PRIVATES AND OTHERS
     */

    /**
     * Returns the id of the instance, if none is defined, it creates one
     */
    private function getId() {
        if (!isset($this->id)) {
            $this->id = self::createId();
        }
        return $this->id;
    }
    private static function createId() {
        do {
            $id = (string) Shortid::generate(Config::get('cms.short_id_length', 10));
            $found_duplicate = Entity::find($id);
        } while (!!$found_duplicate);
        return $id;
    }
    /**
     * Define a one-to-many plucked relationship.
     *
     * @param  string  $related
     * @param  string|null  $foreignKey
     * @param  string|null  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pluckedHasMany($related, $foreignKey = null, $localKey = null, $pluckValue = null, $pluckKey= null) {
        $instance = $this->newRelatedInstance($related);
        $foreignKey = $foreignKey ?: $this->getForeignKey();
        $localKey = $localKey ?: $this->getKeyName();
        return $this->newPluckedHasMany(
            $instance->newQuery(), $this, $instance->getTable().'.'.$foreignKey, $localKey, $pluckValue, $pluckKey
        );
    }
    /**
     * Instantiate a new PluckedHasMany relationship.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @param  string  $foreignKey
     * @param  string  $localKey
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    protected function newPluckedHasMany(\Illuminate\Database\Eloquent\Builder $query, Model $parent, $foreignKey, $localKey, $pluckValue, $pluckKey)
    {
        return new PluckedHasMany($query, $parent, $foreignKey, $localKey, $pluckValue, $pluckKey);
    }

    /**
     * Version Increments
     */
    /* private */
    public static function incrementEntityVersion($entity_id) {
        $e = DB::table('entities')
            ->where('id', $entity_id);
        $e->increment('version');
        $e->increment('version_full');
        self::incrementTreeVersion($entity_id);
        self::incrementRelationsVersion($entity_id);
    }
    private static function incrementTreeVersion($entity_id) {
        $ancestors = Entity::select('id')->ancestorsOf($entity_id)->get();
        if (!empty($ancestors)) {
            foreach ($ancestors as $ancestor) {
                $e = DB::table('entities')
                    ->where('id', $ancestor['id']);
                $e->increment('version_tree');
                $e->increment('version_full');
            }
        }
    }
    public static function incrementRelationsVersion($entity_id) {
        $relating = Entity::select('id')->relating($entity_id)->get();
        if (!empty($relating)) {
            foreach ($relating as $entityRelating) {
                $e = DB::table('entities')
                    ->where('id', $entityRelating['id']);
                $e->increment('version_relations');
                $e->increment('version_full');
                $ancestors = Entity::select('id')->ancestorsOf($entityRelating->id)->get();
                if (!empty($ancestors)) {
                    foreach ($ancestors as $ancestor) {
                        $e = DB::table('entities')
                            ->where('id', $ancestor['id']);
                        $e->increment('version_full');
                    }
                }
            }
        }
    }
    public static function recreateAncestorsRelations($entity_id_or_instance, $recursive = true) {
      if (is_string($entity_id_or_instance)) {
        $entity = Entity::find($entity_id_or_instance);
      } else {
        $entity = $entity_id_or_instance;
      }
      EntityRelation::where("caller_entity_id", $entity->id)->where('kind', EntityRelation::RELATION_ANCESTOR)->delete();
      $instance = self::getInstanceFromName($entity->model);
      if ($instance->ancestorsRelations) {

        // Create the ancestors relations
        $parentEntity = Entity::with('routes')->find($entity['parent_entity_id']);
        if ($parentEntity && $entity['parent_entity_id'] != NULL) {
          EntityRelation::create([
              "caller_entity_id" => $entity->id,
              "called_entity_id" => $parentEntity->id,
              "kind" => EntityRelation::RELATION_ANCESTOR,
              "depth" => 1
          ]);
          $depth = 2;
          $ancestors = Entity::select('id')->ancestorsOf($parentEntity->id)->orderBy('ancestor.depth')->get();
          foreach ($ancestors as $ancestor) {
            EntityRelation::create([
                "caller_entity_id" => $entity->id,
                "called_entity_id" => $ancestor->id,
                "kind" => EntityRelation::RELATION_ANCESTOR,
                "depth" => $depth
            ]);
            $depth++;
          }
        }
        if ($recursive) {
          $children = self::select('id', 'model', 'parent_entity_id')->where('parent_entity_id', $entity->id)->get();
          foreach ($children as $child) {
            self::recreateAncestorsRelations($child);
          }
        }
      }
    }

    /**
     * BOOT
     */
    protected static function boot()
    {
        $modelName = Str::before(Str::studly(Str::afterLast(get_called_class(), '\\')), 'Base');
        if ($modelName !== 'Entity') {
            static::addGlobalScope($modelName, function (Builder $builder) use ($modelName) {
                $builder->where('model', $modelName);
            });
        }
        parent::boot();

        static::retrieved(function (Model $entity) {
            $explodedAttributes = [];
            $propertiesPrefix1 = "json_unquote(json_extract(`properties`, '$.\"";
            $propertiesPrefix2 = 'properties.';
            foreach ($entity->attributes as $key => $value) {
                if (Str::startsWith($key, $propertiesPrefix1)) {
                    Arr::set($explodedAttributes, $propertiesPrefix2.preg_replace("/[\\\"\')]/", "", Str::after($key, $propertiesPrefix1)), Str::startsWith($value, '[') ||  Str::startsWith($value, '{') ? json_decode($value) : (is_numeric($value) ? (float) $value : $value));
                    unset($entity->attributes[$key]);
                } else if (Str::startsWith($key, $propertiesPrefix2)) {
                    Arr::set($explodedAttributes, $key, !Str::startsWith($value, '"') ?  json_decode($value) : $value);
                    unset($entity->attributes[$key]);
                } else if (Str::contains($key, '.')) {
                    $prop = Str::after($key, '.');
                    if ($prop === 'tags') $value = \json_decode($value);
                    $explodedAttributes[Str::before($key, '.')][$prop] = $value;
                    unset($entity->attributes[$key]);
                }
            }
            foreach($explodedAttributes as $att => $explodedAttribute) {
                $entity->$att = $explodedAttribute;
            }
        });
        static::creating(function (Model $entity) {

            // Set a default id
            if (!isset($entity[$entity->getKeyName()])) {
                $entity->setAttribute($entity->getKeyName(), self::createId());
            } else {
                if (self::find($entity[$entity->getKeyName()])) {
                   abort(403, 'Duplicated Entity ID '.$entity[$entity->getKeyName()]);
                }
                $entity->setAttribute($entity->getKeyName(), substr($entity[$entity->getKeyName()], 0, 32));
            }

            // Setting default values
            if (!isset($entity['model']))        { $entity['model'] = 'Entity'; }
            if (!isset($entity['view']))         { $entity['view'] = Str::snake($entity['model']); }
            if (!isset($entity['visibility']))   { $entity['visibility'] = 'public'; }
            if (!isset($entity['properties']))   { $entity['properties'] = new \ArrayObject(); }
            if (!isset($entity['langs']))        { $entity['langs'] = Config::get('kusikusi_website.langs', ['']); }
            if (!isset($entity['published_at'])) { $entity['published_at'] = Carbon::now(); }
            $entity['version'] = 1;
            $entity['version_tree'] = 1;
            $entity['version_relations'] = 1;
            $entity['version_full'] = 1;
        });
        static::saved(function (Model $entity) {
            // Recreate ancestor relations
            if ($entity->isDirty('parent_entity_id')) {
              self::recreateAncestorsRelations($entity->id, true);
            }

            // Update versions
            self::incrementEntityVersion($entity->id);

            // Setting archive version
            $config = Config::get('kusikusi_models.store_versions', false);
            if(config('kusikusi_models.store_versions') === true){
                EntityArchive::archive($entity->id, 'version');
            }
        });

    }
    protected static function getEntityClassName($name) {
        if (class_exists("App\\Models\\".$name)) {
            $modelClassName = "App\\Models\\".$name;
        } else if (class_exists("Kusikusi\\Models\\".$name)) {
            $modelClassName = "Kusikusi\\Models\\".$name;
        } else {
            $modelClassName = "Kusikusi\\Models\\Entity";
        }
        return $modelClassName;
    }
    protected static function getInstanceFromName($name) {
        $modelClassName = self::getEntityClassName($name);
        return new $modelClassName;
    }
}
