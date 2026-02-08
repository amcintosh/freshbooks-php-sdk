<?php

declare(strict_types=1);

namespace amcintosh\FreshBooks\Model;

abstract class DataModel
{
    /**
     * Create a new instance of the data model from an API response array.
     *
     * @param array $data The API response data to map to the data model.
     * @return DataModel An instance of the data model populated with the response data.
     */
    abstract public static function fromResponse(array $data): self;

    /**
     * Serialize the data model to an array to POST or PUT to FreshBooks, removing any read-only fields.
     *
     * @return array
     */
    abstract public function toRequest(): array;

    /**
     * Helper function to convert content from DataModel objects or arrays of DataModel objects.
     *
     * @param array $data The data array to modify.
     * @param string $key The key in the data array to set.
     * @param mixed $value The value to convert, can be a DataModel, an array of DataModel, or any other type.
     * @return void
     */
    public static function convertContent(array &$data, string $key, mixed $value): void
    {
        if ($value === null) {
            return;
        }
        if (is_array($value)) {
            $convertedItems = [];
            foreach ($value as $item) {
                if ($item instanceof DataModel) {
                    $convertedItems[] = $item->toRequest();
                } else {
                    $convertedItems[] = $item;
                }
            }
            $data[$key] = $convertedItems;
        } elseif ($value instanceof DataModel) {
            $data[$key] = $value->toRequest();
        } else {
            $data[$key] = $value;
        }
    }
}
