<?php

namespace App\Http\Resources\Backend;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorProfileResource extends JsonResource
{
    protected $withoutFields = [];

    /**
     * Set Hidden Item
     */
    public function hide(array $hide = []){
        $this->withoutFields = $hide;
        return $this;
    }

    /**
     * Filter Hide Items
     */
    protected function filter($data){
        return collect($data)->forget($this->withoutFields)->toArray();
    }

    /**
     * Collection
     */
    public static function collection($resource){
        return tap(new VendorProfileCollection($resource), function ($collection) {
            $collection->collects = __CLASS__;
        });
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->filter([
            "id"            => $this->id ?? "",
            "vendor_id"            => $this->vendor_id ?? "",
            "factory_profile_name"            => $this->factory_profile_name ?? "",
            "logo"          => $this->logo ?? "",
            "contact_number"          => $this->contact_number ?? "",
            "email"         => $this->email ?? "",
            "address"         => $this->address ?? "",
            "remarks"         => $this->remarks ?? "",
            "status"         => $this->status ?? "",

            // "created_at"         => $this->created_at ?? "",
            // "updated_at"         => $this->updated_at ?? "",
            // "deleted_by"         => $this->deleted_by ?? "",
            // "deleted_date"         => $this->deleted_date ?? "",
            "created_by"    => isset($this->created_by) ? (new AdminResource($this->created_by))->hide(["created_by","updated_by,deleted_by,deleted_date"]) : null,
            "updated_by"    => isset($this->updated_by) ? (new AdminResource($this->updatedBy))->hide(["created_by","updated_by,deleted_by,deleted_date"]) : null,
            "deleted_by"    => isset($this->deleted_by) ? (new AdminResource($this->deleted_by))->hide(["created_by","updated_by,deleted_by,deleted_date"]) : null,
            "deleted_date"  => isset($this->deleted_date) ? (new AdminResource($this->deleted_date))->hide(["created_by","updated_by,deleted_by,deleted_date"]) : null,

        ]);
    }
}
