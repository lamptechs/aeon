<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManualPoResource extends JsonResource
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
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->filter([
            "id"                        => $this->id ?? null,
            "buyer_id"                  => $this->buyer_id ?? null,
            "vendor_id"                 => $this->vendor_id ?? null,
            "note"                      => $this->note ?? null,
            "terms_conditions"          => $this->terms_conditions ?? null,
            "first_delivery_date"       => $this->first_delivery_date ?? null,
            "second_shipment_date"      => $this->second_shipment_date ?? null,
            "vendor_po_date"            => $this->vendor_po_date ?? null,
            "current_buyer_po_price"    => $this->current_buyer_po_price ?? null,
            "vendor_po_price"           => $this->vendor_po_price ?? null,
            "accessorize_price"         => $this->accessorize_price ?? null,
            "plm_no"                    => $this->plm_no ?? null,
            "description"               => $this->description ?? null,
            "fabric_quality"            => $this->fabric_quality ?? null,
            "fabric_content"            => $this->fabric_content ?? null,
            "created_at"                => $this->created_at ?? null,
            "updated_at"                => $this->updated_at ?? null,
            "upload_files"              => PictureGarmentsResource::collection($this->fileInfo),
            "upload_files_artwork"      => PoArtworkResource::collection($this-> fileInfoArt),


        ]);
    }
}
