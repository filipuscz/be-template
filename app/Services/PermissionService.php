<?php

namespace App\Services;

use App\Enums\QueryAcceptedComparatorEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Permission;

class PermissionService extends BaseService
{
    public function __construct(Permission $permission)
    {
        parent::__construct($permission);
    }

    protected function getCacheVersion(): string
    {
        return (string) Cache::rememberForever('permissions_cache_version', fn () => time());
    }

    protected function incrementCacheVersion(): void
    {
        Cache::put('permissions_cache_version', time());
    }

    public function findByIndexes(
        array $indexes,
        bool $any,
        ?int $limit,
        array $orderBy,
        QueryAcceptedComparatorEnum $comparator = QueryAcceptedComparatorEnum::EQUAL,
        ?array $filters = null,
        ?array $fields = null,
        ?array $relation = null,
        ?array $defaultOrderBy = null,
    ): Collection|LengthAwarePaginator|CursorPaginator|Builder {
        $argsHash = md5(serialize(func_get_args()));
        $cacheKey = 'permissions_query_'.$this->getCacheVersion().'_'.$argsHash;

        return Cache::rememberForever($cacheKey, function () use (
            $indexes, $any, $limit, $orderBy, $comparator, $filters, $fields, $relation, $defaultOrderBy
        ) {
            return parent::findByIndexes(
                $indexes, $any, $limit, $orderBy, $comparator, $filters, $fields, $relation, $defaultOrderBy
            );
        });
    }

    public function create(array $data): Model
    {
        $permission = parent::create($data);
        $this->incrementCacheVersion();

        return $permission;
    }

    public function update(array $data, mixed $idOrSlug): ?Model
    {
        $permission = parent::update($data, $idOrSlug);
        if ($permission) {
            $this->incrementCacheVersion();
        }

        return $permission;
    }

    public function findById($idOrSlug): ?Model
    {
        $cacheKey = 'permissions_id_'.$this->getCacheVersion().'_'.$idOrSlug;

        return Cache::rememberForever($cacheKey, function () use ($idOrSlug) {
            return parent::findById($idOrSlug);
        });
    }

    public function delete(mixed $idOrSlug): void
    {
        parent::delete($idOrSlug);
        $this->incrementCacheVersion();
    }
}
