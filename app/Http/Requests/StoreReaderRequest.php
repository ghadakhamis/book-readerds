<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreReaderRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'start_page' => 'required|integer|min:1|max:'.$this->book->pages_count,
            'end_page'   => 'required|integer|gte:start_page|max:'.$this->book->pages_count,
        ];
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                /** @var User $user */
                $user = Auth::user();
                $reader = $user->readers()->bookId($this->book->id)->startPage($this->start_page)->endPage($this->end_page)->first();
                
                if ($reader) {
                    $validator->errors()->add('start_page', trans('errors.page_read'));
                }
            }
        ];
    }
}
