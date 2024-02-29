<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FoodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'categoryId' => 'integer|nullable',
            'sortBy' => ['nullable', 'regex:/^\w+,(asc|desc)$/i'],
            'pageNumber' => 'integer|min:1|nullable',
            'pageSize' => 'integer|min:1|nullable',
        ];
    }

    /**
     * Get the validated data with filter parameters.
     *
     * @return array
     */
    public function filterData()
    {
        $filterData = [];

        if (!is_null($this->query('foodName'))) {
            $filterData['foodName'] = $this->query('foodName');
        }
    
        if (!is_null($this->query('categoryId'))) {
            $filterData['categoryId'] = intval($this->query('categoryId'));
        }
    
        return $filterData;
    }

    /**
     * Get the validated data with pagination parameters.
     *
     * @return array
     */
    public function paginationData()
    {
        $sortBy = $this->query('sortBy');

        $sortField = 'foodName';
        $sortDirection = 'asc';

        if ($sortBy) {
            list($sortField, $sortDirection) = explode(',', $sortBy);
            $sortDirection = strtolower($sortDirection);
        }
        
        return [
            'pageNumber' => intval($this->query('pageNumber', 1)),
            'pageSize' => intval($this->query('pageSize', 10)),
            'sortBy' => [
                'field' => $sortField,
                'direction' => $sortDirection,
            ],
        ];
    }
}
