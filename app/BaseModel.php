<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

abstract class BaseModel extends Model
{
  public function __construct(array $attributes = []) {
    parent::__construct($attributes);
  }

  // NOTE: must not be followed by ->where()
  public function scopeDistinctOn($query, $columns, $orderBy = null, $orderDir = 'asc') {
    if (!is_array($columns)) {
      $columns = [$columns];
    }

    switch (config('database.default')) {
      case 'pgsql':
        foreach ($columns as $index => $column) {
          $columns[$index] = '"'.str_replace('"', '""', $column).'"';
        }
        $orderDir = strtolower($orderDir) == 'asc' ? 'asc' : 'desc';
        $sort = isset($orderBy) ? ', "'.str_replace('"', '""', $orderBy).'" '.$orderDir : '';

        $query->from(DB::raw(
          '(select distinct on ('.implode(', ', $columns).') * from '.str_get_between($query->toSql(), 'from ', ' ')
        ));

        $query->where(DB::raw(
          '1=1 ORDER BY '.implode(', ', $columns).$sort.') d where 1'
        ), '=', '1');
      break;

      case 'mysql':
        return $query->groupBy($columns)->distinct();
      break;

      default:
        dd('\'distinctOn\' is not supported for databases of type \''.config('database.default').'\'');
      break;
    }
  }

  public function scopeRandom($query) {
    switch (config('database.default')) {
      case 'pgsql':
        return $query->orderBy(DB::raw('RANDOM()'));
      break;

      case 'mysql':
        return $query->orderBy(DB::raw('RAND()'));
      break;

      case 'sqlite':
        return $query->orderBy(DB::raw('RANDOM()'));
      break;

      default:
        dd('\'random\' is not supported for databases of type \''.config('database.default').'\'');
      break;
    }
  }

  public function scopeWhereLike($query, $attribute, $like) {
    switch (config('database.default')) {
      case 'pgsql':
        $operator = 'ilike';
      break;
      default:
        $operator = 'like';
      break;
    }
    return $query->where($attribute, $operator, $like);
  }
}
