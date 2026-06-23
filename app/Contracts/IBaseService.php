<?php

namespace App\Contracts;

use App\Enums\QueryAcceptedComparatorEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Interface for base service operations.
 */
interface IBaseService
{
    /**
     * Create a new record.
     *
     * @param  array  $attr  Data to create the record.
     * @return Model Created model instance.
     */
    public function create(array $attr): Model;

    /**
     * Create multiple records.
     *
     * @param  array  $data  Array of data to create multiple records.
     * @return array of created records.
     */
    public function createMany(array $data): array;

    /**
     * Find a record by its ID.
     *
     * @param  mixed  $id  ID of the record to find.
     * @return Model|null Model instance if found, otherwise null.
     */
    public function findById(mixed $id): ?Model;

    /**
     * Find records by multiple indexes.
     *
     * @param  array  $indexes  Array of indexes to search.
     * @param  bool  $any  Whether to search for records matching any index.
     * @param  int  $limit  Maximum number of records to return.
     * @param  array  $orderBy  Array of columns to order the results by.
     * @param  QueryAcceptedComparatorEnum  $comparator  Comparator for the query.
     * @param  array|null  $relation  Optional relation to include in the query.
     * @param  array|null  $defaultOrderBy  Default order by columns.
     * @param  array|null  $fields  Columns to search in.
     * @return Collection|LengthAwarePaginator|Builder data matching the criteria.
     */
    public function findByIndexes(
        array $indexes,
        bool $any,
        ?int $limit,
        array $orderBy,
        QueryAcceptedComparatorEnum $comparator,
        ?array $filters = null,
        ?array $fields = null,
        ?array $relation = null,
        ?array $defaultOrderBy = null,
    ): Collection|LengthAwarePaginator|Builder;

    /**
     * Get all records
     *
     * @return Collection Model instance.
     */
    public function list(): Collection;

    /**
     * Update a records
     *
     * @param  array  $data  attributes data.
     * @param  $idOrSlug  id or slug of instance to delete.
     * @return Model|null updated instance
     */
    public function update(array $data, mixed $idOrSlug): ?Model;

    /**
     * Delete a record.
     *
     * @param  $idOrSlug  id or slug of instance to delete.
     */
    public function delete(mixed $idOrSlug): void;

    /**
     * Delete multiple records.
     *
     * @param  array  $data  Array of model instances to delete.
     * @return ?int total successfully deleted data
     */
    public function deleteMany(array $data): ?int;

    /**
     * Update multiple model instances.
     *
     * @param  array  $ids  An array of IDs or slugs of the model instances to update.
     * @param  array  $updateData  An array of data to update, where the key is the ID or slug, and the value is the data to update.
     * @return int|null Updated model instances count.
     *
     * @throws \Exception If any update fails.
     */
    public function updateMany(array $ids, array $updateData): ?int;
}
