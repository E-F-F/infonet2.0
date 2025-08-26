<?php

namespace Modules\CRMS\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class CRMSPeopleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                              => $this->id,
            'staff_id'                        => $this->hrms_staff_id,
            'staff_name'                      => optional(optional($this->staff)->personal)->full_name,
            'people_type'                     => $this->people_type,
            'people_status'                   => $this->people_status,
            'customer_name'                   => $this->customer_name,
            'grading'                         => $this->grading,
            'last_contact_date'               => $this->last_contact_date,
            'under_company_registration'      => $this->under_company_registration,
            'crms_company_id'                 => $this->crms_company_id,
            'crms_company_name'               => optional($this->company)->company_name,

            // Personal Details
            'id_type'                         => $this->id_type,
            'id_number'                       => $this->id_number,
            'tin'                             => $this->tin,
            'dob'                             => $this->dob,
            'owner_phone'                     => $this->owner_phone,
            'home_no'                         => $this->home_no,
            'email'                           => $this->email,

            // Other Contact
            'contact_person_name'             => $this->contact_person_name,
            'contact_person_phone'            => $this->contact_person_phone,
            'office_no'                       => $this->office_no,
            'fax_no'                          => $this->fax_no,

            // Home Address
            'primary_address'                 => $this->primary_address,
            'primary_postcode'                => $this->primary_postcode,
            'primary_city'                    => $this->primary_city,
            'primary_state'                   => $this->primary_state,
            'primary_country'                 => $this->primary_country,

            // Postal Address
            'postal_address'                  => $this->postal_address,
            'postal_postcode'                 => $this->postal_postcode,
            'postal_city'                     => $this->postal_city,
            'postal_state'                    => $this->postal_state,
            'postal_country'                  => $this->postal_country,

            // Others
            'zone'                            => $this->zone,
            'crms_people_race_id'             => $this->crms_people_race_id,
            'crms_people_race_name'           => optional($this->race)->name,
            'religion'                        => $this->religion,
            'crms_people_income_id'           => $this->crms_people_income_id,
            'crms_people_income_name'         => optional($this->income)->name,
            'marital_status'                  => $this->marital_status,
            'crms_people_occupation_id'       => $this->crms_people_occupation_id,
            'crms_people_occupation_name'     => optional($this->occupation)->name,
            'crms_business_nature_id'         => $this->crms_business_nature_id,
            'crms_business_nature_name'       => optional($this->businessNature)->name,
            'is_corporate'                    => $this->is_corporate,
            'lifestyle_interest'              => $this->lifestyle_interest,
            'link_customer'                   => $this->link_customer,
            'account_terms'                   => $this->account_terms,
            'price_scheme'                    => $this->price_scheme,
        ];
    }
}
