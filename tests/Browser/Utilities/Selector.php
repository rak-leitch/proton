<?php declare(strict_types = 1);
 
namespace Adepta\Proton\Tests\Browser\Utilities;
 
class Selector
{
    /**
     * Get the selector for a list body cell.
     * 
     * @param int $row
     * @param int $column
     *
     * @return string
    */
    public static function listCell(int $row, int $column): string
    {
        return ".v-data-table tbody tr:nth-of-type({$row}) td:nth-of-type({$column})";
    }
    
    /**
     * Get the selector for a list header cell.
     * 
     * @param int $offset
     *
     * @return string
    */
    public static function listHeader($offset): string
    {
        return ".v-data-table thead th:nth-of-type({$offset})";
    }
}
