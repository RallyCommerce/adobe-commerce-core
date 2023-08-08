<?php

namespace Rally\Checkout\Service\Customer;

use Rally\Checkout\Service\AbstractResponseMapperService;

class ResponseMapper extends AbstractResponseMapperService
{
    protected array $mapFields = [
        'city' => 'setCity',
        'email' => 'setEmail',
        'company' => 'setCompany',
        'country_code' => 'setCountryId',
        'first_name' => 'setFirstname',
        'last_name' => 'setLastname',
        'zip' => 'setPostcode',
        'province' => 'setRegion',
        'code' => 'setRegionCode',
        'region_id' => 'setRegionId',
        'address' => 'setStreet',
        'phone' => 'setTelephone',
        'same_as_billing' => 'setSameAsBilling',
        'save_in_address_book' => 'setSaveInAddressBook'
    ];
}
