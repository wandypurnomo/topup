<?php


namespace Wandxx\Topup\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Wandxx\Support\Interfaces\DefaultRequestInterface;

class UpdateTopupRequest extends FormRequest implements DefaultRequestInterface
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "amount" => "required|numeric",
            "price" => "required|numeric",
            "method" => "required",
            "method_identifier_name" => "required",
            "method_identifier_number" => "required"
        ];
    }

    public function data(): array
    {
        $this->merge(["metadata" => $this->_buildMeta()]);
        $only = ["amount", "price", "metadata"];

        return $this->only($only);
    }

    private function _buildMeta()
    {
        return [
            "method" => $this->input("method"),
            "method_identifier_name" => $this->input("method_identifier_name"),
            "method_identifier_number" => $this->input("method_identifier_number"),
            "failed_message" => "",
        ];
    }
}